<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('candles', function (Blueprint $table) {
            $table->id();
            $table->string('symbol');
            $table->string('interval');
            $table->timestamp('open_time')->index();
            $table->decimal('open', 18, 8);
            $table->decimal('high', 18, 8);
            $table->decimal('low', 18, 8);
            $table->decimal('close', 18, 8);
            $table->decimal('volume', 20, 8);
            $table->timestamp('close_time');
            $table->decimal('quote_asset_volume', 20, 8);
            $table->integer('number_of_trades');
            $table->decimal('taker_buy_base_asset_volume', 20, 8);
            $table->decimal('taker_buy_quote_asset_volume', 20, 8);
            $table->boolean('is_bullish_engulfing')->default(false);
            $table->decimal('price_change_24h', 20, 8)->nullable();
            $table->decimal('price_change_percent_24h', 10, 4)->nullable();
            $table->enum('color_status', ['red', 'green'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candles');
    }
};
