<div class="row gx-4 gy-4 dashboard-card-row">
    @forelse($candles as $candle)
        <div class="col-12 col-sm-6 d-flex">
            <div class="todo-card card shadow dashboard-coin-card border-0 flex-fill">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <span class="coin-symbol font-weight-bold mr-2 {{ $candle->color_status }}-text" style="font-size:1.3rem; min-width:70px;">{{ $candle->symbol }}</span>
                        <span class="badge badge-light border mr-2">{{ $candle->interval }}</span>
                        <span class="ml-auto font-weight-bold price-change-percent {{ $candle->color_status }}-text" style="font-size:1.4rem;">
                            {{ $candle->price_change_percent_24h > 0 ? '+' : '' }}{{ number_format($candle->price_change_percent_24h, 2) }}%
                        </span>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6 detail-label">Open Price:</div>
                        <div class="col-6 detail-value">${{ $candle->open }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6 detail-label">Close Price:</div>
                        <div class="col-6 detail-value">${{ $candle->close }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6 detail-label">Volume:</div>
                        <div class="col-6 detail-value">{{ $candle->volume }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6 detail-label"># Trades:</div>
                        <div class="col-6 detail-value">{{ $candle->number_of_trades }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6 detail-label">Open Time:</div>
                        <div class="col-6 detail-value">{{ $candle->open_time }}</div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center text-muted">
            No records found.
        </div>
    @endforelse
</div> 