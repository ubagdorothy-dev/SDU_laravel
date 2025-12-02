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
    
    // Initialize profile modal
    function initializeProfileModal() {
        // Add any profile modal initialization if needed
        const profileModal = document.getElementById('profileModal');
        if (profileModal) {
            profileModal.addEventListener('shown.bs.modal', function () {
                // Any actions when profile modal is shown
                console.log('Profile modal is now visible');
            });
        }
    }
    
    // Handle profile form submission
    function handleProfileFormSubmission() {
        const saveProfileBtn = document.getElementById('saveProfileBtn');
        const profileForm = document.getElementById('profileEditForm');
        
        if (saveProfileBtn && profileForm) {
            saveProfileBtn.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Show loading state
                const originalText = saveProfileBtn.innerHTML;
                saveProfileBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Saving...';
                saveProfileBtn.disabled = true;
                
                // Get form data
                const formData = new FormData(profileForm);
                
                // Submit form via AJAX
                fetch(profileForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => Promise.reject(err));
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Show success message
                        alert('Profile updated successfully!');
                        // Close the modal
                        const profileModal = bootstrap.Modal.getInstance(document.getElementById('profileModal'));
                        profileModal.hide();
                        // Optionally reload the page to show updated data
                        location.reload();
                    } else {
                        // Show error message
                        let errorMessage = 'Error updating profile: ' + (data.message || 'Unknown error');
                        if (data.errors) {
                            errorMessage += '\n\nValidation errors:\n';
                            for (const field in data.errors) {
                                errorMessage += `- ${field}: ${data.errors[field].join(', ')}\n`;
                            }
                        }
                        alert(errorMessage);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error updating profile. Please try again.');
                })
                .finally(() => {
                    // Reset button state
                    saveProfileBtn.innerHTML = originalText;
                    saveProfileBtn.disabled = false;
                });
            });
        }
    }
    
    // Program options based on office (for profile modal)
    const profileProgramOptions = {
        'ACCA': [
            'Artejos program',
            'Aguila program (with 4 student led-organization teatro ateneo, ateneo blue vigors, ateneo concert band, ateneo glee club)'
        ],
        'ACES': [
            'ALERTO',
            'Inigo program',
            'Ecological solid waste management program',
            'Adopt-a-watershed program'
        ],
        'ACLG': [
            'Leadership and governance program',
            'Engaged citizenship & democracy building program'
        ],
        'ALTEC': [
            'SUGPAT PROGRAM',
            'Emerge program',
            'Teach anywhere program'
        ],
        'APC': [
            'Peace education program',
            'Peace advocacy program',
            'Interreligious dialogue program'
        ],
        'CCES': [
            'Health program',
            'Livelihood program',
            'Education program'
        ]
    };
    
    // Populate programs based on office in profile modal
    function populateProfilePrograms(officeCode) {
        const programSelect = document.getElementById('profile_program');
        if (programSelect) {
            programSelect.innerHTML = '<option value="">Select Program</option>';
            
            if (officeCode && profileProgramOptions[officeCode]) {
                profileProgramOptions[officeCode].forEach(function(program) {
                    const option = document.createElement('option');
                    option.value = program;
                    option.text = program;
                    programSelect.appendChild(option);
                });
            }
        }
    }
    
    // Handle degree attained dropdown change in profile modal
    function handleProfileDegreeSelection() {
        const degreeSelect = document.getElementById('profile_degree_attained');
        const otherDegreeGroup = document.getElementById('profile_other_degree_group');
        
        if (degreeSelect && otherDegreeGroup) {
            function toggleOtherDegreeField() {
                if (degreeSelect.value === 'Other') {
                    otherDegreeGroup.style.display = 'block';
                } else {
                    otherDegreeGroup.style.display = 'none';
                }
            }
            
            degreeSelect.addEventListener('change', toggleOtherDegreeField);
            toggleOtherDegreeField(); // Initialize on page load
        }
    }
    
    // Initialize profile modal with all functionality
    function initializeProfileModal() {
        // Add any profile modal initialization if needed
        const profileModal = document.getElementById('profileModal');
        if (profileModal) {
            profileModal.addEventListener('shown.bs.modal', function () {
                // Any actions when profile modal is shown
                console.log('Profile modal is now visible');
                handleProfileDegreeSelection();
                handleProfileFormSubmission();
                
                // Populate programs based on office
                const profileModalElement = document.getElementById('profileModal');
                const officeCode = profileModalElement.getAttribute('data-office-code');
                if (officeCode) {
                    populateProfilePrograms(officeCode);
                    
                    // Set the selected program if it exists
                    const programValue = profileModalElement.getAttribute('data-program');
                    const programSelect = document.getElementById('profile_program');
                    if (programSelect && programValue) {
                        programSelect.value = programValue;
                    }
                }
            });
        }
    }
    
    // Call initializeProfileModal when the page loads
    document.addEventListener('DOMContentLoaded', function() {
        initializeProfileModal();
    });
</script>