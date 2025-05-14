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

     <!-- Sign  -->
    <button class="sign-out-btn no-print" id="signOutBtn">
        <i class="fas fa-sign-out-alt"></i> Sign Out
    </button>

    <main>
        
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
    </script>
</body>
</html>