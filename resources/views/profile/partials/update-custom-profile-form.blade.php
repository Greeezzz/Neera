<section>
    <header>
        <h2 class="text-lg font-medium text-coffee-800">
            {{ __('Profile Customization') }}
        </h2>

        <p class="mt-1 text-sm text-coffee-600">
            {{ __("Update your profile picture, name, and bio.") }}
        </p>
        
        {{-- View Profile Link --}}
        <div class="mt-2">
            <a href="{{ route('user.profile', auth()->id()) }}" 
               class="btn-coffee inline-flex items-center gap-1 text-sm">
                👁️ View My Public Profile
            </a>
        </div>
    </header>

    <form method="post" action="{{ route('profile.update.custom') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        {{-- Current Profile Picture --}}
        @if(auth()->user()->profile_picture)
            <div>
                <x-input-label for="current_picture" :value="__('Current Profile Picture')" />
                <div class="mt-2">
                    <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" 
                         alt="Current profile picture" 
                         class="w-20 h-20 rounded-full object-cover">
                </div>
            </div>
        @endif

        {{-- Profile Picture Upload --}}
        <div>
            <x-input-label for="profile_picture" :value="__('Profile Picture')" />
            <input id="profile_picture" name="profile_picture" type="file" 
                   class="mt-1 block w-full border-2 border-cream-200 bg-cream-50 text-coffee-700 focus:border-coffee-400 focus:ring-2 focus:ring-coffee-200 rounded-xl shadow-sm file:mr-4 file:py-2 file:px-4 file:rounded-l-xl file:border-0 file:bg-coffee-100 file:text-coffee-700 file:font-medium hover:file:bg-coffee-200 transition-all duration-300"
                   accept="image/*" />
            <x-input-error class="mt-2" :messages="$errors->get('profile_picture')" />
        </div>

        {{-- Name --}}
        <div>
            <x-input-label for="name" :value="__('Display Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" 
                          :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        {{-- Bio --}}
        <div>
            <x-input-label for="bio" :value="__('Bio')" />
            <textarea id="bio" name="bio" 
                      class="mt-1 block w-full border-2 border-cream-200 bg-cream-50 text-coffee-700 focus:border-coffee-400 focus:ring-2 focus:ring-coffee-200 rounded-xl shadow-sm resize-none"
                      rows="3" 
                      placeholder="Tell us about yourself...">{{ old('bio', $user->bio) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('bio')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save Profile') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-coffee-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
