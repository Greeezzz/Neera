<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Neera') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

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
                position: relative;
                overflow-y: auto;
                overflow-x: hidden;
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
                width: 6px;
                height: 6px;
                background: rgba(139, 69, 19, 0.2);
                border-radius: 50%;
                animation: float 10s ease-in-out infinite;
            }

            @keyframes float {
                0%, 100% { transform: translateY(0px) rotate(0deg); opacity: 0.2; }
                50% { transform: translateY(-40px) rotate(180deg); opacity: 0.6; }
            }

            .auth-container {
                min-height: 100vh;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                padding: 20px;
                position: relative;
                z-index: 10;
            }

            .logo-section {
                margin-bottom: 20px;
                text-align: center;
            }

            .neera-logo {
                font-size: 2rem;
                font-weight: 700;
                letter-spacing: 4px;
                color: #8B4513;
                text-shadow: 0 4px 15px rgba(139, 69, 19, 0.3);
                margin-bottom: 6px;
                text-decoration: none;
                display: block;
            }

            .logo-subtitle {
                font-size: 0.8rem;
                opacity: 0.8;
                letter-spacing: 1px;
                color: #A0522D;
            }

            .auth-card {
                width: 100%;
                max-width: 380px;
                background: linear-gradient(135deg, rgba(245, 245, 220, 0.95), rgba(222, 184, 135, 0.3));
                backdrop-filter: blur(25px);
                border: 2px solid rgba(139, 69, 19, 0.15);
                border-radius: 20px;
                padding: 30px;
                box-shadow: 0 20px 40px rgba(139, 69, 19, 0.2);
                position: relative;
                overflow: hidden;
                margin: 10px 0;
            }

            .auth-card::before {
                content: '';
                position: absolute;
                top: -50%;
                left: -50%;
                width: 200%;
                height: 200%;
                background: radial-gradient(circle, rgba(139, 69, 19, 0.05) 0%, transparent 70%);
                animation: cardGlow 8s ease-in-out infinite;
            }

            @keyframes cardGlow {
                0%, 100% { opacity: 0.3; transform: rotate(0deg); }
                50% { opacity: 0.8; transform: rotate(180deg); }
            }

            .form-content {
                position: relative;
                z-index: 2;
            }

            /* Custom form styling */
            .form-group {
                margin-bottom: 16px;
            }

            .form-label {
                display: block;
                font-size: 0.85rem;
                font-weight: 600;
                color: #8B4513;
                margin-bottom: 6px;
                text-shadow: 0 1px 2px rgba(139, 69, 19, 0.1);
            }

            .form-input {
                width: 100%;
                padding: 10px 14px;
                border: 2px solid rgba(139, 69, 19, 0.2);
                border-radius: 10px;
                background: rgba(245, 245, 220, 0.8);
                color: #654321;
                font-size: 0.9rem;
                transition: all 0.3s ease;
                backdrop-filter: blur(10px);
            }

            .form-input:focus {
                outline: none;
                border-color: #8B4513;
                background: rgba(245, 245, 220, 0.95);
                box-shadow: 0 0 0 3px rgba(139, 69, 19, 0.1);
            }

            .form-input::placeholder {
                color: rgba(139, 69, 19, 0.5);
            }

            .error-message {
                margin-top: 4px;
                font-size: 0.8rem;
                color: #D2691E;
                font-weight: 500;
            }

            .checkbox-container {
                display: flex;
                align-items: center;
                gap: 8px;
                margin: 16px 0;
            }

            .checkbox-input {
                width: 16px;
                height: 16px;
                border: 2px solid rgba(139, 69, 19, 0.3);
                border-radius: 4px;
                background: rgba(245, 245, 220, 0.8);
                accent-color: #8B4513;
            }

            .checkbox-label {
                font-size: 0.85rem;
                color: #654321;
                font-weight: 500;
            }

            .form-actions {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-top: 20px;
                flex-wrap: wrap;
                gap: 12px;
            }

            .forgot-password {
                color: #A0522D;
                text-decoration: none;
                font-size: 0.8rem;
                font-weight: 500;
                transition: color 0.3s ease;
            }

            .forgot-password:hover {
                color: #8B4513;
                text-decoration: underline;
            }

            .login-btn {
                background: linear-gradient(135deg, #8B4513, #D2B48C, #BC9A6A);
                color: white;
                border: none;
                padding: 10px 24px;
                border-radius: 16px;
                cursor: pointer;
                font-weight: 600;
                font-size: 0.9rem;
                transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                box-shadow: 0 6px 16px rgba(139, 69, 19, 0.3);
                border: 2px solid rgba(245, 245, 220, 0.3);
                text-decoration: none;
                display: inline-block;
                text-align: center;
            }

            .login-btn:hover {
                transform: scale(1.05) translateY(-2px);
                box-shadow: 0 8px 20px rgba(139, 69, 19, 0.4);
            }

            .register-link {
                text-align: center;
                margin-top: 20px;
                padding-top: 16px;
                border-top: 1px solid rgba(139, 69, 19, 0.2);
            }

            .register-link a {
                color: #8B4513;
                text-decoration: none;
                font-weight: 600;
                transition: color 0.3s ease;
            }

            .register-link a:hover {
                color: #A0522D;
                text-decoration: underline;
            }

            /* Responsive */
            @media (max-width: 640px) {
                .auth-container {
                    padding: 16px;
                    min-height: 100vh;
                    justify-content: flex-start;
                    padding-top: 40px;
                }

                .auth-card {
                    padding: 24px 20px;
                    margin: 8px 0;
                    max-width: 100%;
                }

                .neera-logo {
                    font-size: 1.8rem;
                    letter-spacing: 3px;
                }

                .logo-section {
                    margin-bottom: 16px;
                }

                .form-actions {
                    flex-direction: column;
                    align-items: stretch;
                    gap: 12px;
                }

                .login-btn {
                    width: 100%;
                }

                .form-group {
                    margin-bottom: 14px;
                }
            }

            @media (max-height: 700px) {
                .auth-container {
                    justify-content: flex-start;
                    padding-top: 20px;
                    padding-bottom: 20px;
                }

                .logo-section {
                    margin-bottom: 12px;
                }

                .auth-card {
                    margin: 8px 0;
                }
            }
        </style>
    </head>
    <body>
        <!-- Coffee bubbles background -->
        <div class="bg-effects">
            <div class="coffee-bubble" style="top: 10%; left: 15%; animation-delay: 0s;"></div>
            <div class="coffee-bubble" style="top: 30%; left: 8%; animation-delay: 2s;"></div>
            <div class="coffee-bubble" style="top: 60%; left: 85%; animation-delay: 4s;"></div>
            <div class="coffee-bubble" style="top: 80%; left: 20%; animation-delay: 6s;"></div>
            <div class="coffee-bubble" style="top: 20%; left: 75%; animation-delay: 1s;"></div>
            <div class="coffee-bubble" style="top: 50%; left: 60%; animation-delay: 3s;"></div>
            <div class="coffee-bubble" style="top: 40%; left: 40%; animation-delay: 5s;"></div>
        </div>

        <div class="auth-container">
            <div class="logo-section">
                <a href="/" class="neera-logo">NEERA</a>
                <p class="logo-subtitle">Your Cozy Forum Experience</p>
            </div>

            <div class="auth-card">
                <div class="form-content">
                    {{ $slot }}
                </div>
            </div>
        </div>

        <script>
            // Coffee bubble interactive effects
            document.addEventListener('mousemove', function(e) {
                const bubbles = document.querySelectorAll('.coffee-bubble');
                bubbles.forEach((bubble, index) => {
                    const speed = (index + 1) * 0.003;
                    const x = (e.clientX * speed) % window.innerWidth;
                    const y = (e.clientY * speed) % window.innerHeight;
                    bubble.style.left = x + 'px';
                    bubble.style.top = y + 'px';
                });
            });
        </script>
    </body>
</html>
