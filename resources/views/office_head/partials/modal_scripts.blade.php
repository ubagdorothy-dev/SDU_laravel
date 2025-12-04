<script>
    // Handle "Other" option for add training modal
    document.addEventListener('DOMContentLoaded', function() {
        const addNatureSelect = document.getElementById('add_nature_of_training');
        const addOtherGroup = document.getElementById('add_other_nature_group');
        
        if (addNatureSelect && addOtherGroup) {
            function toggleAddOtherField() {
                if (addNatureSelect.value === 'Other') {
                    addOtherGroup.style.display = 'block';
                } else {
                    addOtherGroup.style.display = 'none';
                }
            }
            
            addNatureSelect.addEventListener('change', toggleAddOtherField);
            toggleAddOtherField(); // Initialize on page load
        }
        
        // Handle "Other" option for edit training modal
        const editNatureSelect = document.getElementById('edit_nature_of_training');
        const editOtherGroup = document.getElementById('edit_other_nature_group');
        
        if (editNatureSelect && editOtherGroup) {
            function toggleEditOtherField() {
                if (editNatureSelect.value === 'Other') {
                    editOtherGroup.style.display = 'block';
                } else {
                    editOtherGroup.style.display = 'none';
                }
            }
            
            editNatureSelect.addEventListener('change', toggleEditOtherField);
            toggleEditOtherField(); // Initialize on page load
        }
        
        // Handle notifications modal
        const notificationsModal = document.getElementById('notificationsModal');
        if (notificationsModal) {
            let isFetching = false;
            notificationsModal.addEventListener('show.bs.modal', function () {
                if (!isFetching) {
                    isFetching = true;
                    fetchNotifications();
                    // Reset the flag after a short delay to allow subsequent fetches
                    setTimeout(() => {
                        isFetching = false;
                    }, 1000);
                }
            });
            
            // Handle tab switching
            const sentTab = document.getElementById('sent-tab');
            console.log('Sent tab element:', sentTab);
            if (sentTab) {
                sentTab.addEventListener('shown.bs.tab', function () {
                    console.log('Sent tab shown event triggered');
                    fetchSentNotifications();
                });
            } else {
                console.log('Sent tab not found');
            }
        }
        
        // Update notification badge periodically
        updateUnreadCount();
        setInterval(updateUnreadCount, 30000); // Update every 30 seconds
    });
    
    // Fetch notifications and display them in the modal
    function fetchNotifications() {
        const notificationsList = document.getElementById('notificationsList');
        const loader = document.getElementById('notificationsLoader');
        
        console.log('fetchNotifications called');
        
        if (!notificationsList || !loader) {
            console.log('Missing elements: notificationsList=', notificationsList, 'loader=', loader);
            return;
        }
        
        // Show loading spinner
        loader.style.display = 'block';
        notificationsList.innerHTML = '';
        
        // Get the CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        console.log('CSRF Token:', csrfToken);
        
        const url = "{{ route('notifications.get') }}";
        console.log('Fetching URL:', url);
        
        fetch(url, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => {
            console.log('Notifications response status:', response.status);
            console.log('Notifications response headers:', response.headers);
            
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.status);
            }
            
            return response.json();
        })
        .then(data => {
            console.log('Notifications data:', data);
            loader.style.display = 'none';
            if (data.success) {
                renderNotifications(data.notifications);
            } else {
                notificationsList.innerHTML = '<div class="alert alert-danger">Failed to load notifications: ' + data.message + '</div>';
            }
        })
        .catch(error => {
            loader.style.display = 'none';
            console.error('Error fetching notifications:', error);
            notificationsList.innerHTML = '<div class="alert alert-danger">Failed to load notifications. Please try again.</div>';
        });
    }
    
    // Fetch sent notifications
    function fetchSentNotifications() {
        const sentNotificationsList = document.getElementById('sentNotificationsList');
        const loader = document.getElementById('sentNotificationsLoader');
        
        console.log('fetchSentNotifications called');
        
        if (!sentNotificationsList || !loader) {
            console.log('Missing elements: sentNotificationsList=', sentNotificationsList, 'loader=', loader);
            return;
        }
        
        console.log('Fetching sent notifications...');
        
        // Show loading spinner
        loader.style.display = 'block';
        sentNotificationsList.innerHTML = '';
        
        // Get the CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        console.log('CSRF Token:', csrfToken);
        
        const url = "{{ route('notifications.sent') }}";
        console.log('Fetching URL:', url);
        
        fetch(url, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => {
            console.log('Sent notifications response status:', response.status);
            console.log('Sent notifications response headers:', response.headers);
            
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.status);
            }
            
            return response.json();
        })
        .then(data => {
            console.log('Sent notifications data:', data);
            loader.style.display = 'none';
            if (data.success) {
                renderSentNotifications(data.sent_notifications);
            } else {
                sentNotificationsList.innerHTML = '<div class="alert alert-danger">Failed to load sent notifications: ' + data.message + '</div>';
            }
        })
        .catch(error => {
            loader.style.display = 'none';
            console.error('Error fetching sent notifications:', error);
            sentNotificationsList.innerHTML = '<div class="alert alert-danger">Failed to load sent notifications. Please try again. Error: ' + error.message + '</div>';
        });
    }
    
    // Render received notifications
    function renderNotifications(notifications) {
        const notificationsList = document.getElementById('notificationsList');
        if (!notificationsList) return;
        
        if (notifications.length === 0) {
            notificationsList.innerHTML = '<div class="text-center py-5"><i class="fas fa-inbox fa-3x text-muted mb-3"></i><h5>No notifications</h5><p class="text-muted">You don\'t have any notifications yet.</p></div>';
            return;
        }
        
        let html = '<div class="list-group">';
        notifications.forEach(notification => {
            const isReadClass = notification.is_read ? '' : 'list-group-item-warning';
            const senderInfo = notification.sender_name ? `<small class="text-muted">From: ${notification.sender_name} (${notification.sender_role})</small>` : '';
            
            html += `
                <div class="list-group-item ${isReadClass} mb-2 rounded-3 border-0 shadow-sm">
                    <div class="d-flex w-100 justify-content-between">
                        <h6 class="mb-1">${notification.title || 'Notification'}</h6>
                        <small>${notification.created_at}</small>
                    </div>
                    <p class="mb-1">${notification.message}</p>
                    ${senderInfo}
                </div>
            `;
        });
        html += '</div>';
        
        notificationsList.innerHTML = html;
    }
    
    // Render sent notifications
    function renderSentNotifications(sentNotifications) {
        const sentNotificationsList = document.getElementById('sentNotificationsList');
        if (!sentNotificationsList) return;
        
        if (sentNotifications.length === 0) {
            sentNotificationsList.innerHTML = '<div class="text-center py-5"><i class="fas fa-paper-plane fa-3x text-muted mb-3"></i><h5>No sent items</h5><p class="text-muted">You haven\'t sent any notifications yet.</p></div>';
            return;
        }
        
        let html = '<div class="list-group">';
        sentNotifications.forEach(notification => {
            const recipientInfo = notification.recipient_name ? `<small class="text-muted">To: ${notification.recipient_name} (${notification.recipient_role})</small>` : '';
            
            html += `
                <div class="list-group-item mb-2 rounded-3 border-0 shadow-sm">
                    <div class="d-flex w-100 justify-content-between">
                        <h6 class="mb-1">${notification.title || 'Notification'}</h6>
                        <small>${notification.created_at}</small>
                    </div>
                    <p class="mb-1">${notification.message}</p>
                    ${recipientInfo}
                </div>
            `;
        });
        html += '</div>';
        
        sentNotificationsList.innerHTML = html;
    }
    
    // Initialize notification action buttons (mark as read, delete, etc.)
    function initializeNotificationActions() {
        // Mark all as read button
        const markAllReadBtn = document.getElementById('markAllReadBtn');
        if (markAllReadBtn) {
            markAllReadBtn.addEventListener('click', function() {
                markAllNotificationsAsRead();
            });
        }
        
        // Delete all button
        const deleteAllBtn = document.getElementById('deleteAllBtn');
        if (deleteAllBtn) {
            deleteAllBtn.addEventListener('click', function() {
                deleteAllNotifications();
            });
        }
        
        // Individual notification actions would be handled here if needed
    }
    
    // Mark all notifications as read
    function markAllNotificationsAsRead() {
        fetch("{{ route('notifications.mark_read') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ ids: 'all' })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Refresh notifications
                fetchNotifications();
                // Update badge count
                updateUnreadCount();
            } else {
                console.error('Failed to mark all as read:', data.message);
            }
        })
        .catch(error => {
            console.error('Error marking all as read:', error);
        });
    }
    
    // Delete all notifications
    function deleteAllNotifications() {
        if (!confirm('Are you sure you want to delete all notifications?')) {
            return;
        }
        
        fetch("{{ route('notifications.delete') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ ids: 'all' })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Refresh notifications
                fetchNotifications();
                // Update badge count
                updateUnreadCount();
            } else {
                console.error('Failed to delete all notifications:', data.message);
            }
        })
        .catch(error => {
            console.error('Error deleting all notifications:', error);
        });
    }
    
    // Update unread notification count badge
    function updateUnreadCount() {
        fetch("{{ route('notifications.unread_count') }}", {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            const badge = document.getElementById('notificationBadge');
            if (badge) {
                if (data.count > 0) {
                    badge.textContent = data.count;
                    badge.style.display = 'block';
                } else {
                    badge.style.display = 'none';
                }
            }
        })
        .catch(error => {
            console.error('Error fetching unread count:', error);
        });
    }
</script>