<x-teacher-layout>
    <!-- Page Header -->
    <div class="flex items-center mb-8">
        <a href="{{ route('teacher.courses.index') }}" 
           class="mr-4 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Create New Course</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Add a new course to your teaching portfolio</p>
        </div>
    </div>

    <div class="max-w-2xl mx-auto">
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-8">
                <form method="POST" action="{{ route('teacher.courses.store') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Course Title
                        </label>
                        <input type="text" 
                               id="title" 
                               name="title" 
                               value="{{ old('title') }}"
                               class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 @enderror"
                               placeholder="Enter course title"
                               required>
                        @error('title')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="section" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Section
                        </label>
                        <input type="text" 
                               id="section" 
                               name="section" 
                               value="{{ old('section') }}"
                               class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('section') border-red-500 @enderror"
                               placeholder="Enter section (e.g., A, B, 1, 2, etc.)">
                        @error('section')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="student_capacity" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Student Capacity
                        </label>
                        <input type="number" 
                               id="student_capacity" 
                               name="student_capacity" 
                               value="{{ old('student_capacity', 30) }}"
                               min="1"
                               max="200"
                               class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('student_capacity') border-red-500 @enderror"
                               placeholder="Enter maximum number of students">
                        @error('student_capacity')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Course Description
                        </label>
                        <textarea id="description" 
                                  name="description" 
                                  rows="6"
                                  class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                                  placeholder="Enter course description"
                                  required>{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Course Image (Optional)
                        </label>
                        <input type="file" 
                               id="image" 
                               name="image" 
                               accept="image/jpeg,image/png,image/jpg"
                               class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('image') border-red-500 @enderror">
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Upload a course image (JPG, PNG, max 2MB)</p>
                        @error('image')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Course Structure Section -->
                    <div class="border-t border-gray-200 dark:border-gray-600 pt-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Course Structure</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Define the terms and weeks for your course. You can add up to 4 terms.</p>
                        <div class="bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-700 rounded-lg p-3 mb-6">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div class="text-sm text-blue-800 dark:text-blue-200">
                                    <p class="font-medium mb-1">Default settings:</p>
                                    <ul class="space-y-1 text-xs">
                                        <li>• Default weeks: 18 — change if needed</li>
                                        <li>• Default sections: Prelims / Midterms / Finals — editable / removable</li>
                                        <li>• You can add up to 4 terms</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div id="terms-container">
                            <!-- Terms will be added here dynamically -->
                        </div>
                        
                        <button type="button" 
                                id="add-term-btn" 
                                class="mt-4 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-150 ease-in-out disabled:opacity-50 disabled:cursor-not-allowed"
                                title="You can add up to 4 terms">
                            + Add Term
                        </button>
                    </div>

                    <div class="flex gap-4 pt-4">
                        <button type="submit" 
                                class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg shadow-md transition duration-150 ease-in-out">
                            Create Course
                        </button>
                        <a href="{{ route('teacher.courses.index') }}" 
                           class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-lg shadow-md transition duration-150 ease-in-out">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let termCount = 0;
            const maxTerms = 4;
            const termsContainer = document.getElementById('terms-container');
            const addTermBtn = document.getElementById('add-term-btn');

            // Add first term by default
            addTerm();

            addTermBtn.addEventListener('click', function() {
                if (termCount < maxTerms) {
                    addTerm();
                } else {
                    alert('Maximum of 4 terms allowed.');
                }
            });

            function addTerm() {
                if (termCount >= maxTerms) return;

                const termIndex = termCount;
                const termElement = document.createElement('div');
                termElement.className = 'term-item mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600';
                termElement.innerHTML = `
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="text-md font-medium text-gray-900 dark:text-white">Term ${termIndex + 1}</h4>
                        <button type="button" class="remove-term-btn text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300" 
                                onclick="return confirm('Are you sure you want to delete this term and all its weeks/sections?')">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Term Name
                            </label>
                            <input type="text" 
                                   name="terms[${termIndex}][name]" 
                                   value="Term ${termIndex + 1}"
                                   class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-600 dark:text-white rounded-md p-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="e.g., First Term, Prelims, etc.">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Total Weeks
                            </label>
                            <input type="number" 
                                   name="terms[${termIndex}][total_weeks]" 
                                   value="18"
                                   min="1" 
                                   max="52"
                                   class="total-weeks-input w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-600 dark:text-white rounded-md p-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Number of weeks">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Term Description
                        </label>
                        <textarea name="terms[${termIndex}][description]" 
                                  rows="2"
                                  class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-600 dark:text-white rounded-md p-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Describe what this term covers..."></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Sections (Prelims, Midterms, Finals, etc.)
                        </label>
                        <div class="sections-container">
                            <div class="section-item mb-2 flex gap-2">
                                <input type="text" 
                                       name="terms[${termIndex}][sections][0][title]" 
                                       value="Prelims"
                                       class="flex-1 border border-gray-300 dark:border-gray-600 dark:bg-gray-600 dark:text-white rounded-md p-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Section title">
                                <input type="text" 
                                       name="terms[${termIndex}][sections][0][description]" 
                                       value="Preliminary examinations and assessments"
                                       class="flex-1 border border-gray-300 dark:border-gray-600 dark:bg-gray-600 dark:text-white rounded-md p-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Section description">
                                <button type="button" 
                                        class="remove-section-btn text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 px-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                            <div class="section-item mb-2 flex gap-2">
                                <input type="text" 
                                       name="terms[${termIndex}][sections][1][title]" 
                                       value="Midterms"
                                       class="flex-1 border border-gray-300 dark:border-gray-600 dark:bg-gray-600 dark:text-white rounded-md p-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Section title">
                                <input type="text" 
                                       name="terms[${termIndex}][sections][1][description]" 
                                       value="Midterm examinations and assessments"
                                       class="flex-1 border border-gray-300 dark:border-gray-600 dark:bg-gray-600 dark:text-white rounded-md p-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Section description">
                                <button type="button" 
                                        class="remove-section-btn text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 px-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                            <div class="section-item mb-2 flex gap-2">
                                <input type="text" 
                                       name="terms[${termIndex}][sections][2][title]" 
                                       value="Finals"
                                       class="flex-1 border border-gray-300 dark:border-gray-600 dark:bg-gray-600 dark:text-white rounded-md p-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Section title">
                                <input type="text" 
                                       name="terms[${termIndex}][sections][2][description]" 
                                       value="Final examinations and assessments"
                                       class="flex-1 border border-gray-300 dark:border-gray-600 dark:bg-gray-600 dark:text-white rounded-md p-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Section description">
                                <button type="button" 
                                        class="remove-section-btn text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 px-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <button type="button" 
                                class="add-section-btn mt-2 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm">
                            + Add Custom Section
                        </button>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Weeks (${termIndex + 1})
                        </label>
                        <div class="weeks-container bg-gray-100 dark:bg-gray-600 rounded-lg p-3">
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2" id="weeks-${termIndex}">
                                <!-- Weeks will be generated here -->
                            </div>
                        </div>
                    </div>
                `;

                termsContainer.appendChild(termElement);
                termCount++;

                // Add event listeners for this term
                const removeTermBtn = termElement.querySelector('.remove-term-btn');
                const addSectionBtn = termElement.querySelector('.add-section-btn');
                const sectionsContainer = termElement.querySelector('.sections-container');
                const totalWeeksInput = termElement.querySelector('.total-weeks-input');
                const weeksContainer = termElement.querySelector(`#weeks-${termIndex}`);

                removeTermBtn.addEventListener('click', function() {
                    if (confirm('Are you sure you want to delete this term and all its weeks/sections?')) {
                        termElement.remove();
                        termCount--;
                        updateTermNumbers();
                        updateAddTermButton();
                    }
                });

                addSectionBtn.addEventListener('click', function() {
                    addSection(sectionsContainer, termIndex);
                });

                // Generate weeks when total weeks changes
                totalWeeksInput.addEventListener('input', function() {
                    generateWeeks(weeksContainer, termIndex, parseInt(this.value) || 0);
                });

                // Generate initial weeks
                generateWeeks(weeksContainer, termIndex, 18);

                // Add section remove listeners
                sectionsContainer.addEventListener('click', function(e) {
                    if (e.target.closest('.remove-section-btn')) {
                        e.target.closest('.section-item').remove();
                    }
                });

                updateAddTermButton();
            }

            function addSection(container, termIndex) {
                const sectionCount = container.children.length;
                const sectionElement = document.createElement('div');
                sectionElement.className = 'section-item mb-2 flex gap-2';
                sectionElement.innerHTML = `
                    <input type="text" 
                           name="terms[${termIndex}][sections][${sectionCount}][title]" 
                           class="flex-1 border border-gray-300 dark:border-gray-600 dark:bg-gray-600 dark:text-white rounded-md p-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Section title">
                    <input type="text" 
                           name="terms[${termIndex}][sections][${sectionCount}][description]" 
                           class="flex-1 border border-gray-300 dark:border-gray-600 dark:bg-gray-600 dark:text-white rounded-md p-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Section description">
                    <button type="button" 
                            class="remove-section-btn text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 px-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                `;

                container.appendChild(sectionElement);
            }

            function generateWeeks(container, termIndex, totalWeeks) {
                container.innerHTML = '';
                
                for (let i = 1; i <= totalWeeks; i++) {
                    const weekElement = document.createElement('div');
                    weekElement.className = 'week-item bg-white dark:bg-gray-800 rounded border border-gray-200 dark:border-gray-500 p-2';
                    weekElement.innerHTML = `
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <input type="text" 
                                       name="terms[${termIndex}][weeks][${i-1}][title]" 
                                       value="Week ${i}"
                                       class="w-full text-sm font-medium text-gray-900 dark:text-white bg-transparent border-none focus:ring-0 p-0"
                                       placeholder="Week title">
                                <input type="text" 
                                       name="terms[${termIndex}][weeks][${i-1}][notes]" 
                                       class="w-full text-xs text-gray-600 dark:text-gray-400 bg-transparent border-none focus:ring-0 p-0 mt-1"
                                       placeholder="Optional notes...">
                            </div>
                            <button type="button" 
                                    class="remove-week-btn text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 ml-2"
                                    onclick="return confirm('Delete this week?')">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    `;
                    container.appendChild(weekElement);
                }
            }

            function updateTermNumbers() {
                const terms = termsContainer.querySelectorAll('.term-item');
                terms.forEach((term, index) => {
                    const title = term.querySelector('h4');
                    title.textContent = `Term ${index + 1}`;
                });
            }

            function updateAddTermButton() {
                if (termCount >= maxTerms) {
                    addTermBtn.disabled = true;
                    addTermBtn.textContent = 'Maximum 4 terms reached';
                } else {
                    addTermBtn.disabled = false;
                    addTermBtn.textContent = '+ Add Term';
                }
            }
        });
    </script>
</x-teacher-layout>
