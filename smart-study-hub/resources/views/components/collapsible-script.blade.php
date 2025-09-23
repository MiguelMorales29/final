<script>
    function collapsibleManager() {
        return {
            openTerms: {},
            openWeeks: {},
            totalItems: 0,
            totalWeeks: 0,

            init() {
                // Load saved states from sessionStorage
                this.loadSavedStates();
                
                // Calculate totals
                this.calculateTotals();
                
                // Set default state: first term expanded, others collapsed
                this.setDefaultStates();
            },

            toggleTerm(termId) {
                this.openTerms[termId] = !this.openTerms[termId];
                this.saveStates();
            },

            toggleWeek(weekId) {
                this.openWeeks[weekId] = !this.openWeeks[weekId];
                this.saveStates();
            },

            setDefaultStates() {
                // Get all term IDs
                const termHeaders = document.querySelectorAll('[x-data] [x-click*="toggleTerm"]');
                const termIds = [];
                
                termHeaders.forEach(header => {
                    const onclick = header.getAttribute('@click');
                    const match = onclick.match(/toggleTerm\((\d+)\)/);
                    if (match) {
                        termIds.push(parseInt(match[1]));
                    }
                });

                // Set ALL terms as expanded by default if no saved state
                if (termIds.length > 0 && !this.hasSavedStates()) {
                    termIds.forEach(termId => {
                        this.openTerms[termId] = true;
                    });
                }
            },

            calculateTotals() {
                // Calculate total items and weeks
                let items = 0;
                let weeks = 0;

                // Count items from the DOM
                const itemElements = document.querySelectorAll('[x-data] .item-card');
                items = itemElements.length;

                const weekElements = document.querySelectorAll('[x-data] [x-click*="toggleWeek"]');
                weeks = weekElements.length;

                this.totalItems = items;
                this.totalWeeks = weeks;
            },

            saveStates() {
                // Save current states to sessionStorage
                sessionStorage.setItem('collapsible_terms', JSON.stringify(this.openTerms));
                sessionStorage.setItem('collapsible_weeks', JSON.stringify(this.openWeeks));
            },

            loadSavedStates() {
                // Load saved states from sessionStorage
                const savedTerms = sessionStorage.getItem('collapsible_terms');
                const savedWeeks = sessionStorage.getItem('collapsible_weeks');
                
                if (savedTerms) {
                    this.openTerms = JSON.parse(savedTerms);
                }
                if (savedWeeks) {
                    this.openWeeks = JSON.parse(savedWeeks);
                }
            },

            hasSavedStates() {
                return sessionStorage.getItem('collapsible_terms') !== null;
            },

            isCurrentWeek(weekElement) {
                // Optional: Add logic to highlight current week
                // This could be based on current date vs week dates
                return false;
            }
        }
    }
</script>
