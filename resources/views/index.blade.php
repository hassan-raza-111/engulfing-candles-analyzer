@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <div class="container">
        <!-- Page Header -->
        <div class="page-header mb-4">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="page-title">
                        <i class="fas fa-chart-line mr-2"></i>
                        Market Overview
                    </h1>
                    <p class="page-subtitle">
                        Live Binance USDT pairs with real-time engulfing & spot pattern analysis
                    </p>
                </div>
                
            </div>
        </div>

        <!-- Summary Cards -->
        @php
            $totalCoins = $candles->count();
            $gainers = $candles->where('color_status', 'green');
            $losers = $candles->where('color_status', 'red');
            $biggestGainer = $candles->sortByDesc('price_change_percent_24h')->first();
            $biggestLoser = $candles->sortBy('price_change_percent_24h')->first();
        @endphp
        
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card">
                    <div class="stats-icon">
                        <i class="fas fa-coins"></i>
                    </div>
                    <div class="stats-content">
                        <div class="stats-number">{{ $totalCoins }}</div>
                        <div class="stats-label">Total Coins Tracked</div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card stats-card-success">
                    <div class="stats-icon">
                        <i class="fas fa-arrow-up"></i>
                    </div>
                    <div class="stats-content">
                        <div class="stats-number">{{ $gainers->count() }}</div>
                        <div class="stats-label">Gainers (24h)</div>
                        <div class="stats-change">+{{ number_format(($gainers->count() / $totalCoins) * 100, 1) }}%</div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card stats-card-danger">
                    <div class="stats-icon">
                        <i class="fas fa-arrow-down"></i>
                    </div>
                    <div class="stats-content">
                        <div class="stats-number">{{ $losers->count() }}</div>
                        <div class="stats-label">Losers (24h)</div>
                        <div class="stats-change">-{{ number_format(($losers->count() / $totalCoins) * 100, 1) }}%</div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card stats-card-gradient">
                    <div class="stats-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <div class="stats-content">
                        <div class="stats-label">Top Performer</div>
                        @if($biggestGainer)
                            <div class="stats-performer">
                                <span class="performer-symbol">{{ $biggestGainer->symbol }}</span>
                                <span class="performer-change text-success">
                                    +{{ number_format($biggestGainer->price_change_percent_24h, 2) }}%
                                </span>
                            </div>
                        @endif
                        @if($biggestLoser)
                            <div class="stats-performer">
                                <span class="performer-symbol">{{ $biggestLoser->symbol }}</span>
                                <span class="performer-change text-danger">
                                    {{ number_format($biggestLoser->price_change_percent_24h, 2) }}%
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="filters-section mb-4">
            <div class="card">
                <div class="card-body">
                    <form method="GET" class="row align-items-center">
                        <div class="col-md-3">
                            <label class="form-label">Filter by Symbol</label>
                            <select name="symbol" class="form-control">
                                <option value="">All Symbols</option>
                                @foreach($symbols as $symbol)
                                    <option value="{{ $symbol }}" {{ request('symbol') == $symbol ? 'selected' : '' }}>
                                        {{ $symbol }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Price Change</label>
                            <select name="change_filter" class="form-control">
                                <option value="">All Changes</option>
                                <option value="positive" {{ request('change_filter') == 'positive' ? 'selected' : '' }}>Positive Only</option>
                                <option value="negative" {{ request('change_filter') == 'negative' ? 'selected' : '' }}>Negative Only</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Sort By</label>
                            <select name="sort" class="form-control">
                                <option value="">Default</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price (Low to High)</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price (High to Low)</option>
                                <option value="change_asc" {{ request('sort') == 'change_asc' ? 'selected' : '' }}>Change (Low to High)</option>
                                <option value="change_desc" {{ request('sort') == 'change_desc' ? 'selected' : '' }}>Change (High to Low)</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex">
                                <button type="submit" class="btn btn-primary mr-2">
                                    <i class="fas fa-filter mr-1"></i>Apply
                                </button>
                                <a href="{{ url()->current() }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times mr-1"></i>Clear
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Market Data Section -->
        <div class="section-header mb-3">
            <h3 class="section-title">
                <i class="fas fa-chart-candlestick mr-2"></i>
                Live Market Data
            </h3>
            <div class="section-actions">
                <span class="text-muted">{{ $candles->count() }} coins displayed</span>
            </div>
        </div>

        <!-- Coin Cards -->
        <div class="row">
            @forelse($candles as $candle)
                <div class="col-12 col-md-6 col-lg-6 mb-4">
                    <div class="coin-card {{ $candle->color_status }}">
                        <div class="coin-card-header">
                            <div class="coin-info">
                                <div class="coin-symbol">{{ $candle->symbol }}</div>
                                <div class="coin-interval">{{ $candle->interval }}</div>
                            </div>
                            <div class="coin-change {{ $candle->color_status }}">
                                <i class="fas fa-{{ $candle->color_status == 'green' ? 'arrow-up' : 'arrow-down' }}"></i>
                                {{ $candle->price_change_percent_24h > 0 ? '+' : '' }}{{ number_format($candle->price_change_percent_24h, 2) }}%
                            </div>
                        </div>
                        
                        <div class="coin-card-body">
                            <div class="price-info">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="price-item">
                                            <span class="price-label">Open</span>
                                            <span class="price-value">${{ number_format($candle->open, 4) }}</span>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="price-item">
                                            <span class="price-label">Close</span>
                                            <span class="price-value">${{ number_format($candle->close, 4) }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-6">
                                        <div class="price-item">
                                            <span class="price-label">Volume</span>
                                            <span class="price-value">{{ number_format($candle->volume, 0) }}</span>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="price-item">
                                            <span class="price-label">Trades</span>
                                            <span class="price-value">{{ number_format($candle->number_of_trades, 0) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card-footer">
                                <small class="text-muted">
                                    <i class="fas fa-clock mr-1"></i>
                                    {{ $candle->open_time }}
                                </small>
                                <div class="card-actions">
                                    <button class="btn btn-sm btn-outline-primary" onclick="showChart('{{ $candle->symbol }}')">
                                        <i class="fas fa-chart-line"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary" onclick="toggleFavorite('{{ $candle->symbol }}')">
                                        <i class="fas fa-star"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h4>No Market Data Available</h4>
                        <p class="text-muted">No coins found matching your current filters.</p>
                        <a href="{{ url()->current() }}" class="btn btn-primary">
                            <i class="fas fa-refresh mr-1"></i>Refresh Page
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>

<style>
/* Dashboard Styles */
.dashboard-container {
    min-height: 100vh;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    padding: 2rem 0;
}

.page-header {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    border: 1px solid rgba(255,255,255,0.2);
}

.page-title {
    font-size: 2.2rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 0.5rem;
}

.page-subtitle {
    color: #718096;
    font-size: 1.1rem;
    margin-bottom: 0;
}

.page-actions .btn {
    border-radius: 10px;
    font-weight: 500;
    padding: 0.5rem 1.2rem;
}

/* Stats Cards */
.stats-card {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    border: 1px solid rgba(255,255,255,0.2);
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
}

.stats-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
}

.stats-card-success::before {
    background: linear-gradient(90deg, #00d4aa 0%, #00b894 100%);
}

.stats-card-danger::before {
    background: linear-gradient(90deg, #ff7675 0%, #d63031 100%);
}

.stats-card-gradient::before {
    background: linear-gradient(90deg, #fdcb6e 0%, #e17055 100%);
}

.stats-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    margin-bottom: 1rem;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.stats-card-success .stats-icon {
    background: linear-gradient(135deg, #00d4aa 0%, #00b894 100%);
    box-shadow: 0 4px 15px rgba(0, 212, 170, 0.3);
}

.stats-card-danger .stats-icon {
    background: linear-gradient(135deg, #ff7675 0%, #d63031 100%);
    box-shadow: 0 4px 15px rgba(255, 118, 117, 0.3);
}

.stats-card-gradient .stats-icon {
    background: linear-gradient(135deg, #fdcb6e 0%, #e17055 100%);
    box-shadow: 0 4px 15px rgba(253, 203, 110, 0.3);
}

.stats-number {
    font-size: 2.5rem;
    font-weight: 700;
    color: #2d3748;
    line-height: 1;
    margin-bottom: 0.5rem;
}

.stats-label {
    color: #718096;
    font-size: 0.9rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stats-change {
    font-size: 0.8rem;
    font-weight: 600;
    margin-top: 0.25rem;
}

.performer-symbol {
    font-weight: 600;
    color: #2d3748;
    display: block;
    font-size: 1.1rem;
}

.performer-change {
    font-weight: 700;
    font-size: 1rem;
}

/* Filters Section */
.filters-section .card {
    border-radius: 15px;
    border: 1px solid rgba(255,255,255,0.2);
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
}

.form-label {
    font-weight: 600;
    color: #4a5568;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.form-control {
    border-radius: 10px;
    border: 1px solid #e2e8f0;
    padding: 0.75rem 1rem;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

/* Section Header */
.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: white;
    padding: 1.5rem;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    border: 1px solid rgba(255,255,255,0.2);
}

.section-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 0;
}

/* Coin Cards */
.coin-card {
    background: white;
    border-radius: 15px;
    border: 1px solid rgba(255,255,255,0.2);
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    overflow: hidden;
    position: relative;
}

.coin-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
}

.coin-card.green::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #00d4aa 0%, #00b894 100%);
}

.coin-card.red::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #ff7675 0%, #d63031 100%);
}

.coin-card-header {
    padding: 1.5rem 1.5rem 1rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.coin-symbol {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2d3748;
}

.coin-interval {
    font-size: 0.8rem;
    color: #718096;
    background: #f7fafc;
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    font-weight: 500;
}

.coin-change {
    font-size: 1.3rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.coin-change.green {
    color: #00b894;
}

.coin-change.red {
    color: #d63031;
}

.coin-card-body {
    padding: 0 1.5rem 1.5rem;
}

.price-info {
    margin-bottom: 1rem;
}

.price-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f7fafc;
}

.price-item:last-child {
    border-bottom: none;
}

.price-label {
    color: #718096;
    font-size: 0.9rem;
    font-weight: 500;
}

.price-value {
    color: #2d3748;
    font-weight: 600;
    font-size: 0.95rem;
}

.card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 1rem;
    border-top: 1px solid #f7fafc;
}

.card-actions {
    display: flex;
    gap: 0.5rem;
}

.card-actions .btn {
    width: 35px;
    height: 35px;
    border-radius: 8px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
}

.empty-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 2rem;
    margin: 0 auto 2rem;
}

.empty-state h4 {
    color: #2d3748;
    margin-bottom: 1rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .page-header {
        padding: 1.5rem;
    }
    
    .page-title {
        font-size: 1.8rem;
    }
    
    .page-subtitle {
        font-size: 1rem;
    }
    
    .page-actions {
        margin-top: 1rem;
    }
    
    .stats-number {
        font-size: 2rem;
    }
    
    .section-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .coin-card-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .coin-change {
        font-size: 1.1rem;
    }
    
    .filters-section .form-control {
        margin-bottom: 1rem;
    }
}

@media (max-width: 576px) {
    .dashboard-container {
        padding: 1rem 0;
    }
    
    .page-header {
        padding: 1rem;
    }
    
    .stats-card {
        padding: 1rem;
    }
    
    .coin-card-header,
    .coin-card-body {
        padding: 1rem;
    }
}
</style>
@endsection