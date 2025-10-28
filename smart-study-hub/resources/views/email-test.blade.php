<x-guest-layout>
    <div class="max-w-md mx-auto">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Email Test</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Test email functionality for password reset</p>
        </div>

        <!-- Test Basic Email -->
        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6 mb-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Test Basic Email</h2>
            <form id="testEmailForm">
                @csrf
                <div class="mb-4">
                    <label for="test_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email Address</label>
                    <input type="email" id="test_email" name="email" required 
                           class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                           placeholder="Enter email to test">
                </div>
                <button type="submit" 
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Send Test Email
                </button>
            </form>
            <div id="testEmailResult" class="mt-4 hidden"></div>
        </div>

        <!-- Test Password Reset -->
        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6 mb-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Test Password Reset Email</h2>
            <form id="testPasswordResetForm">
                @csrf
                <div class="mb-4">
                    <label for="reset_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email Address</label>
                    <input type="email" id="reset_email" name="email" required 
                           class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                           placeholder="Enter email for password reset">
                </div>
                <button type="submit" 
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Send Password Reset Email
                </button>
            </form>
            <div id="testPasswordResetResult" class="mt-4 hidden"></div>
        </div>

        <!-- Back to Login -->
        <div class="text-center">
            <a href="{{ route('login') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
                ← Back to Login
            </a>
        </div>
    </div>

    <script>
        // Test Basic Email
        document.getElementById('testEmailForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const resultDiv = document.getElementById('testEmailResult');
            
            try {
                const response = await fetch('/test-email', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                const result = await response.json();
                
                resultDiv.className = 'mt-4 p-4 rounded-md ' + (result.success ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800');
                resultDiv.textContent = result.message;
                resultDiv.classList.remove('hidden');
                
            } catch (error) {
                resultDiv.className = 'mt-4 p-4 rounded-md bg-red-100 text-red-800';
                resultDiv.textContent = 'Error: ' + error.message;
                resultDiv.classList.remove('hidden');
            }
        });

        // Test Password Reset
        document.getElementById('testPasswordResetForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const resultDiv = document.getElementById('testPasswordResetResult');
            
            try {
                const response = await fetch('/test-password-reset', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                const result = await response.json();
                
                resultDiv.className = 'mt-4 p-4 rounded-md ' + (result.success ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800');
                resultDiv.textContent = result.message;
                resultDiv.classList.remove('hidden');
                
            } catch (error) {
                resultDiv.className = 'mt-4 p-4 rounded-md bg-red-100 text-red-800';
                resultDiv.textContent = 'Error: ' + error.message;
                resultDiv.classList.remove('hidden');
            }
        });
    </script>
</x-guest-layout>







