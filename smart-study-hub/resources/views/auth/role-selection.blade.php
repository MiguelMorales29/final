<x-guest-layout>
    <div class="max-w-md mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Welcome to Smart Study Hub!</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Please select your role to continue</p>
        </div>

        <!-- User Info -->
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-6">
            <div class="flex items-center">
                <img src="{{ session('google_user_avatar') }}" alt="Profile" class="w-10 h-10 rounded-full mr-3">
                <div>
                    <p class="font-medium text-blue-900 dark:text-blue-100">{{ session('google_user_name') }}</p>
                    <p class="text-sm text-blue-700 dark:text-blue-300">{{ session('google_user_email') }}</p>
                </div>
            </div>
        </div>

        <!-- Role Selection Form -->
        <form method="POST" action="{{ route('auth.google.role-selection') }}">
            @csrf
            
            <div class="space-y-4">
                <!-- Student Option -->
                <label class="block">
                    <input type="radio" name="role" value="student" class="sr-only" required>
                    <div class="border-2 border-gray-200 dark:border-gray-700 rounded-lg p-4 cursor-pointer hover:border-blue-500 dark:hover:border-blue-400 transition-colors duration-200 role-option" data-role="student">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Student</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">I want to enroll in courses and learn</p>
                            </div>
                        </div>
                    </div>
                </label>

                <!-- Teacher Option -->
                <label class="block">
                    <input type="radio" name="role" value="teacher" class="sr-only" required>
                    <div class="border-2 border-gray-200 dark:border-gray-700 rounded-lg p-4 cursor-pointer hover:border-blue-500 dark:hover:border-blue-400 transition-colors duration-200 role-option" data-role="teacher">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Teacher</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">I want to create courses and teach students</p>
                            </div>
                        </div>
                    </div>
                </label>
            </div>

            <!-- Submit Button -->
            <div class="mt-8">
                <x-primary-button class="w-full">
                    Continue
                </x-primary-button>
            </div>
        </form>

        <!-- Back to Login -->
        <div class="mt-6 text-center">
            <a href="{{ route('login') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
                ← Back to Login
            </a>
        </div>
    </div>

    <!-- JavaScript for Role Selection -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleOptions = document.querySelectorAll('.role-option');
            
            roleOptions.forEach(option => {
                option.addEventListener('click', function() {
                    // Remove selected class from all options
                    roleOptions.forEach(opt => {
                        opt.classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
                        opt.classList.add('border-gray-200', 'dark:border-gray-700');
                    });
                    
                    // Add selected class to clicked option
                    this.classList.remove('border-gray-200', 'dark:border-gray-700');
                    this.classList.add('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
                    
                    // Check the radio button
                    const radio = this.parentElement.querySelector('input[type="radio"]');
                    radio.checked = true;
                });
            });
        });
    </script>
</x-guest-layout>





