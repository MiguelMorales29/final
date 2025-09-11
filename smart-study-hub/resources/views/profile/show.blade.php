<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex items-center space-x-4">
                        <img src="{{ $profile->profile_picture ? asset('storage/' . $profile->profile_picture) : asset('images/default-avatar.png') }}" alt="Profile Picture" class="w-24 h-24 rounded-full object-cover">
                        <div>
                            <div class="text-lg font-semibold">{{ $user->name }}</div>
                            <div class="text-sm text-gray-500">{{ $user->email }}</div>
                        </div>
                    </div>
                    <div class="mt-6">
                        <p><strong>Birth Date:</strong> {{ $profile->birth_date }}</p>
                        <p class="mt-2"><strong>Gender:</strong> {{ $profile->gender }}</p>
                        <p class="mt-2"><strong>Bio:</strong> {{ $profile->bio }}</p>
                    </div>
                    <div class="mt-6">
                        <a href="{{ route('profile.edit') }}" class="text-blue-500 underline">Edit Profile</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
