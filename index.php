<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Restaurant System</title>
    <link rel="stylesheet" href="/restaurant_system/assets/styles.css">
    <meta name="viewport" content="width=device-width,initial-scale=1">
</head>
<body>
<div class="site-container">
        <div class="dither-hero">
                <!-- Animated noise + threshold dither effect using SVG filters -->
                        <svg class="dither-svg" preserveAspectRatio="xMidYMid slice" viewBox="0 0 1200 600" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <defs>
                                <filter id="turb" x="-20%" y="-20%" width="140%" height="140%">
                                    <feTurbulence baseFrequency="0.6" numOctaves="2" seed="7" stitchTiles="stitch" type="fractalNoise">
                                        <animate attributeName="baseFrequency" dur="12s" values="0.4;0.9;0.4" repeatCount="indefinite"/>
                                    </feTurbulence>
                                    <feColorMatrix type="saturate" values="0" />
                                    <feComponentTransfer>
                                        <feFuncR type="table" tableValues="0 0 1 1"/>
                                        <feFuncG type="table" tableValues="0 0 1 1"/>
                                        <feFuncB type="table" tableValues="0 0 1 1"/>
                                    </feComponentTransfer>
                                    <feGaussianBlur stdDeviation="0.4" />
                                </filter>
                            </defs>

                            <rect width="100%" height="100%" fill="#0b0b0b" />
                            <rect width="100%" height="100%" filter="url(#turb)" opacity="0.95" />
                        </svg>

                <div class="hero-overlay">
                    <div class="hero-content">
                        <h1>Welcome to Our Restaurant!</h1>
                        <p class="lead">Good food. Good mood. We make every meal fresh and fast for you.</p>
                        <div class="cta">
                            <a class="btn" href="customer_register.php">Get Started</a>
                        </div>
                    </div>
                </div>
        </div>


    <h3>For Customers</h3>
        <a href="customer_login.php">Login</a>

                <h3>For Admin</h3>
                <a href="admin_login.php">Login</a> 

    </div>
</div>
<script src="/restaurant_system/assets/app.js"></script>
</body>
</html>
