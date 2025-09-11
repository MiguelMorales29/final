<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <div>
                            <x-input-label for="birth_date" :value="__('Birth Date')" />
                            <x-text-input id="birth_date" name="birth_date" type="date" class="mt-1 block w-full" :value="old('birth_date', $profile->birth_date)" />
                            <x-input-error :messages="$errors->get('birth_date')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="gender" :value="__('Gender')" />
                            <x-text-input id="gender" name="gender" type="text" class="mt-1 block w-full" :value="old('gender', $profile->gender)" />
                            <x-input-error :messages="$errors->get('gender')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="bio" :value="__('Bio')" />
                            <textarea id="bio" name="bio" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300" rows="4">{{ old('bio', $profile->bio) }}</textarea>
                            <x-input-error :messages="$errors->get('bio')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="profile_picture" :value="__('Profile Picture')" />
                            <input id="profile_picture" name="profile_picture" type="file" class="mt-1 block w-full" accept="image/*">
                            <x-input-error :messages="$errors->get('profile_picture')" class="mt-2" />
                        </div>

                        <div class="mt-6 flex items-center justify-end">
                            <x-primary-button>{{ __('Save') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
