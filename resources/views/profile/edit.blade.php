<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-gradient-to-br from-coffee-500 to-coffee-600 rounded-xl flex items-center justify-center">
                <span class="text-cream-50 text-lg">⚙️</span>
            </div>
            <h2 class="font-bold text-2xl text-gradient">Profile Settings</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            {{-- Custom Profile Settings --}}
            <div class="card-coffee p-6 hover-lift animate-fade-in-up">
                <div class="max-w-xl">
                    @include('profile.partials.update-custom-profile-form')
                </div>
            </div>
            
            <div class="card-coffee p-6 hover-lift animate-fade-in-up" style="animation-delay: 0.1s;">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="card-coffee p-6 hover-lift animate-fade-in-up" style="animation-delay: 0.2s;">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="card-coffee p-6 hover-lift animate-fade-in-up" style="animation-delay: 0.3s;">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
