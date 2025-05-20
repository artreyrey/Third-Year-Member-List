<?php
require_once 'features.php';
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CE 3rd Year Members</title>
    <link rel="stylesheet" href="membersPage.css">
</head>
<body>

<nav class="navbar">
  <div class="navbar-left">Tricom</div>
  <ul class="nav-list">
    <li><a href="home.php" class="nav-link">Home</a></li>
    <li><a href="membersPage.php" class="nav-link active">Members</a></li>
    <li><a href="profile.php" class="nav-link">Profile</a></li>
  </ul>
  <button class="sign-out-btn no-print" id="signOutBtn">
    <i class="fas fa-sign-out-alt"></i> Sign Out
  </button>
</nav>

    <main>
         <!-- Member View individualy -->
    <div id="memberViewModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" style="color:rgb(243, 115, 41)">Member Information</h2>
                <button class="close-modal">&times;</button>
            </div>
            <div class="profile-view">
                <div class="profile-info-left">
                    <div class="info-row">
                        <div class="info-label">Student No:</div>
                        <div class="info-value" id="viewStudentNo">20230001</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Name:</div>
                        <div class="info-value" id="viewName"></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Age:</div>
                        <div class="info-value" id="viewAge"></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Gender:</div>
                        <div class="info-value" id="viewGender"></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Contact:</div>
                        <div class="info-value" id="viewContact"></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Role:</div>
                        <div class="info-value" id="viewRole"></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Address:</div>
                        <div class="info-value" id="viewAddress"></div>
                    </div>
                </div>
                <div class="profile-picture-right">
                    <img class="profile-picture-solo" id="viewProfilePicture" src="https://via.placeholder.com/150" alt="Profile Picture">
                </div>
            </div>
            <div class="modal-actions">
                <button class="print-btn-modal" id="printMemberBtn">
                    <i class="fas fa-print"></i> Print
                </button>
            </div>
        </div>
    </div>

        <!-- Add member form/ edit form -->
        <div id="addMemberForm" class="profile-content" style="display: none;">
            <div class="form-header">
                <h2>Add New Member</h2>
            </div>
            
            <div class="profile-picture-container">
                <img src="https://via.placeholder.com/150" alt="Profile Picture Preview" id="profilePicturePreview" class="profile-picture-preview">
                <label for="profilePicture" class="picture-upload-btn">
                    <i class="fas fa-camera"></i> Upload Photo
                </label>
                <input type="file" id="profilePicture" accept="image/*" style="display: none;">
            </div>

            <div class="profile-info">
                <div class="form-group">
                    <label for="studentNo">Student No:</label>
                    <input type="text" id="studentNo" required>

                    <label for="firstName">First Name:</label>
                    <input type="text" id="firstName" required>

                    <label for="middleInitial">MI:</label>
                    <input type="text" id="middleInitial">

                    <label for="lastName">Last Name:</label>
                    <input type="text" id="lastName" required>

                    <label for="age">Age:</label>
                    <input type="number" id="age" min="16" max="99" required>

                    <label for="gender">Gender:</label>
                    <select id="gender" required>
                        <option value="">Select</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>

                    <label for="contact">Contact:</label>
                    <input type="tel" id="contact" placeholder="Optional">
                </div>

                <div class="form-group">
                    <label for="role">Role:</label>
                    <select id="role" required>
                        <option value="Student">Student</option>
                        <option value="Officer">Officer</option>
                    </select>
                    
                    <label for="address">Address:</label>
                    <textarea id="address" rows="3" placeholder="Optional"></textarea>
                </div>

                <div class="form-buttons">
                    <button id="cancelAddBtn" class="cancel-btn"><i class="fas fa-times"></i> Cancel</button>
                    <button id="submitAddBtn" class="save-btn"><i class="fas fa-save"></i> Save Member</button>
                </div>
            </div>
        </div>
            
        <!-- Members Display Section -->

        <section id="membersSection" class="members">
            <div class="member-padding">
                <div class="member-header">
                   <h1 class="member-title">BSCpE - 3rd Year</h1>
                        <div class="search-group" style="margin-top: 10px; display: flex; align-items: center; gap: 8px;">
                            <label for="searchInput" style="margin-right: 8px;">Search by Name:</label>
                            <input type="text" id="searchInput" placeholder="Enter name..." autocomplete="off" style="flex: 1; padding: 6px 10px; font-size: 16px;" />
                                <button id="searchBtn" class="action-btn no-print" title="Search" style="padding: 6px 12px; font-size: 16px; cursor: pointer;">
                                <i class="fas fa-search"></i>
                                </button>
                        </div>


                    <div class="filter-controls">
                        <div class="filter-group">
                            <label for="genderFilter">Gender:</label>
                            <select id="genderFilter" class="filter-select">
                                <option value="All" selected>All</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>

                        <div class="filter-group">
                            <label for="roleFilter">Role:</label>
                            <select id="roleFilter" class="filter-select">
                                <option value="All" selected>All</option>
                                <option value="Student">Student</option>
                                <option value="Officer">Officer</option>
                            </select>
                        </div>

                        <div class="action-buttons">
                            <button id="printButton" class="action-btn print-btn no-print" title="Print List">
                                <i class="fa-solid fa-print"></i>
                            </button>
                            <button id="addButton" class="action-btn add-btn no-print" title="Add Member">
                                <i class="fa-solid fa-user-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Members Display Area -->
                <div class="members-display">
                    <div id="membersList" class="members-list">
                        <!-- Sample member (will be populated dynamically) -->
                        <div class="member-item">
                                 <div id="membersListContainer">
                                    <?php echo displayMembers(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>


    <!-- Latest Font Awesome (v6) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        // Show add member form
        $('#addButton').click(function() {
            $('#membersSection').hide();
            $('#addMemberForm').show();
        });
    
        // Hide add member form
        $('#cancelAddBtn').click(function() {
            $('#addMemberForm').hide();
            $('#membersSection').show();
            // Balik sa dating state
            $('#addMemberForm')[0].reset();
            $('#profilePicturePreview').attr('src', 'https://via.placeholder.com/150');
        });
    
        // Handle profile picture preview
        $('#profilePicture').change(function(e) {
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#profilePicturePreview').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
            }
        });
    
        // Submit add member form
        $('#submitAddBtn').click(function() {
            var formData = new FormData();
            formData.append('studentNo', $('#studentNo').val());
            formData.append('firstName', $('#firstName').val());
            formData.append('middleInitial', $('#middleInitial').val());
            formData.append('lastName', $('#lastName').val());
            formData.append('age', $('#age').val());
            formData.append('gender', $('#gender').val());
            formData.append('contact', $('#contact').val());
            formData.append('address', $('#address').val());
            formData.append('role', $('#role').val());
            
            // Add profile picture
            if ($('#profilePicture')[0].files[0]) {
                formData.append('profilePicture', $('#profilePicture')[0].files[0]);
            }
            
            $.ajax({
                url: 'add_member.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response === 'success') {
                        // Reload the page to show updated member list
                        location.reload();
                    } else {
                        alert('Error adding member: ' + response);
                    }
                },
                error: function(xhr, status, error) {
                    alert('Error: ' + error);
                }
            });
        });

// View member functionality - FIXED
$(document).on('click', '.view-btn', function() {
    var memberId = $(this).data('id');
    
    $.ajax({
        url: 'get_member.php',
        type: 'POST',
        data: { id: memberId },
        success: function(response) {
            try {
                var member = JSON.parse(response);
                
                // Populate the modal with member data
                $('#viewStudentNo').text(member.student_id || 'N/A');
                
                // Use the combined name field directly
                $('#viewName').text(member.name || 'N/A');
                
                $('#viewAge').text(member.age || 'N/A');
                $('#viewGender').text(member.gender || 'N/A');
                $('#viewContact').text(member.contact_number || 'N/A');
                $('#viewRole').text(member.role || 'N/A');
                $('#viewAddress').text(member.address || 'N/A');
                
                // Set profile picture
                var profilePic = member.profile_picture || 'https://via.placeholder.com/150';
                $('#viewProfilePicture').attr('src', profilePic);
                
                $('#memberViewModal').show();
            } catch (e) {
                console.error('Error parsing member data:', e);
                alert('Error displaying member information.');
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX error:', error);
            alert('Error loading member information.');
        }
    });
});
        
        // Close modal
        $('.close-modal').click(function() {
            $('#memberViewModal').hide();
        });
        
        // Close modal when clicking outside
        $(window).click(function(event) {
            if ($(event.target).is('#memberViewModal')) {
                $('#memberViewModal').hide();
            }
        });
        
        // Print member information
        $('#printMemberBtn').click(function() {
            var memberId = $(this).data('member-id');
            var printWindow = window.open('', '', 'width=800,height=600');
            
            // Get the member data to print
            var memberData = {
                studentNo: $('#viewStudentNo').text(),
                name: $('#viewName').text(),
                age: $('#viewAge').text(),
                gender: $('#viewGender').text(),
                contact: $('#viewContact').text(),
                role: $('#viewRole').text(),
                address: $('#viewAddress').text(),
                photo: $('#viewProfilePicture').attr('src')
            };
            
            printWindow.document.write('<html><head><title>Member Information</title>');
            printWindow.document.write('<style>');
            printWindow.document.write(`
                body { font-family: Arial, sans-serif; margin: 20px; }
                .print-header { text-align: center; margin-bottom: 20px; }
                .print-title { color: #2c3e50; margin-bottom: 5px; }
                .print-date { color: #7f8c8d; font-size: 14px; margin-bottom: 20px; }
                .profile-print { display: flex; gap: 30px; margin-bottom: 20px; }
                .profile-info-left { flex: 1; }
                .profile-picture-right { width: 150px; text-align: center; }
                .profile-picture-right img { width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 2px solid #ff7300; }
                .info-row { display: flex; margin-bottom: 12px; }
                .info-label { font-weight: bold; width: 100px; color: #34495e; }
                .info-value { flex: 1; color: #2c3e50; }
                .print-footer { margin-top: 30px; text-align: right; font-size: 12px; color: #7f8c8d; }
            `);
            printWindow.document.write('</style>');
            printWindow.document.write('</head><body>');
            
            printWindow.document.write('<div class="print-header">');
            printWindow.document.write('<h2 class="print-title">CE 3rd Year Member Information</h2>');
            printWindow.document.write('<div class="print-date">Printed on: ' + new Date().toLocaleDateString() + '</div>');
            printWindow.document.write('</div>');
            
            printWindow.document.write('<div class="profile-print">');
            printWindow.document.write('<div class="profile-info-left">');
            printWindow.document.write('<div class="info-row"><div class="info-label">Student No:</div><div class="info-value">' + memberData.studentNo + '</div></div>');
            printWindow.document.write('<div class="info-row"><div class="info-label">Name:</div><div class="info-value">' + memberData.name + '</div></div>');
            printWindow.document.write('<div class="info-row"><div class="info-label">Age:</div><div class="info-value">' + memberData.age + '</div></div>');
            printWindow.document.write('<div class="info-row"><div class="info-label">Gender:</div><div class="info-value">' + memberData.gender + '</div></div>');
            printWindow.document.write('<div class="info-row"><div class="info-label">Contact:</div><div class="info-value">' + memberData.contact + '</div></div>');
            printWindow.document.write('<div class="info-row"><div class="info-label">Role:</div><div class="info-value">' + memberData.role + '</div></div>');
            printWindow.document.write('<div class="info-row"><div class="info-label">Address:</div><div class="info-value">' + memberData.address + '</div></div>');
            printWindow.document.write('</div>');
            
            printWindow.document.write('<div class="profile-picture-right">');
            printWindow.document.write('<img src="' + memberData.photo + '" alt="Profile Picture">');
            printWindow.document.write('</div>');
            printWindow.document.write('</div>');
            
            printWindow.document.write('<div class="print-footer">Tricom Member Information System</div>');
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            
            // Wait for content to load before printing
            printWindow.onload = function() {
                setTimeout(function() {
                    printWindow.print();
                    printWindow.close();
                }, 500);
            };
        });
    });

    // Edit member functionality
$(document).on('click', '.edit-btn', function() {
    var memberId = $(this).data('id');
    
    $.ajax({
        url: 'get_member.php',
        type: 'POST',
        data: { id: memberId },
        success: function(response) {
            var member = JSON.parse(response);
            
            // Populate the form with member data
            $('#studentNo').val(member.student_id);
            
            // Split the name into parts
            var nameParts = member.name.split(', ');
            var firstName = nameParts[1] ? nameParts[1].split(' ')[0] : '';
            var middleInitial = nameParts[1] && nameParts[1].split(' ').length > 1 ? 
                nameParts[1].split(' ')[1].replace('.', '') : '';
            var lastName = nameParts[0] || '';
            
            $('#firstName').val(firstName);
            $('#middleInitial').val(middleInitial);
            $('#lastName').val(lastName);
            $('#age').val(member.age);
            $('#gender').val(member.gender);
            $('#contact').val(member.contact_number);
            $('#address').val(member.address);
            $('#role').val(member.role);
            $('#profilePicturePreview').attr('src', member.profile_picture);
            
            // Store the member ID in a hidden field or data attribute
            $('#submitAddBtn').data('edit-id', memberId);
            $('#submitAddBtn').text('Update Member');
            
            // Show the form in edit mode
            $('#membersSection').hide();
            $('#addMemberForm').show();
        }
    });
});

// Delete member functionality
$(document).on('click', '.delete-btn', function() {
    if (confirm('Are you sure you want to delete this member?')) {
        var memberId = $(this).data('id');
        
        $.ajax({
            url: 'delete_member.php',
            type: 'POST',
            data: { id: memberId },
            success: function(response) {
                if (response === 'success') {
                    location.reload();
                } else {
                    alert('Error deleting member: ' + response);
                }
            }
        });
    }
});

// Modify your existing form submission to handle both add and edit
$('#submitAddBtn').click(function() {
    var formData = new FormData();
    var memberId = $(this).data('edit-id');
    
    formData.append('studentNo', $('#studentNo').val());
    formData.append('firstName', $('#firstName').val());
    formData.append('middleInitial', $('#middleInitial').val());
    formData.append('lastName', $('#lastName').val());
    formData.append('age', $('#age').val());
    formData.append('gender', $('#gender').val());
    formData.append('contact', $('#contact').val());
    formData.append('address', $('#address').val());
    formData.append('role', $('#role').val());
    
    // Add profile picture if selected
    if ($('#profilePicture')[0].files[0]) {
        formData.append('profilePicture', $('#profilePicture')[0].files[0]);
    }
    
    var url = memberId ? 'update_member.php' : 'add_member.php';
    if (memberId) {
        formData.append('id', memberId);
        formData.append('existingProfilePicture', $('#profilePicturePreview').attr('src'));
    }
    
    $.ajax({
        url: url,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response === 'success') {
                location.reload();
            } else {
                alert('Error: ' + response);
            }
        }
    });
});

// Sign Out Button functionality
$('#signOutBtn').click(function() {
    $.ajax({
        url: 'logout.php',
        type: 'POST',
        success: function() {
            window.location.href = 'adminLogin.php';
        },
        error: function() {
            window.location.href = 'adminLogin.php';
        }
    });
});

// Print Button 
$('#printButton').click(function() {
    // Clone the members list
    var printContent = $('#membersList').clone();
    
    // Remove action buttons 
    printContent.find('.cell-actions').remove();
    // Remove action header
    printContent.find('.header-actions').remove();
    
    // Create a new window for printing
    var printWindow = window.open('', '', 'width=800,height=600');
    printWindow.document.write('<html><head><title>CE 3rd Year Members</title>');
    
    // Include basic styling for printing
    printWindow.document.write('<style>');
    printWindow.document.write(`
        body { font-family: Arial, sans-serif; margin: 20px; }
        .members-table { width: 100%; border-collapse: collapse; }
        .table-header, .table-row { display: flex; border-bottom: 1px solid #ddd; }
        .table-header div, .table-row div { padding: 8px; flex: 1; }
        .table-header { font-weight: bold; background-color: #f2f2f2; }
        .member-photo { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; }
        @page { size: auto; margin: 5mm; }
        @media print {
            .no-print { display: none !important; }
        }
    `);
    printWindow.document.write('</style>');
    printWindow.document.write('</head><body>');
    printWindow.document.write('<h1>CE 3rd Year Members</h1>');
    printWindow.document.write('<p>Printed on: ' + new Date().toLocaleDateString() + '</p>');
    printWindow.document.write(printContent.html());
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    
    // Wait for content to load before printing
    printWindow.onload = function() {
        setTimeout(function() {
            printWindow.print();
            printWindow.close();
        }, 500);
    };
});
// Function to apply filters and refresh member list
function applyFilters() {
    var genderFilter = $('#genderFilter').val();
    var roleFilter = $('#roleFilter').val();
    
    $.ajax({
        url: 'filter_members.php',
        type: 'POST',
        data: {
            gender: genderFilter,
            role: roleFilter
        },
        success: function(response) {
            $('#membersList').html(response);
        },
        error: function(xhr, status, error) {
            alert('Error applying filters: ' + error);
        }
    });
}

// Apply filters when gender selection changes
$('#genderFilter').change(function() {
    applyFilters();
});

// Apply filters when role selection changes
$('#roleFilter').change(function() {
    applyFilters();
});

// Initial load with default filters
applyFilters();

// Search by name functionality
$('#searchBtn').click(function() {
    var searchTerm = $('#searchInput').val().trim();

    if (searchTerm === '') {
        // If empty, reload with default filters
        applyFilters();
        return;
    }

    $.ajax({
        url: 'search_members.php',
        type: 'POST',
        data: { search: searchTerm },
        success: function(response) {
            $('#membersList').html(response);
        },
        error: function(xhr, status, error) {
            alert('Error searching members: ' + error);
        }
    });
});

// Optionally, also trigger search on pressing Enter key in the search input
$('#searchInput').on('keypress', function(e) {
    if (e.which === 13) { // Enter key pressed
        $('#searchBtn').click();
    }
});

    </script>
</body>
</html>

