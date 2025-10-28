<!-- Smart Buddy Widget -->
<div x-data="smartBuddy(@js($textContent ?? ''), @js($materialId ?? ''))" 
     class="fixed bottom-6 right-6 z-50">
    
    <!-- Smart Buddy Button -->
    <button @click="togglePanel()" 
            class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-blue-600 hover:from-indigo-600 hover:to-blue-700 text-white rounded-full shadow-xl flex items-center justify-center transition-all duration-300 hover:scale-110 focus:outline-none focus:ring-4 focus:ring-indigo-300">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
        </svg>
        <span x-show="notificationCount > 0" 
              class="absolute -top-1 -right-1 w-6 h-6 bg-red-500 text-white text-xs rounded-full flex items-center justify-center animate-pulse"
              x-text="notificationCount"></span>
    </button>

    <!-- Smart Buddy Panel -->
    <div x-show="isOpen" 
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-4 scale-95"
         x-transition:enter-end="opacity-100 transform translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 transform translate-y-4 scale-95"
         class="absolute bottom-20 right-0 w-96 h-[600px] bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 flex flex-col overflow-hidden">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-blue-600 px-6 py-4 text-white">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold">Smart Buddy</h3>
                        <p class="text-sm text-blue-100">Your AI study assistant</p>
                    </div>
                </div>
                <button @click="togglePanel()" class="text-white hover:text-blue-200 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Tabs -->
        <div class="flex border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
            <button @click="activeTab = 'chat'" 
                    :class="activeTab === 'chat' ? 'bg-white dark:bg-gray-800 text-indigo-600 dark:text-indigo-400 border-b-2 border-indigo-600' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800'"
                    class="flex-1 px-4 py-3 text-sm font-medium transition">💬 Chat</button>
            <button @click="activeTab = 'reviewer'" 
                    :class="activeTab === 'reviewer' ? 'bg-white dark:bg-gray-800 text-indigo-600 dark:text-indigo-400 border-b-2 border-indigo-600' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800'"
                    class="flex-1 px-4 py-3 text-sm font-medium transition">🧠 Reviewer</button>
            <button @click="activeTab = 'flashcards'" 
                    :class="activeTab === 'flashcards' ? 'bg-white dark:bg-gray-800 text-indigo-600 dark:text-indigo-400 border-b-2 border-indigo-600' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800'"
                    class="flex-1 px-4 py-3 text-sm font-medium transition">🗂️ Flashcards</button>
            <button @click="activeTab = 'quiz'" 
                    :class="activeTab === 'quiz' ? 'bg-white dark:bg-gray-800 text-indigo-600 dark:text-indigo-400 border-b-2 border-indigo-600' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800'"
                    class="flex-1 px-4 py-3 text-sm font-medium transition">❓ Quiz</button>
        </div>

        <!-- Content -->
        <div class="flex-1 overflow-y-auto p-4">
            <!-- Chat -->
            <div x-show="activeTab === 'chat'" class="h-full flex flex-col">
                <div class="flex-1 overflow-y-auto space-y-4 mb-4" x-ref="chatContainer">
                    <template x-for="(msg, idx) in messages" :key="idx">
                        <div class="flex items-start space-x-3" :class="msg.type === 'user' ? 'justify-end' : ''">
                            <template x-if="msg.type === 'bot'">
                                <div class="flex items-start space-x-3">
                                    <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center">SB</div>
                                    <div class="bg-gray-100 dark:bg-gray-700 p-3 rounded-lg max-w-[80%]">
                                        <p class="text-sm text-gray-800 dark:text-gray-200" x-text="msg.text"></p>
                                    </div>
                                </div>
                            </template>
                            <template x-if="msg.type === 'user'">
                                <div class="bg-indigo-600 text-white p-3 rounded-lg max-w-[80%]">
                                    <p class="text-sm" x-text="msg.text"></p>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>
                <div class="flex items-center border-t border-gray-200 dark:border-gray-700 pt-4">
                    <input x-model="chatInput" type="text" placeholder="Ask a question or type a command..." class="flex-1 px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                    <button @click="sendMessage()" class="ml-2 p-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </div>
            </div>

            <!-- Reviewer -->
            <div x-show="activeTab === 'reviewer'" class="space-y-3">
                <div class="flex items-center justify-between">
                    <h4 class="text-md font-semibold text-gray-900 dark:text-white">Reviewer</h4>
                    <button @click="generateReviewer()" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded text-sm">Generate</button>
                </div>
                <div class="space-y-2">
                    <template x-for="(rev, idx) in reviewers" :key="idx">
                        <div class="p-3 rounded border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                            <div class="font-medium text-gray-900 dark:text-white" x-text="rev.title"></div>
                            <div class="text-sm text-gray-700 dark:text-gray-300 mt-1" x-text="rev.content"></div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Flashcards -->
            <div x-show="activeTab === 'flashcards'" class="space-y-3">
                <div class="flex items-center justify-between">
                    <h4 class="text-md font-semibold text-gray-900 dark:text-white">Flashcards</h4>
                    <button @click="generateFlashcards()" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded text-sm">Generate</button>
                </div>
                <div class="relative perspective-1000">
                    <div class="p-4 rounded border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 preserve-3d" :class="flipCard ? 'flip-card' : ''">
                        <div class="min-h-[120px] backface-hidden" :class="flipCard ? 'hidden' : ''">
                            <div class="text-sm text-gray-500 dark:text-gray-400">Question</div>
                            <div class="text-gray-900 dark:text-white font-medium mt-1" x-text="currentFlashcard().question || 'Click Generate to create flashcards' "></div>
                        </div>
                        <div class="min-h-[120px] backface-hidden flip-back" :class="flipCard ? '' : 'hidden'">
                            <div class="text-sm text-gray-500 dark:text-gray-400">Answer</div>
                            <div class="text-gray-900 dark:text-white font-medium mt-1" x-text="currentFlashcard().answer || '—'"></div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex gap-2">
                        <button @click="prevFlashcard()" class="px-3 py-1.5 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded">Prev</button>
                        <button @click="nextFlashcard()" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded">Next</button>
                    </div>
                    <button @click="flipCard = !flipCard" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded">Flip</button>
                </div>
            </div>

            <!-- Quiz -->
            <div x-show="activeTab === 'quiz'" class="space-y-3">
                <div class="flex items-center justify-between">
                    <h4 class="text-md font-semibold text-gray-900 dark:text-white">Quiz</h4>
                    <button @click="generateQuiz()" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded text-sm">Generate</button>
                </div>
                <template x-if="quizQuestions.length">
                    <div>
                        <div class="text-gray-900 dark:text-white font-medium" x-text="quizQuestions[currentQuizIndex].question"></div>
                        <div class="mt-2 space-y-2">
                            <template x-for="(opt, idx) in quizQuestions[currentQuizIndex].options" :key="idx">
                                <label class="flex items-center gap-2 text-sm text-gray-800 dark:text-gray-200">
                                    <input type="radio" :name="'q'+currentQuizIndex" :value="idx" @change="selectAnswer(idx)" :checked="selectedAnswers[currentQuizIndex] === idx">
                                    <span x-text="opt"></span>
                                </label>
                            </template>
                        </div>
                        <div class="flex gap-2 mt-3">
                            <button @click="previousQuiz()" :disabled="currentQuizIndex === 0" class="flex-1 px-3 py-1.5 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded disabled:opacity-50">Prev</button>
                            <button @click="nextQuiz()" :disabled="currentQuizIndex === quizQuestions.length - 1" class="flex-1 px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded disabled:opacity-50">Next</button>
                        </div>
                        <button x-show="currentQuizIndex === quizQuestions.length - 1" @click="submitQuiz()" class="w-full mt-2 px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white rounded">Submit</button>
                    </div>
                </template>
                <template x-if="!quizQuestions.length">
                    <div class="text-sm text-gray-600 dark:text-gray-400">Click Generate to create a quiz.</div>
                </template>
            </div>
        </div>
    </div>

    <style>
    [x-cloak] { display: none !important; }
    .perspective-1000 { perspective: 1000px; }
    .preserve-3d { transform-style: preserve-3d; }
    .backface-hidden { backface-visibility: hidden; }
    .flip-card { transform: rotateY(180deg); }
    .flip-back { transform: rotateY(180deg); }
    </style>

    <script>
    function smartBuddy(textContent, materialId) {
        return {
            isOpen: false,
            activeTab: 'chat',
            chatInput: '',
            messages: [],
            reviewers: [],
            flashcards: [],
            quizQuestions: [],
            currentFlashcardIndex: 0,
            currentQuizIndex: 0,
            selectedAnswers: {},
            flipCard: false,
            notificationCount: 0,
            hasGreeted: false,

            init() {
                this.loadStoredData();
                if (!this.hasGreeted) {
                    this.addMessage('bot', this.getGreeting());
                    this.hasGreeted = true;
                }
            },

            togglePanel() {
                this.isOpen = !this.isOpen;
                if (this.isOpen) {
                    this.$nextTick(() => {
                        this.$refs?.chatContainer?.scrollTo({ top: this.$refs.chatContainer.scrollHeight, behavior: 'smooth' });
                    });
                }
            },

            // Chat
            sendMessage() {
                const text = (this.chatInput || '').trim();
                if (!text) return;
                this.addMessage('user', text);
                this.chatInput = '';

                // Simple command detection
                const lower = text.toLowerCase();
                if (lower.includes('summarize') || lower.includes('review')) {
                    this.generateReviewer();
                    this.addMessage('bot', 'I generated a reviewer based on your module.');
                } else if (lower.includes('flashcard')) {
                    this.generateFlashcards();
                    this.addMessage('bot', 'Flashcards generated! Use Flip/Prev/Next to study.');
                } else if (lower.includes('quiz')) {
                    this.generateQuiz();
                    this.addMessage('bot', 'Quiz ready! Answer the questions and submit to check your score.');
                } else {
                    const answer = this.answerFromContent(textContent, text);
                    this.addMessage('bot', answer);
                }
                this.saveData();
            },
            addMessage(type, text) {
                this.messages.push({ type, text, ts: Date.now() });
                this.$nextTick(() => this.$refs?.chatContainer?.scrollTo({ top: this.$refs.chatContainer.scrollHeight }));
            },

            // Reviewer
            generateReviewer() {
                if (!textContent) return;
                const points = this.extractKeyPoints(textContent);
                this.reviewers = points.map((p, i) => ({ title: `Key Idea ${i+1}`, content: p }));
                this.saveData();
            },

            // Flashcards
            generateFlashcards() {
                if (!textContent) return;
                const pairs = this.extractFlashcardPairs(textContent);
                this.flashcards = pairs;
                this.currentFlashcardIndex = 0;
                this.flipCard = false;
                this.saveData();
            },
            currentFlashcard() {
                return this.flashcards[this.currentFlashcardIndex] || {};
            },
            nextFlashcard() {
                if (this.currentFlashcardIndex < this.flashcards.length - 1) this.currentFlashcardIndex++;
            },
            prevFlashcard() {
                if (this.currentFlashcardIndex > 0) this.currentFlashcardIndex--;
            },

            // Quiz
            generateQuiz() {
                if (!textContent) return;
                this.quizQuestions = this.buildQuiz(textContent);
                this.currentQuizIndex = 0;
                this.selectedAnswers = {};
                this.saveData();
            },
            nextQuiz() {
                if (this.currentQuizIndex < this.quizQuestions.length - 1) this.currentQuizIndex++;
            },
            previousQuiz() {
                if (this.currentQuizIndex > 0) this.currentQuizIndex--;
            },
            selectAnswer(optionIndex) {
                this.selectedAnswers[this.currentQuizIndex] = optionIndex;
            },
            submitQuiz() {
                let score = 0;
                this.quizQuestions.forEach((q, i) => {
                    if (this.selectedAnswers[i] === q.correctAnswer) score++;
                });
                const percentage = Math.round((score / (this.quizQuestions.length || 1)) * 100);
                alert(`Quiz Complete!\n\nYou scored ${score} out of ${this.quizQuestions.length} (${percentage}%)`);
            },

            // Helpers
            getGreeting() {
                const greetings = [
                    "Hi! I'm Smart Buddy, your AI study assistant.",
                    "Hello! Ready to study? I can create flashcards, summaries, and quizzes.",
                    "Hey there! Ask me anything about this module.",
                    "Welcome! I can generate reviewers, flashcards, and quizzes."
                ];
                return greetings[Math.floor(Math.random() * greetings.length)];
            },

            answerFromContent(content, question) {
                if (!content) return "I couldn't find content to analyze.";
                const key = this.extractKeyPoints(content)[0] || 'a key concept';
                return `From the module, an important idea is: ${key}`;
            },

            extractKeyPoints(content) {
                // Simple heuristic: split into sentences, pick informative ones
                const sentences = (content || '').replace(/\s+/g, ' ').split(/(?<=[.!?])\s+/).slice(0, 12);
                return sentences.filter(s => s.length > 40).slice(0, 6);
            },

            extractFlashcardPairs(content) {
                const points = this.extractKeyPoints(content);
                return points.map(p => ({ question: `Explain: ${p.slice(0, 50)}...`, answer: p }));
            },

            buildQuiz(content) {
                const points = this.extractKeyPoints(content);
                return points.slice(0, 5).map(p => {
                    const correct = p.slice(0, 60) + '...';
                    const distractor = (i) => `Related concept ${i+1}`;
                    const options = [correct, distractor(1), distractor(2), distractor(3)];
                    return { question: `What best summarizes: ${p.slice(0, 40)}...`, options, correctAnswer: 0 };
                });
            },

            loadStoredData() {
                const stored = localStorage.getItem(`smartBuddy_${materialId}`);
                if (stored) {
                    const data = JSON.parse(stored);
                    this.messages = data.messages || [];
                    this.reviewers = data.reviewers || [];
                    this.flashcards = data.flashcards || [];
                    this.quizQuestions = data.quizQuestions || [];
                }
            },
            saveData() {
                const data = { messages: this.messages, reviewers: this.reviewers, flashcards: this.flashcards, quizQuestions: this.quizQuestions };
                localStorage.setItem(`smartBuddy_${materialId}`, JSON.stringify(data));
            }
        }
    }
    </script>
</div>


