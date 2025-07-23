<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Candle;

class CandleController extends Controller
{
    public function index(Request $request)
    {
        $symbols = Candle::distinct()->pluck('symbol');
        $query = Candle::query();

        // Filter by symbol
        if ($request->filled('symbol')) {
            $query->where('symbol', $request->symbol);
        }

        // Filter by price change (change_filter)
        if ($request->filled('change_filter')) {
            if ($request->change_filter == 'positive') {
                $query->where('price_change_percent_24h', '>', 0);
            } elseif ($request->change_filter == 'negative') {
                $query->where('price_change_percent_24h', '<', 0);
            }
        }

        // Sorting (sort)
        if ($request->filled('sort')) {
            if ($request->sort == 'price_asc') {
                $query->orderBy('open', 'asc');
            } elseif ($request->sort == 'price_desc') {
                $query->orderBy('open', 'desc');
            } elseif ($request->sort == 'change_asc') {
                $query->orderBy('price_change_percent_24h', 'asc');
            } elseif ($request->sort == 'change_desc') {
                $query->orderBy('price_change_percent_24h', 'desc');
            } else {
                $query->orderBy('open_time', 'desc');
            }
        } else {
            $query->orderBy('open_time', 'desc');
        }

        $candles = $query->get();

        return view('index', [
            'candles' => $candles,
            'symbols' => $symbols
        ]);
    }
}
