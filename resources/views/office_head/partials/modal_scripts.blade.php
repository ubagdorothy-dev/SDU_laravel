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
            notificationsModal.addEventListener('show.bs.modal', function () {
                fetchNotifications();
            });
        }
        
        // Update notification badge periodically
        updateUnreadCount();
        setInterval(updateUnreadCount, 30000); // Update every 30 seconds
    });
    
    // Fetch notifications and display them in the modal
    function fetchNotifications() {
        const contentDiv = document.getElementById('notificationsContent');
        if (!contentDiv) return;
        
        // Show loading spinner
        contentDiv.innerHTML = '<div class="text-center py-4"><div class="spinner-border" role="status"></div></div>';
        
        fetch("{{ route('notifications.get') }}", {
            method: 'GET',
            headers: {
                'Accept': 'text/html',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            contentDiv.innerHTML = html;
            
            // Add event listeners for notification actions
            initializeNotificationActions();
        })
        .catch(error => {
            console.error('Error fetching notifications:', error);
            contentDiv.innerHTML = '<div class="alert alert-danger">Failed to load notifications. Please try again.</div>';
        });
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