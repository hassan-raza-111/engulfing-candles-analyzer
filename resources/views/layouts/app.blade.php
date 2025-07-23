<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CryptoVision Pro - Advanced Trading Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
</head>
<body>
    <!-- Professional Header -->
    <header class="main-header">
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="/">
                    <div class="brand-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="brand-text ml-2">
                        <div class="brand-name">CryptoVision Pro</div>
                        <div class="brand-tagline">Advanced Trading Dashboard</div>
                    </div>
                </a>
                <!-- Removed navbar-toggler button -->
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <span class="nav-text">
                                <i class="fas fa-chart-line mr-1"></i>
                                Real-time Market Analytics
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        
        <!-- Market Status Bar -->
        <div class="market-status-bar">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center text-white">
                            <div class="status-indicator online mr-2"></div>
                            <span class="mr-4">Market Status: <strong>ONLINE</strong></span>
                            <span class="mr-4">Last Update: <strong id="lastUpdate">Just Now</strong></span>
                            <span>API Health: <strong class="text-success">Good</strong></span>
                        </div>
                    </div>
                    <div class="col-md-4 text-right">
                        <div class="market-time text-white">
                            <i class="fas fa-clock mr-1"></i>
                            <span id="currentTime">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="main-content">
        @yield('content')
    </main>

    <!-- Professional Footer -->
    <footer class="professional-footer">
        <div class="footer-bottom">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="mb-0">&copy; {{ date('Y') }} CryptoVision Pro. All rights reserved.</p>
                    </div>
                    <div class="col-md-6 text-md-right text-center">
                        <span class="footer-dev-by">Developed by <b>Zevox Solutions</b></span>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Update current time
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-US', { 
                hour12: false, 
                hour: '2-digit', 
                minute: '2-digit', 
                second: '2-digit' 
            });
            document.getElementById('currentTime').textContent = timeString;
        }
        
        // Update last update time
        function updateLastUpdate() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-US', { 
                hour12: true, 
                hour: '2-digit', 
                minute: '2-digit'
            });
            document.getElementById('lastUpdate').textContent = timeString;
        }
        
        // Initialize time updates
        updateTime();
        updateLastUpdate();
        setInterval(updateTime, 1000);
        setInterval(updateLastUpdate, 30000); // Update every 30 seconds
    </script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        /* Header Styles */
        .main-header {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .navbar {
            padding: 1rem 0;
        }
        
        .brand-icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        
        .brand-name {
            font-size: 1.4rem;
            font-weight: 700;
            color: white;
            line-height: 1.2;
        }
        
        .brand-tagline {
            font-size: 0.75rem;
            color: rgba(255,255,255,0.8);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .navbar-nav .nav-text {
            color: rgba(255,255,255,0.9) !important;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            border-radius: 8px;
            background: rgba(255,255,255,0.1);
            font-size: 0.9rem;
            display: flex;
            align-items: center;
        }
        
        .footer-text {
            color: #999;
            font-size: 0.9rem;
            cursor: default;
        }
        
        .footer-links span.footer-text:hover {
            color: #667eea;
        }
        
        /* Market Status Bar */
        .market-status-bar {
            background: rgba(0,0,0,0.2);
            padding: 0.75rem 0;
            border-top: 1px solid rgba(255,255,255,0.1);
        }
        
        .status-indicator {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
        }
        
        .status-indicator.online {
            background: #00ff88;
            box-shadow: 0 0 10px rgba(0,255,136,0.5);
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { box-shadow: 0 0 10px rgba(0,255,136,0.5); }
            50% { box-shadow: 0 0 20px rgba(0,255,136,0.8); }
            100% { box-shadow: 0 0 10px rgba(0,255,136,0.5); }
        }
        
        /* Main Content */
        .main-content {
            flex: 1;
            padding: 2rem 0;
        }
        
        /* Footer Styles */
        .professional-footer {
            background: #1a1a1a;
            color: #ffffff;
            margin-top: auto;
        }
        
        .footer-main {
            padding: 3rem 0 2rem;
        }
        
        .footer-logo {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
        }
        
        .footer-title {
            color: #ffffff;
            font-weight: 600;
            margin-bottom: 1rem;
            font-size: 1rem;
        }
        
        .footer-links {
            list-style: none;
            padding: 0;
        }
        
        .footer-links li {
            margin-bottom: 0.5rem;
        }
        
        .footer-links a {
            color: #999;
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s ease;
        }
        
        .footer-links a:hover {
            color: #667eea;
            text-decoration: none;
        }
        
        .social-links {
            margin-top: 1rem;
        }
        
        .social-links span {
            display: inline-block;
            width: 40px;
            height: 40px;
            background: #333;
            border-radius: 50%;
            text-align: center;
            line-height: 40px;
            color: #999;
            margin-right: 0.5rem;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .social-links span:hover {
            background: #667eea;
            color: white;
            transform: translateY(-2px);
        }
        
        .contact-info {
            font-size: 0.9rem;
        }
        
        .contact-info div {
            color: #999;
        }
        
        .footer-bottom {
            background: #111;
            padding: 1.5rem 0;
            border-top: 1px solid #333;
        }
        
        .footer-bottom p {
            color: #999;
            font-size: 0.9rem;
        }
        
        .footer-badges .badge {
            background: transparent;
            border: 1px solid #333;
            color: #999;
            font-size: 0.8rem;
            padding: 0.4rem 0.8rem;
        }
        
        .badge-outline-primary {
            border-color: #667eea !important;
            color: #667eea !important;
        }
        
        .badge-outline-success {
            border-color: #28a745 !important;
            color: #28a745 !important;
        }
        
        .badge-outline-info {
            border-color: #17a2b8 !important;
            color: #17a2b8 !important;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .brand-name {
                font-size: 1.2rem;
            }
            
            .brand-tagline {
                font-size: 0.7rem;
            }
            
            .market-status-bar {
                text-align: center;
            }
            
            .market-status-bar .col-md-4 {
                text-align: center !important;
                margin-top: 0.5rem;
            }
            
            .footer-badges {
                text-align: center !important;
                margin-top: 1rem;
            }
        }
    </style>
    <style>
.footer-dev-by {
    color: #aaa;
    font-size: 1.08rem;
    letter-spacing: 0.5px;
}
.footer-dev-by b {
    color: #7c3aed;
    font-weight: 700;
}
</style>
</body>
</html>