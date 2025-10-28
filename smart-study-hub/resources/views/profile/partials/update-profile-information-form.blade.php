<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Profile Picture -->
        <div>
            <x-input-label for="profile_picture" :value="__('Profile Picture')" />
            <div class="mt-2 flex items-center space-x-4">
                <div class="flex-shrink-0">
                    <img id="profile-picture-preview" 
                         src="{{ $user->profile_picture ? (str_starts_with($user->profile_picture, 'images/') ? asset($user->profile_picture) : (str_starts_with($user->profile_picture, 'http') ? $user->profile_picture : asset('storage/' . $user->profile_picture))) : asset('images/avatars/avatar-default.svg') }}" 
                         alt="Profile Picture" 
                         class="h-20 w-20 rounded-full object-cover border-2 border-gray-300 dark:border-gray-600">
                </div>
                <div class="flex-1">
                    <input id="profile_picture" 
                           name="profile_picture" 
                           type="file" 
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" 
                           accept="image/*"
                           onchange="previewImage(this)">
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __('Or upload your own picture') }}</p>
                </div>
            </div>
            <!-- Default Avatar Selection -->
            <div class="mt-4">
                <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">{{ __('Choose a default avatar') }}</p>
                <div class="grid grid-cols-8 gap-3">
                    @for($i = 1; $i <= 8; $i++)
                        <div class="relative cursor-pointer group">
                            <input type="radio" 
                                   name="selected_avatar" 
                                   id="avatar-{{ $i }}" 
                                   value="images/avatars/avatar-{{ $i }}.svg" 
                                   class="peer sr-only"
                                   {{ str_contains($user->profile_picture ?? '', 'avatar-'.$i.'.svg') ? 'checked' : '' }}
                                   onchange="selectAvatar('images/avatars/avatar-{{ $i }}.svg', 'images/avatars/avatar-{{ $i }}.svg')">
                            <label for="avatar-{{ $i }}" 
                                   class="flex items-center justify-center cursor-pointer">
                                <img src="{{ asset('images/avatars/avatar-'.$i.'.svg') }}" 
                                     alt="Avatar {{ $i }}"
                                     class="w-12 h-12 rounded-full peer-checked:ring-2 peer-checked:ring-indigo-500 peer-checked:ring-offset-2 transition-all hover:scale-110">
                            </label>
                        </div>
                    @endfor
                </div>
            </div>
            
            <input type="hidden" name="avatar_selected" id="avatar_selected" value="">
            <x-input-error class="mt-2" :messages="$errors->get('profile_picture')" />
        </div>

        <!-- Birth Date -->
        <div>
            <x-input-label for="birth_date" :value="__('Birth Date')" />
            <x-text-input id="birth_date" 
                          name="birth_date" 
                          type="date" 
                          class="mt-1 block w-full" 
                          :value="old('birth_date', $user->birth_date?->format('Y-m-d'))" />
            <x-input-error class="mt-2" :messages="$errors->get('birth_date')" />
        </div>

        <!-- Gender -->
        <div>
            <x-input-label for="gender" :value="__('Gender')" />
            <select id="gender" 
                    name="gender" 
                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                <option value="">{{ __('Select Gender') }}</option>
                <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>{{ __('Male') }}</option>
                <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>{{ __('Female') }}</option>
                <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>{{ __('Other') }}</option>
                <option value="prefer_not_to_say" {{ old('gender', $user->gender) == 'prefer_not_to_say' ? 'selected' : '' }}>{{ __('Prefer not to say') }}</option>
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('gender')" />
        </div>

        <!-- Bio -->
        <div>
            <x-input-label for="bio" :value="__('Bio')" />
            <textarea id="bio" 
                      name="bio" 
                      rows="4" 
                      class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                      placeholder="{{ __('Tell us about yourself...') }}">{{ old('bio', $user->bio) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('bio')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>

    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profile-picture-preview').src = e.target.result;
                    document.getElementById('avatar_selected').value = '';
                    // Uncheck all radio buttons
                    document.querySelectorAll('input[name="selected_avatar"]').forEach(radio => {
                        radio.checked = false;
                    });
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        
        function selectAvatar(avatarPath) {
            document.getElementById('profile-picture-preview').src = '{{ asset('') }}' + avatarPath;
            document.getElementById('avatar_selected').value = avatarPath;
            // Clear file input if avatar is selected
            var fileInput = document.getElementById('profile_picture');
            if (fileInput) {
                fileInput.value = '';
            }
        }
    </script>
</section>
