<!-- Notifications Modal -->
<div class="modal fade" id="notificationsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-bell me-2"></i>Notifications</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="notificationsContent">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <button id="markAllReadBtn" class="btn btn-sm btn-outline-primary me-2">
                            <i class="fas fa-check-double me-1"></i>Mark All Read
                        </button>
                        <button id="deleteAllBtn" class="btn btn-sm btn-outline-danger">
                            <i class="fas fa-trash-alt me-1"></i>Delete All
                        </button>
                    </div>
                 
                </div>
                <div class="text-center py-4" id="notificationsLoader">
                    <div class="spinner-border" role="status"></div>
                </div>
                <div id="notificationsList"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>   
document.addEventListener('DOMContentLoaded', function () {
        const notificationsModal = document.getElementById('notificationsModal');
        if (notificationsModal) {
            notificationsModal.addEventListener('show.bs.modal', function () {
                fetchOfficeNotifications();
            });
        }

        // Update badge periodically
        updateOfficeUnreadCount();
        setInterval(updateOfficeUnreadCount, 30000);
    });

    function fetchOfficeNotifications() {
        const loaderDiv = document.getElementById('notificationsLoader');
        const listDiv = document.getElementById('notificationsList');
        if (!loaderDiv || !listDiv) return;
        loaderDiv.style.display = 'block';
        listDiv.innerHTML = '';

        const url = "{{ route('notifications.get') }}";
        fetch(url, { method: 'GET', headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.json())
            .then(data => {
                loaderDiv.style.display = 'none';
                if (data.success && data.notifications) {
                    // filter out notifications sent by office_head
                    const filtered = data.notifications.filter(n => (n.sender_role || '').toLowerCase() !== 'office_head');
                    renderOfficeNotifications(filtered);
                } else {
                    listDiv.innerHTML = '<div class="alert alert-danger">Failed to load notifications. Please try again.</div>';
                }
                initializeOfficeNotificationActions();
            })
            .catch(err => {
                console.error('Error fetching notifications:', err);
                loaderDiv.style.display = 'none';
                listDiv.innerHTML = '<div class="alert alert-danger">Failed to load notifications. Please try again.</div>';
            });
    }

    function renderOfficeNotifications(notifications) {
        const listDiv = document.getElementById('notificationsList');
        if (!listDiv) return;
        if (!notifications || notifications.length === 0) {
            listDiv.innerHTML = `<div class="text-center py-5"><i class="fas fa-inbox fa-3x mb-3 text-muted"></i><h5>No notifications</h5><p class="text-muted">You don't have any notifications at the moment.</p></div>`;
            return;
        }
        // Deduplicate notifications by id in case multiple fetches returned overlapping results
        const unique = {};
        const deduped = [];
        notifications.forEach(n => {
            if (!n || !n.id) return;
            if (!unique[n.id]) { unique[n.id] = true; deduped.push(n); }
        });

        let html = '<div class="list-group">';
        deduped.forEach(n => {
            const isUnreadClass = !n.is_read ? 'list-group-item-warning unread' : '';
            const unreadIndicator = !n.is_read ? '<span class="badge bg-warning me-2">NEW</span>' : '';
            let senderInfo = '';
            if (n.sender_name && n.sender_role) senderInfo = `<small class="text-muted d-block mb-1">From: ${n.sender_name} (${n.sender_role})</small>`;
            html += `<div class="list-group-item ${isUnreadClass}" data-notification-id="${n.id}"><div class="d-flex w-100 justify-content-between"><h6 class="mb-1">${unreadIndicator}${n.title || 'Notification'}</h6><small class="text-muted">${timeAgo(new Date(n.created_at))}</small></div>${senderInfo}<p class="mb-1">${n.message || ''}</p></div>`;
        });
        html += '</div>';
        listDiv.innerHTML = html;
    }

    // Helper function to format time ago
    function timeAgo(date) {
        const seconds = Math.floor((new Date() - date) / 1000);
        let interval = Math.floor(seconds / 31536000);
        if (interval > 1) return interval + " years ago";
        interval = Math.floor(seconds / 2592000);
        if (interval > 1) return interval + " months ago";
        interval = Math.floor(seconds / 86400);
        if (interval > 1) return interval + " days ago";
        interval = Math.floor(seconds / 3600);
        if (interval > 1) return interval + " hours ago";
        interval = Math.floor(seconds / 60);
        if (interval > 1) return interval + " minutes ago";
        return Math.floor(seconds) + " seconds ago";
    }

    function initializeOfficeNotificationActions() {
        const markAllReadBtn = document.getElementById('markAllReadBtn');
        if (markAllReadBtn) markAllReadBtn.addEventListener('click', markAllOfficeNotificationsAsRead);
        const deleteAllBtn = document.getElementById('deleteAllBtn');
        if (deleteAllBtn) deleteAllBtn.addEventListener('click', deleteAllOfficeNotifications);
    }

    function markAllOfficeNotificationsAsRead() {
        fetch("{{ route('notifications.mark_read') }}", { method: 'POST', headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), 'Accept':'application/json' }, body: JSON.stringify({ ids: 'all' }) })
            .then(r => r.json()).then(data => { if (data.success) { fetchOfficeNotifications(); updateOfficeUnreadCount(); } else console.error(data); }).catch(console.error);
    }

    function deleteAllOfficeNotifications() {
        if (!confirm('Are you sure you want to delete all notifications?')) return;
        fetch("{{ route('notifications.delete') }}", { method: 'POST', headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), 'Accept':'application/json' }, body: JSON.stringify({ ids: 'all' }) })
            .then(r => r.json()).then(data => { if (data.success) { fetchOfficeNotifications(); updateOfficeUnreadCount(); } else console.error(data); }).catch(console.error);
    }

    function updateOfficeUnreadCount() {
        // fetch all notifications and count unread excluding office_head senders
        fetch("{{ route('notifications.get') }}", { method: 'GET', headers: { 'Accept':'application/json', 'X-Requested-With':'XMLHttpRequest' } })
            .then(r => r.json())
            .then(data => {
                const badge = document.getElementById('notificationBadgeOffice');
                if (!badge) return;
                if (data.success && data.notifications) {
                    // Deduplicate by id first to avoid double-counting
                    const seen = {};
                    const unique = [];
                    data.notifications.forEach(n => { if (n && n.id && !seen[n.id]) { seen[n.id] = true; unique.push(n); } });
                    const count = unique.filter(n => !n.is_read && (n.sender_role || '').toLowerCase() !== 'office_head').length;
                    if (count > 0) { badge.textContent = count; badge.style.display = 'block'; } else { badge.style.display = 'none'; }
                }
            }).catch(err => console.error('Error updating unread count:', err));
    }
</script>