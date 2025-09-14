<!-- Notification Dropdown -->
<div class="relative" x-data="{ open: false }">
    <button @click="open = !open" class="relative p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors duration-200">
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5"></path>
        </svg>
        <!-- Unread count badge -->
        <span id="notification-count" class="absolute top-1 right-1 h-2 w-2 bg-red-500 rounded-full hidden"></span>
    </button>

    <!-- Dropdown Menu -->
    <div x-show="open" 
         @click.away="open = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 z-50 hidden"
         style="display: none;">
        
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Notifications</h3>
                <button onclick="markAllAsRead()" class="text-sm text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">
                    Mark all as read
                </button>
            </div>
        </div>

        <!-- Notifications List -->
        <div id="notifications-list" class="max-h-96 overflow-y-auto">
            <!-- Notifications will be loaded here via JavaScript -->
            <div class="p-4 text-center text-gray-500 dark:text-gray-400">
                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-indigo-600 mx-auto mb-2"></div>
                Loading notifications...
            </div>
        </div>

        <!-- Footer -->
        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
            <a href="{{ route('notifications.index') }}" class="block text-center text-sm text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">
                View all notifications
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load notifications when dropdown opens
    const notificationButton = document.querySelector('[x-data] button');
    const notificationsList = document.getElementById('notifications-list');
    const notificationCount = document.getElementById('notification-count');
    
    // Load unread count
    loadUnreadCount();
    
    // Load notifications when dropdown opens
    notificationButton.addEventListener('click', function() {
        if (notificationsList.innerHTML.includes('Loading notifications...')) {
            loadNotifications();
        }
    });
    
    // Auto-refresh every 30 seconds
    setInterval(loadUnreadCount, 30000);
});

function loadUnreadCount() {
    fetch('{{ route("api.notifications.unread-count") }}')
        .then(response => response.json())
        .then(data => {
            const countElement = document.getElementById('notification-count');
            if (data.count > 0) {
                countElement.textContent = data.count;
                countElement.classList.remove('hidden');
            } else {
                countElement.classList.add('hidden');
            }
        })
        .catch(error => console.error('Error loading notification count:', error));
}

function loadNotifications() {
    fetch('{{ route("api.notifications.recent") }}')
        .then(response => response.json())
        .then(data => {
            const notificationsList = document.getElementById('notifications-list');
            
            if (data.length === 0) {
                notificationsList.innerHTML = `
                    <div class="p-4 text-center text-gray-500 dark:text-gray-400">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5"></path>
                        </svg>
                        No notifications yet
                    </div>
                `;
                return;
            }
            
            let html = '';
            data.forEach(notification => {
                const timeAgo = getTimeAgo(notification.created_at);
                const isRead = notification.read ? 'bg-gray-50 dark:bg-gray-700' : 'bg-white dark:bg-gray-800';
                
                html += `
                    <div class="p-4 border-b border-gray-200 dark:border-gray-700 ${isRead} hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                ${getNotificationIcon(notification.type)}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">${notification.title}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">${notification.message}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">${timeAgo}</p>
                            </div>
                            ${!notification.read ? '<div class="flex-shrink-0"><div class="h-2 w-2 bg-indigo-600 rounded-full"></div></div>' : ''}
                        </div>
                    </div>
                `;
            });
            
            notificationsList.innerHTML = html;
        })
        .catch(error => {
            console.error('Error loading notifications:', error);
            notificationsList.innerHTML = `
                <div class="p-4 text-center text-red-500">
                    Error loading notifications
                </div>
            `;
        });
}

function markAllAsRead() {
    fetch('{{ route("notifications.read-all") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
    })
    .then(response => {
        if (response.ok) {
            loadUnreadCount();
            loadNotifications();
        }
    })
    .catch(error => console.error('Error marking notifications as read:', error));
}

function getNotificationIcon(type) {
    const icons = {
        'course_approved': '<svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>',
        'course_rejected': '<svg class="h-5 w-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>',
        'course_application': '<svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>',
        'default': '<svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5"></path></svg>'
    };
    return icons[type] || icons['default'];
}

function getTimeAgo(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diffInSeconds = Math.floor((now - date) / 1000);
    
    if (diffInSeconds < 60) return 'Just now';
    if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)}m ago`;
    if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)}h ago`;
    if (diffInSeconds < 2592000) return `${Math.floor(diffInSeconds / 86400)}d ago`;
    return date.toLocaleDateString();
}
</script>
