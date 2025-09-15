<x-guest-layout>
    <div class="text-center mb-5">
        <h2 class="text-xl font-bold text-coffee-dark mb-1" style="color: #8B4513; text-shadow: 0 2px 4px rgba(139, 69, 19, 0.1);">
            Join Neera ☕
        </h2>
        <p class="text-sm opacity-80" style="color: #A0522D;">
            Create your cozy forum account
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="form-group">
            <label for="name" class="form-label">{{ __('Name') }}</label>
            <input id="name" 
                   class="form-input" 
                   type="text" 
                   name="name" 
                   value="{{ old('name') }}" 
                   required 
                   autofocus 
                   autocomplete="name"
                   placeholder="Enter your full name" />
            @error('name')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <!-- Email Address -->
        <div class="form-group">
            <label for="email" class="form-label">{{ __('Email') }}</label>
            <input id="email" 
                   class="form-input" 
                   type="email" 
                   name="email" 
                   value="{{ old('email') }}" 
                   required 
                   autocomplete="username"
                   placeholder="Enter your email address" />
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
                   autocomplete="new-password"
                   placeholder="Create a strong password" />
            @error('password')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="form-group">
            <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
            <input id="password_confirmation" 
                   class="form-input"
                   type="password"
                   name="password_confirmation" 
                   required 
                   autocomplete="new-password"
                   placeholder="Confirm your password" />
            @error('password_confirmation')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-actions">
            <a class="forgot-password" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <button type="submit" class="login-btn">
                {{ __('Register') }}
            </button>
        </div>
    </form>

    <div class="register-link">
        <p style="color: #654321; font-size: 0.9rem;">
            By registering, you agree to our Terms of Service and Privacy Policy
        </p>
    </div>
</x-guest-layout>
