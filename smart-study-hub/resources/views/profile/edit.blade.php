@if(Auth::user()->role === 'teacher')
    <x-profile-layout>
        <!-- Profile Header -->
        <div class="mb-8 pl-4 md:pl-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Profile Settings</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Manage your account settings and preferences.</p>
        </div>

        <div class="space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div id="password-section" class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('Theme Settings') }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                {{ __('Choose your preferred theme for the application.') }}
                            </p>
                        </header>

                        <div class="mt-6">
                            <form method="POST" action="{{ route('profile.theme') }}">
                                @csrf
                                @method('PATCH')
                                
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ __('Current Theme') }}
                                        </h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            {{ Auth::user()->theme === 'dark' ? 'Dark Mode' : 'Light Mode' }}
                                        </p>
                                    </div>
                                    
                                    <button type="submit" 
                                        name="theme" 
                                        value="{{ Auth::user()->theme === 'dark' ? 'light' : 'dark' }}"
                                        class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-gray-300 focus:bg-gray-700 dark:focus:bg-gray-300 active:bg-gray-900 dark:active:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                        {{ Auth::user()->theme === 'dark' ? 'Switch to Light Mode' : 'Switch to Dark Mode' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </section>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </x-profile-layout>
@else
    <x-student-profile-layout>
        <!-- Profile Header -->
        <div class="mb-8 pl-4 md:pl-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Profile Settings</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Manage your account settings and preferences.</p>
        </div>

        <div class="space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            @if(Auth::user()->role === 'student')
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('Student Information') }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                {{ __('Your student identification details.') }}
                            </p>
                        </header>

                        <div class="mt-6">
                            @if(Auth::user()->student_number)
                            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">
                                            Student Number
                                        </h3>
                                        <div class="mt-1 text-sm text-blue-700 dark:text-blue-300">
                                            <p class="font-mono text-lg font-bold">{{ Auth::user()->student_number }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                                            No Student Number
                                        </h3>
                                        <div class="mt-1 text-sm text-yellow-700 dark:text-yellow-300">
                                            <p class="mb-3">You don't have a student number assigned yet. Contact your administrator to get one.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </section>
                </div>
            </div>
            @endif

            <div id="password-section" class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('Theme Settings') }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                {{ __('Choose your preferred theme for the application.') }}
                            </p>
                        </header>

                        <div class="mt-6">
                            <form method="POST" action="{{ route('profile.theme') }}">
                                @csrf
                                @method('PATCH')
                                
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ __('Current Theme') }}
                                        </h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            {{ Auth::user()->theme === 'dark' ? 'Dark Mode' : 'Light Mode' }}
                                        </p>
                                    </div>
                                    
                                    <button type="submit" 
                                        name="theme" 
                                        value="{{ Auth::user()->theme === 'dark' ? 'light' : 'dark' }}"
                                        class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-gray-300 focus:bg-gray-700 dark:focus:bg-gray-300 active:bg-gray-900 dark:active:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                        {{ Auth::user()->theme === 'dark' ? 'Switch to Light Mode' : 'Switch to Dark Mode' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </section>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </x-student-profile-layout>
@endif