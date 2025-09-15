<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Neera') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Figtree', sans-serif;
            background: linear-gradient(135deg, #F5F5DC 0%, #DEB887 30%, #D2B48C 60%, #BC9A6A 100%);
            min-height: 100vh;
            color: #8B4513;
            overflow: hidden;
            position: relative;
        }

        /* Coffee bubbles background */
        .bg-effects {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
        }

        .coffee-bubble {
            position: absolute;
            width: 8px;
            height: 8px;
            background: rgba(139, 69, 19, 0.3);
            border-radius: 50%;
            animation: float 8s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); opacity: 0.3; }
            50% { transform: translateY(-30px) rotate(180deg); opacity: 0.8; }
        }

        /* Header */
        .header {
            position: absolute;
            top: 30px;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
            z-index: 10;
        }

        .main-title {
            font-size: 3rem;
            font-weight: 700;
            letter-spacing: 8px;
            margin-bottom: 8px;
            color: #8B4513;
            text-shadow: 0 4px 15px rgba(139, 69, 19, 0.3);
        }

        .subtitle {
            font-size: 1rem;
            opacity: 0.8;
            letter-spacing: 2px;
            color: #A0522D;
        }

        /* Auth buttons */
        .auth-buttons {
            position: absolute;
            top: 30px;
            right: 40px;
            display: flex;
            gap: 15px;
            z-index: 10;
        }

        .auth-btn {
            padding: 12px 25px;
            border: 2px solid rgba(139, 69, 19, 0.3);
            background: rgba(245, 245, 220, 0.8);
            color: #8B4513;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .auth-btn:hover {
            background: rgba(139, 69, 19, 0.2);
            border-color: #8B4513;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(139, 69, 19, 0.2);
        }

        /* Main container - PREVENT OVERFLOW */
        .container {
            position: relative;
            z-index: 5;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 40px;
            max-width: 100vw;
            overflow: hidden;
        }

        /* Profile card - COMPACT SIZE */
        .profile-card {
            background: linear-gradient(135deg, rgba(245, 245, 220, 0.95), rgba(222, 184, 135, 0.3));
            backdrop-filter: blur(25px);
            border: 2px solid rgba(139, 69, 19, 0.15);
            border-radius: 25px;
            padding: 30px;
            width: 350px;
            box-shadow: 0 20px 40px rgba(139, 69, 19, 0.2);
            position: relative;
            overflow: hidden;
        }

        .profile-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(139, 69, 19, 0.05) 0%, transparent 70%);
            animation: profileGlow 6s ease-in-out infinite;
        }

        @keyframes profileGlow {
            0%, 100% { opacity: 0.3; transform: rotate(0deg); }
            50% { opacity: 0.8; transform: rotate(180deg); }
        }

        .profile-header {
            text-align: center;
            margin-bottom: 25px;
            position: relative;
            z-index: 2;
        }

        .profile-name {
            font-size: 1.9rem;
            font-weight: 700;
            margin-bottom: 8px;
            background: linear-gradient(135deg, #8B4513, #D2B48C, #BC9A6A);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 2px 10px rgba(139, 69, 19, 0.1);
        }

        .profile-class {
            font-size: 1rem;
            opacity: 0.85;
            margin-bottom: 25px;
            color: #A0522D;
            font-weight: 500;
        }

        .bio-section {
            text-align: left;
            position: relative;
            z-index: 2;
        }

        .bio-title {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 12px;
            color: #8B4513;
            text-shadow: 0 2px 5px rgba(139, 69, 19, 0.1);
        }

        .bio-text {
            font-size: 0.9rem;
            line-height: 1.6;
            opacity: 0.9;
            color: #654321;
            font-weight: 400;
        }

        .see-more-btn {
            background: linear-gradient(135deg, #8B4513, #D2B48C, #BC9A6A);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 20px;
            margin-top: 20px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 8px 20px rgba(139, 69, 19, 0.3);
            border: 2px solid rgba(245, 245, 220, 0.3);
        }

        .see-more-btn:hover {
            transform: scale(1.05) translateY(-2px);
            box-shadow: 0 12px 25px rgba(139, 69, 19, 0.4);
        }

        /* Center - Profile picture - OPTIMIZED SIZE */
        .center-profile {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .profile-picture {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background-image: url('https://i.pinimg.com/1200x/11/f8/92/11f892139cdbaba3ecc84912f0cb5ac6.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
            font-weight: 700;
            color: white;
            box-shadow: 0 25px 50px rgba(139, 69, 19, 0.4);
            margin-bottom: 20px;
            animation: profilePulse 5s ease-in-out infinite;
            border: 6px solid rgba(245, 245, 220, 0.9);
            position: relative;
            overflow: hidden;
        }

        .profile-picture::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            animation: shimmer 3s linear infinite;
        }

        @keyframes profilePulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        @keyframes shimmer {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .center-name {
            font-size: 1.7rem;
            font-weight: 700;
            margin-bottom: 8px;
            color: #8B4513;
            text-shadow: 0 4px 15px rgba(139, 69, 19, 0.2);
        }

        /* Right side elements - SIMPLIFIED */
        .right-elements {
            display: flex;
            flex-direction: column;
            gap: 20px;
            width: 280px;
            justify-content: center;
        }

        /* Coding element - COMPACT SIZE */
        .coding-element {
            background: linear-gradient(135deg, rgba(139, 69, 19, 0.1), rgba(210, 180, 140, 0.1));
            backdrop-filter: blur(20px);
            border: 2px solid rgba(139, 69, 19, 0.15);
            border-radius: 20px;
            padding: 20px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(139, 69, 19, 0.1);
        }

        .coding-element::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(139, 69, 19, 0.05) 0%, transparent 70%);
            animation: codeGlow 4s ease-in-out infinite;
        }

        @keyframes codeGlow {
            0%, 100% { opacity: 0.3; transform: scale(1); }
            50% { opacity: 0.8; transform: scale(1.1); }
        }

        .coding-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
            position: relative;
            z-index: 2;
        }

        .coding-icon {
            width: 35px;
            height: 35px;
            background: linear-gradient(135deg, #8B4513, #D2B48C);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: white;
            font-size: 1rem;
            box-shadow: 0 5px 15px rgba(139, 69, 19, 0.3);
            border: 2px solid rgba(245, 245, 220, 0.8);
        }

        .coding-text {
            font-weight: 600;
            color: #8B4513;
            font-size: 1rem;
            text-shadow: 0 2px 5px rgba(139, 69, 19, 0.1);
        }

        .code-lines {
            font-family: 'Courier New', monospace;
            font-size: 0.75rem;
            line-height: 1.4;
            color: #654321;
            background: rgba(245, 245, 220, 0.3);
            padding: 15px;
            border-radius: 12px;
            border-left: 3px solid #8B4513;
            position: relative;
            z-index: 2;
            box-shadow: inset 0 2px 8px rgba(139, 69, 19, 0.1);
        }

        .code-lines .keyword {
            color: #8B4513;
            font-weight: 700;
        }

        .code-lines .string {
            color: #BC9A6A;
            font-weight: 500;
        }

        .code-lines .function {
            color: #A0522D;
            font-weight: 600;
        }

        .code-lines .comment {
            color: #D2B48C;
            font-style: italic;
        }

        /* Hobby chart - ENHANCED BIGGER */
        .hobby-chart {
            background: linear-gradient(135deg, rgba(245, 245, 220, 0.95), rgba(222, 184, 135, 0.3));
            backdrop-filter: blur(20px);
            border: 2px solid rgba(139, 69, 19, 0.15);
            border-radius: 25px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 15px 35px rgba(139, 69, 19, 0.15);
            position: relative;
            overflow: hidden;
        }

        .hobby-chart::before {
            content: '';
            position: absolute;
            top: -10px;
            left: -10px;
            right: -10px;
            bottom: -10px;
            background: linear-gradient(45deg, rgba(139, 69, 19, 0.1), transparent, rgba(210, 180, 140, 0.1));
            border-radius: 25px;
            z-index: -1;
            animation: chartShimmer 3s ease-in-out infinite;
        }

        @keyframes chartShimmer {
            0%, 100% { opacity: 0.5; }
            50% { opacity: 1; }
        }

        .chart-title {
            font-weight: 700;
            margin-bottom: 25px;
            color: #8B4513;
            font-size: 1.4rem;
            text-shadow: 0 2px 5px rgba(139, 69, 19, 0.1);
        }

        .pie-chart {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            background: conic-gradient(
                #8B4513 0deg 144deg,
                #D2B48C 144deg 252deg,
                #DEB887 252deg 270deg,
                #BC9A6A 270deg 324deg,
                #A0522D 324deg 360deg
            );
            margin: 0 auto 25px;
            box-shadow: 0 20px 40px rgba(139, 69, 19, 0.3);
            border: 5px solid rgba(245, 245, 220, 0.9);
            transition: transform 0.3s ease;
            position: relative;
        }

        .pie-chart::before {
            content: '🎯';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 50px;
            height: 50px;
            background: rgba(245, 245, 220, 0.95);
            border-radius: 50%;
            border: 3px solid #8B4513;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .pie-chart:hover {
            transform: scale(1.08) rotate(5deg);
        }

        .chart-legend {
            font-size: 0.85rem;
            text-align: left;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 8px;
            color: #654321;
            font-weight: 500;
            transition: transform 0.2s ease;
        }

        .legend-item:hover {
            transform: translateX(5px);
        }

        .legend-color {
            width: 16px;
            height: 16px;
            border-radius: 4px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        /* Social media footer - Modern Clean Style */
        .social-footer-modern {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(16px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            z-index: 100;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .social-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: white;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            white-space: nowrap;
        }

        .social-btn-modern {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            text-decoration: none;
            color: white;
            font-size: 1rem;
            font-weight: 600;
            transition: transform 0.2s ease;
            border: none;
            cursor: pointer;
        }

        .social-btn-modern:hover {
            transform: scale(1.1);
        }

        .youtube-modern {
            background: linear-gradient(135deg, #ec4899, #a855f7);
        }

        .tiktok-modern {
            background: linear-gradient(135deg, #22d3ee, #3b82f6);
        }

        .instagram-modern {
            background: linear-gradient(135deg, #fb923c, #ef4444);
        }

        .whatsapp-modern {
            background: linear-gradient(135deg, #4ade80, #14b8a6);
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .container {
                flex-direction: column;
                gap: 40px;
                padding: 20px;
                justify-content: center;
            }

            .profile-card, .right-elements {
                width: 100%;
                max-width: 420px;
            }

            .main-title {
                font-size: 2.2rem;
            }

            .auth-buttons {
                top: 20px;
                right: 20px;
            }

            .header {
                top: 20px;
            }

            .social-footer-modern {
                bottom: 15px;
                padding: 14px;
                gap: 14px;
            }
        }

        @media (max-width: 768px) {
            .main-title {
                font-size: 1.8rem;
                letter-spacing: 4px;
            }

            .profile-picture {
                width: 180px;
                height: 180px;
                font-size: 3.5rem;
            }

            .auth-buttons {
                flex-direction: column;
                gap: 10px;
            }

            .social-footer-modern {
                bottom: 10px;
                padding: 12px;
                gap: 12px;
                border-radius: 12px;
            }

            .social-title {
                font-size: 1rem;
            }

            .social-btn-modern {
                width: 36px;
                height: 36px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <!-- Coffee bubbles background -->
    <div class="bg-effects">
        <div class="coffee-bubble" style="top: 15%; left: 8%; animation-delay: 0s;"></div>
        <div class="coffee-bubble" style="top: 45%; left: 15%; animation-delay: 1.5s;"></div>
        <div class="coffee-bubble" style="top: 25%; left: 75%; animation-delay: 3s;"></div>
        <div class="coffee-bubble" style="top: 70%; left: 80%; animation-delay: 4.5s;"></div>
        <div class="coffee-bubble" style="top: 60%; left: 25%; animation-delay: 6s;"></div>
        <div class="coffee-bubble" style="top: 35%; left: 90%; animation-delay: 2s;"></div>
        <div class="coffee-bubble" style="top: 80%; left: 60%; animation-delay: 5s;"></div>
        <div class="coffee-bubble" style="top: 20%; left: 40%; animation-delay: 7s;"></div>
    </div>

    <!-- Header -->
    <div class="header">
        <h1 class="main-title">NEERA</h1>
        <p class="subtitle">Your Cozy Forum Experience</p>
    </div>

    <!-- Auth buttons - SISTEM LOGIN/REGISTER LARAVEL DIPERTAHANKAN -->
    @if (Route::has('login'))
        <div class="auth-buttons">
            @auth
                <a href="{{ url('/dashboard') }}" class="auth-btn">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="auth-btn">Login</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="auth-btn">Register</a>
                @endif
            @endauth
        </div>
    @endif

    <!-- Main container -->
    <div class="container">
        <!-- Left side - Profile card -->
        <div class="profile-card">
            <div class="profile-header">
                <h2 class="profile-name">Chelo</h2>
                <p class="profile-class">From XII PPLG A, SMKN 7</p>
            </div>
            
            <div class="bio-section">
                <h3 class="bio-title">Biografi</h3>
                <p class="bio-text">
                    Halo! Saya Chelo Arung Samudro, seorang pelajar dari SMKN 7. Saya sangat menyukai musik, gaming, dan coding. Di waktu luang, saya suka mendesain grafis dan menjelajahi dunia digital. Saya percaya bahwa teknologi dapat mengubah dunia menjadi tempat yang lebih baik. Mari terhubung dan berbagi ide!
                </p>
                <button class="see-more-btn" onclick="alert('Welcome to Neera! ☕')">See More</button>
            </div>
        </div>

        <!-- Center - Profile picture -->
        <div class="center-profile">
            <div class="profile-picture">
            </div>
            <h2 class="center-name">Chelo Arung Samudro</h2>
        </div>

        <!-- Right side elements -->
        <div class="right-elements">
            <!-- Hobby chart -->
            <div class="hobby-chart">
                <h3 class="chart-title">🎯 Hobi Favorit</h3>
                <div class="pie-chart"></div>
                <div class="chart-legend">
                    <div class="legend-item">
                        <div class="legend-color" style="background: #8B4513;"></div>
                        <span>🎵 Musik 40%</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background: #D2B48C;"></div>
                        <span>🎮 Game 30%</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background: #DEB887;"></div>
                        <span>💻 Koding 15%</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background: #BC9A6A;"></div>
                        <span>😴 Tidur 10%</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color" style="background: #A0522D;"></div>
                        <span>🎨 Design 5%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Social Media Footer - Modern Style -->
    <div class="social-footer-modern">
        <span class="social-title">Connect With Me ☕</span>
        <a href="https://www.youtube.com/@gelutoniexdi" class="social-btn-modern youtube-modern" title="YouTube">
            ▶
        </a>
        <a href="https://www.tiktok.com/@grreeeddd" class="social-btn-modern tiktok-modern" title="TikTok">
            🎵
        </a>
        <a href="https://www.instagram.com/grreeedd_/" class="social-btn-modern instagram-modern" title="Instagram">
            📸
        </a>
        <a href="https://wa.me/6283875095310" class="social-btn-modern whatsapp-modern" title="WhatsApp">
            💬
        </a>
    </div>

    <script>
        // Coffee bubble interactive effects
        document.addEventListener('mousemove', function(e) {
            const bubbles = document.querySelectorAll('.coffee-bubble');
            bubbles.forEach((bubble, index) => {
                const speed = (index + 1) * 0.005;
                const x = (e.clientX * speed) % window.innerWidth;
                const y = (e.clientY * speed) % window.innerHeight;
                bubble.style.left = x + 'px';
                bubble.style.top = y + 'px';
            });
        });

        // Animate coding text with coffee theme
        const codeLines = document.querySelector('.code-lines');
        let isBrewingCode = false;

        setInterval(() => {
            if (!isBrewingCode) {
                isBrewingCode = true;
                codeLines.style.opacity = '0.6';
                codeLines.style.transform = 'scale(0.98)';
                setTimeout(() => {
                    codeLines.style.opacity = '1';
                    codeLines.style.transform = 'scale(1)';
                    isBrewingCode = false;
                }, 1200);
            }
        }, 4000);

        // Add smooth transition to code lines
        codeLines.style.transition = 'all 0.6s ease';
    </script>
</body>
</html>