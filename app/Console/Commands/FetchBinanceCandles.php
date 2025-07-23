<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Models\Candle;
use Carbon\Carbon;

class FetchBinanceCandles extends Command
{
    protected $signature = 'binance:fetch {symbols=BTCUSDT,ETHUSDT,NEIROUSDT}';
    protected $description = 'Fetch 15-min candlestick data from Binance and save valid bullish engulfing spot candles for multiple coins';

    public function handle()
    {
        // 🔁 Fetch all USDT trading pairs from Binance API dynamically
        $exchangeInfo = Http::timeout(120)->retry(3, 5000)->get('https://api.binance.com/api/v3/exchangeInfo');
        if (!$exchangeInfo->ok()) {
            $this->error('❌ Failed to fetch exchange info from Binance.');
            return;
        }
        $symbols = collect($exchangeInfo->json()['symbols'])
            ->filter(fn($s) => $s['status'] === 'TRADING' && str_ends_with($s['symbol'], 'USDT'))
            ->pluck('symbol')
            ->unique()
            ->values();
        $interval = '15m';

        $lossCoins = [];
        $profitCoins = [];
        foreach ($symbols as $index => $symbol) {
            if ($index % 50 === 0 && $index > 0) {
                sleep(1); // 1 second delay after every 50 requests
            }
            $this->info("🔍 Checking symbol: $symbol");

            // Get 24hr ticker data
            $ticker24h = \Illuminate\Support\Facades\Http::timeout(120)->retry(3, 5000)->get("https://api.binance.com/api/v3/ticker/24hr", [
                'symbol' => $symbol
            ]);

            if (!$ticker24h->ok()) {
                $this->error("❌ Failed to fetch 24hr ticker for $symbol");
                continue;
            }

            $tickerData = $ticker24h->json();
            if (!isset($tickerData['priceChangePercent']) || !isset($tickerData['lastPrice'])) {
                $this->error("❌ Invalid ticker data for $symbol");
                continue;
            }
            $priceChange = isset($tickerData['priceChange']) ? (float) $tickerData['priceChange'] : null;
            $priceChangePercent = (float) $tickerData['priceChangePercent'];
            $colorStatus = ($priceChangePercent > 0) ? 'green' : 'red';

            if ($colorStatus === 'red') {
                $lossCoins[] = [
                    'symbol' => $symbol,
                    'openPrice' => $tickerData['openPrice'] ?? null,
                    'lastPrice' => $tickerData['lastPrice'] ?? null,
                    'priceChangePercent' => $priceChangePercent
                ];
            } else {
                $profitCoins[] = [
                    'symbol' => $symbol,
                    'openPrice' => $tickerData['openPrice'] ?? null,
                    'lastPrice' => $tickerData['lastPrice'] ?? null,
                    'priceChangePercent' => $priceChangePercent
                ];
            }

            $response = Http::timeout(120)->retry(3, 5000)->get("https://api.binance.com/api/v3/klines", [
                'symbol' => $symbol,
                'interval' => $interval,
                'limit' => 73
            ]);

            if (!$response->ok()) {
                $this->error("❌ Failed to fetch data for $symbol");
                continue;
            }

            $candles = $response->json();

            for ($index = 5; $index < count($candles); $index++) {
                $prev = $candles[$index - 1];
                $current = $candles[$index];

                $prevOpen = (float) $prev[1];
                $prevClose = (float) $prev[4];
                $currOpen = (float) $current[1];
                $currClose = (float) $current[4];

                // ✅ Condition 1: Engulfing (green after red, open == prev close, close > prev open)
                $isEngulfing = (
                    $prevOpen > $prevClose &&
                    $currClose > $currOpen &&
                    abs($prevClose - $currOpen) < 0.000001 &&
                    $currClose > $prevOpen
                );

                if (!$isEngulfing) continue;

                // ✅ Condition 2: Spot (curr open < min of last 4 candles' open and close)
                $isSpot = true;
                for ($j = $index - 5; $j < $index - 1; $j++) {
                    $priorOpen = (float) $candles[$j][1];
                    $priorClose = (float) $candles[$j][4];

                    if ($currOpen >= $priorOpen || $currOpen >= $priorClose) {
                        $isSpot = false;
                        break;
                    }
                }

                if (!$isSpot) continue;

                $openTime = Carbon::createFromTimestampMs($current[0])->setTimezone('Asia/Karachi');
                $closeTime = Carbon::createFromTimestampMs($current[6])->setTimezone('Asia/Karachi');

                if (Candle::where('symbol', $symbol)->where('open_time', $openTime)->exists()) {
                    continue;
                }

                Candle::create([
                    'symbol' => $symbol,
                    'interval' => $interval,
                    'open_time' => $openTime,
                    'open' => $currOpen,
                    'high' => (float) $current[2],
                    'low' => (float) $current[3],
                    'close' => $currClose,
                    'volume' => (float) $current[5],
                    'close_time' => $closeTime,
                    'quote_asset_volume' => (float) $current[7],
                    'number_of_trades' => (int) $current[8],
                    'taker_buy_base_asset_volume' => (float) $current[9],
                    'taker_buy_quote_asset_volume' => (float) $current[10],
                    'price_change_24h' => $priceChange,
                    'price_change_percent_24h' => $priceChangePercent,
                    'color_status' => $colorStatus,
                    'is_bullish_engulfing' => true
                ]);

                $colorEmoji = $colorStatus === 'green' ? '🟢' : '🔴';
                $this->info("✅ [$symbol] $colorEmoji {$priceChangePercent}% | Engulfing Spot Candle at: $openTime");
            }
        }

        // After all symbols processed, send email if there are loss coins
        if (count($lossCoins) > 0) {
            if (!config('mail.mailers.smtp.host')) {
                $this->error("❌ Mail configuration not set!");
                return;
            }
            $to = config('mail.from.address', 'example@gmail.com');
            $body = "🔴 Loss Coins Report (24h) - " . now()->format('Y-m-d H:i:s') . "\n\n";
            $body .= "Total Loss Coins: " . count($lossCoins) . "\n";
            $body .= str_repeat("-", 50) . "\n\n";
            foreach ($lossCoins as $coin) {
                $body .= "📉 {$coin['symbol']}\n";
                $body .= "   Previous: $" . number_format($coin['openPrice'], 4) . "\n";
                $body .= "   Current:  $" . number_format($coin['lastPrice'], 4) . "\n";
                $body .= "   Change:   " . number_format($coin['priceChangePercent'], 2) . "%\n\n";
            }
            \Illuminate\Support\Facades\Mail::raw($body, function ($message) use ($to) {
                $message->to($to)->subject('Loss Coins Report (Binance 24h)');
            });
            $this->info("📧 Loss coins report sent to $to");
        }

        $this->info("🎯 Finished scanning all symbols.");
    }
}
