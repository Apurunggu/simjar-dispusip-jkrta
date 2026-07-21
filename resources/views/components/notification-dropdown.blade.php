<!-- Notification Bell Dropdown -->
<div class="nav-item dropdown me-3">
    <button class="nav-link btn btn-link position-relative" id="notificationBell" data-bs-toggle="dropdown" style="border: none; background: none; color: white; text-decoration: none;">
        <i class="bi bi-bell fs-5"></i>
        <span id="unreadCount" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="display: none; font-size: 0.65rem;">
            <span id="unreadCountValue">0</span>
        </span>
    </button>
    
    <div class="dropdown-menu dropdown-menu-end notification-dropdown" style="min-width: 350px; max-height: 500px; overflow-y: auto;">
        <div class="dropdown-header bg-light">
            <h6 class="mb-0">
                <i class="bi bi-bell-fill"></i> Notifikasi
                <small class="float-end text-muted" id="unreadLabel" style="display: none;">
                    <span id="unreadCountHeader">0</span> Baru
                </small>
            </h6>
        </div>
        
        <div id="notificationList" class="notification-list-dropdown">
            <!-- Will be populated by JavaScript -->
            <div class="text-center text-muted p-3">
                <small>Memuat notifikasi...</small>
            </div>
        </div>
        
        <div class="dropdown-divider"></div>
        
        <a href="{{ route('notifications.index') }}" class="dropdown-item text-center text-primary">
            <small><i class="bi bi-arrow-right-circle"></i> Lihat Semua Notifikasi</small>
        </a>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    loadNotifications();
    // Refresh setiap 5 detik
    setInterval(loadNotifications, 5000);
});

function loadNotifications() {
    fetch('{{ route("notifications.dropdown") }}')
        .then(response => response.json())
        .then(data => {
            const unreadCount = data.unread_count;
            const notifications = data.notifications;
            const unreadCountEl = document.getElementById('unreadCount');
            const unreadCountValue = document.getElementById('unreadCountValue');
            const unreadCountHeader = document.getElementById('unreadCountHeader');
            const unreadLabel = document.getElementById('unreadLabel');
            const notificationList = document.getElementById('notificationList');

            // Update unread count badge
            if (unreadCount > 0) {
                unreadCountValue.textContent = unreadCount;
                unreadCountEl.style.display = 'inline-block';
                unreadLabel.style.display = 'inline-block';
                unreadCountHeader.textContent = unreadCount;
            } else {
                unreadCountEl.style.display = 'none';
                unreadLabel.style.display = 'none';
            }

            // Populate notification list
            if (notifications.length > 0) {
                notificationList.innerHTML = notifications.map(notif => `
                    <a href="#" class="dropdown-item border-bottom py-2" onclick="markAsReadAndRedirect('${notif.id}', '${notif.action_url || '#'}'); return false;">
                        <div class="d-flex align-items-start">
                            <i class="bi ${notif.icon} text-${notif.color} me-2 mt-1" style="font-size: 1.1rem;"></i>
                            <div class="flex-grow-1">
                                <h6 class="mb-1" style="font-size: 0.85rem;">
                                    ${notif.title}
                                    ${!notif.read_at ? '<span class="badge bg-primary ms-1" style="font-size: 0.6rem;">Baru</span>' : ''}
                                </h6>
                                <p class="mb-1 text-muted" style="font-size: 0.8rem;">${notif.message}</p>
                                <small class="text-muted" style="font-size: 0.75rem;">
                                    <i class="bi bi-clock"></i> ${formatTimeAgo(new Date(notif.created_at))}
                                </small>
                            </div>
                        </div>
                    </a>
                `).join('');
            } else {
                notificationList.innerHTML = '<div class="text-center text-muted p-3"><small>Tidak ada notifikasi</small></div>';
            }
        })
        .catch(error => {
            console.error('Error loading notifications:', error);
            document.getElementById('notificationList').innerHTML = '<div class="text-center text-muted p-3"><small>Gagal memuat notifikasi</small></div>';
        });
}

function formatTimeAgo(date) {
    const now = new Date();
    const seconds = Math.floor((now - date) / 1000);
    
    if (seconds < 60) return 'Baru saja';
    if (seconds < 3600) return Math.floor(seconds / 60) + ' menit lalu';
    if (seconds < 86400) return Math.floor(seconds / 3600) + ' jam lalu';
    if (seconds < 604800) return Math.floor(seconds / 86400) + ' hari lalu';
    return date.toLocaleDateString('id-ID');
}

function markAsReadAndRedirect(notificationId, actionUrl) {
    // Mark as read
    fetch(`{{ url('notifications') }}/${notificationId}/mark-as-read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (actionUrl && actionUrl !== '#') {
            window.location.href = actionUrl;
        }
        loadNotifications(); // Refresh notification list
    })
    .catch(error => console.error('Error:', error));
}
</script>

<style>
.notification-dropdown {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    border: 1px solid #dee2e6;
}

.notification-list-dropdown a.dropdown-item:hover {
    background-color: #f8f9fa;
}

.notification-list-dropdown a.dropdown-item {
    transition: background-color 0.2s ease;
}

#notificationBell {
    cursor: pointer;
    transition: transform 0.2s ease;
}

#notificationBell:hover {
    transform: scale(1.15);
}
</style>
