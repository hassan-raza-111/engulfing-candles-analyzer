<?php

// app/Models/Candle.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Candle extends Model
{
    protected $fillable = [
        'symbol', 'interval', 'open_time', 'open', 'close', 'high', 'low', 'is_bullish_engulfing',
        'volume', 'close_time', 'quote_asset_volume', 'number_of_trades', 'taker_buy_base_asset_volume', 'taker_buy_quote_asset_volume',
        'price_change_24h', 'price_change_percent_24h', 'color_status'
    ];

    protected $casts = [
        'open_time' => 'datetime',
        'close_time' => 'datetime',
        'is_bullish_engulfing' => 'boolean',
        'volume' => 'float',
        'quote_asset_volume' => 'float',
        'number_of_trades' => 'integer',
        'taker_buy_base_asset_volume' => 'float',
        'taker_buy_quote_asset_volume' => 'float',
        'price_change_24h' => 'float',
        'price_change_percent_24h' => 'float',
    ];
}
