<x-guest-layout>
    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-4 p-3 rounded-lg bg-green-100 border border-green-200 text-green-700 text-sm">
            {{ session('status') }}
        </div>
    @endif

    <div class="text-center mb-5">
        <h2 class="text-xl font-bold text-coffee-dark mb-1" style="color: #8B4513; text-shadow: 0 2px 4px rgba(139, 69, 19, 0.1);">
            Welcome Back ☕
        </h2>
        <p class="text-sm opacity-80" style="color: #A0522D;">
            Sign in to your Neera account
        </p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="form-group">
            <label for="email" class="form-label">{{ __('Email') }}</label>
            <input id="email" 
                   class="form-input" 
                   type="email" 
                   name="email" 
                   value="{{ old('email') }}" 
                   required 
                   autofocus 
                   autocomplete="username"
                   placeholder="Enter your email" />
            @error('email')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="form-group">
            <label for="password" class="form-label">{{ __('Password') }}</label>
            <input id="password" 
                   class="form-input"
                   type="password"
                   name="password"
                   required 
                   autocomplete="current-password"
                   placeholder="Enter your password" />
            @error('password')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="checkbox-container">
            <input id="remember_me" 
                   type="checkbox" 
                   class="checkbox-input" 
                   name="remember">
            <label for="remember_me" class="checkbox-label">
                {{ __('Remember me') }}
            </label>
        </div>

        <div class="form-actions">
            @if (Route::has('password.request'))
                <a class="forgot-password" href="{{ route('password.request') }}">
                    {{ __('Forgot password?') }}
                </a>
            @endif

            <button type="submit" class="login-btn">
                {{ __('Log in') }}
            </button>
        </div>
    </form>

    @if (Route::has('register'))
        <div class="register-link">
            <p style="color: #654321; font-size: 0.9rem;">
                Don't have an account? 
                <a href="{{ route('register') }}">Create one here</a>
            </p>
        </div>
    @endif
</x-guest-layout>
