<?php
// Database connection function
function connectDB() {
    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "cethirdyearmembers";

    $conn = new mysqli($host, $username, $password, $database, 3309);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

session_start();

// Initialize variables
$username = '';
$profile_picture = 'https://via.placeholder.com/150';
$error = '';
$success = '';

// Get admin data from database
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $conn = connectDB();
    $stmt = $conn->prepare("SELECT username, password, profilePicture FROM admin WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $adminData = $result->fetch_assoc();
        $profile_picture = !empty($adminData['profilePicture']) ? $adminData['profilePicture'] : $profile_picture;
        $current_password = $adminData['password'];
    } else {
        $error = "Admin user not found!";
    }
    $stmt->close();
    $conn->close();
} else {
    header("Location: adminLogin.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_username = $_POST['username'] ?? $username;
    $new_password = $_POST['password'] ?? '';
    
    // Store old picture path before potential update
    $old_picture = $profile_picture;
    
    // Handle file upload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "uploads/";
        
        // Create uploads directory if it doesn't exist
        if (!file_exists($target_dir)) {
            if (!mkdir($target_dir, 0755, true)) {
                $error = "Failed to create upload directory";
            }
        }
        
        if (empty($error)) {
            $file_ext = strtolower(pathinfo($_FILES["profile_picture"]["name"], PATHINFO_EXTENSION));
            $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
            
            if (in_array($file_ext, $allowed_ext)) {
                // Generate unique filename
                $file_name = uniqid('img_') . '.' . $file_ext;
                $target_file = $target_dir . $file_name;
                
                // Check file size (max 2MB)
                if ($_FILES["profile_picture"]["size"] > 2000000) {
                    $error = "File is too large (max 2MB allowed)";
                } else {
                    if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
                        $profile_picture = $target_file;
                    } else {
                        $error = "Sorry, there was an error uploading your file.";
                    }
                }
            } else {
                $error = "Only JPG, JPEG, PNG & GIF files are allowed.";
            }
        }
    }
    
    // Update database if no errors
    if (empty($error)) {
        $conn = connectDB();
        
        try {
            if (!empty($new_password) && $new_password !== '********') {
                $stmt = $conn->prepare("UPDATE admin SET username = ?, password = ?, profilePicture = ? WHERE username = ?");
                $stmt->bind_param("ssss", $new_username, $new_password, $profile_picture, $username);
            } else {
                $stmt = $conn->prepare("UPDATE admin SET username = ?, profilePicture = ? WHERE username = ?");
                $stmt->bind_param("sss", $new_username, $profile_picture, $username);
            }
            
            if ($stmt->execute()) {
                // Delete old picture only after successful update and if it's not the default
                if ($old_picture !== 'https://via.placeholder.com/150' && file_exists($old_picture)) {
                    unlink($old_picture);
                }
                
                $success = "Profile updated successfully!";
                $_SESSION['username'] = $new_username;
                $username = $new_username;
                
                header("Location: profile.php");
                exit();
            } else {
                $error = "Error updating profile: " . $conn->error;
            }
        } catch (Exception $e) {
            $error = "Database error: " . $e->getMessage();
        } finally {
            $stmt->close();
            $conn->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - CE 3rd Year</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
    :root {
        --orange: #ff7300;
        --gray: #5c5c5c;
        --light-gray: #e0e0e0;
        --white: #ffffff;
        --cream: #f5f5f5;
        --transition: all 0.3s ease;
    }
    
    body {
        font-family: 'Poppins', sans-serif;
        background-color: var(--cream);
        margin: 0;
        padding: 0;
    }
    
    .navbar {
        background-color: var(--orange);
        padding: 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: white;
        transition: var(--transition);
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .navbar:hover {
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }
    
    .nav-list {
        display: flex;
        list-style: none;
        gap: 1.5rem;
        margin: 0;
        padding: 0;
    }
    
    .nav-link {
        color: white;
        text-decoration: none;
        font-weight: 500;
        transition: var(--transition);
        position: relative;
    }
    
    .nav-link:after {
        content: '';
        position: absolute;
        width: 0;
        height: 2px;
        bottom: -4px;
        left: 0;
        background-color: white;
        transition: var(--transition);
    }
    
    .nav-link:hover:after {
        width: 100%;
    }
    
    .nav-link.active {
        font-weight: 600;
    }
    
    .nav-link.active:after {
        width: 100%;
    }
    
    .sign-out-btn {
        background-color: transparent;
        border: 1px solid white;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 4px;
        cursor: pointer;
        transition: var(--transition);
    }
    
    .sign-out-btn:hover {
        background-color: rgba(255,255,255,0.1);
        transform: translateY(-2px);
    }
    
    main {
        max-width: 800px;
        margin: 2rem auto;
        padding: 1rem;
        animation: fadeIn 0.5s ease-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .profile-container {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        padding: 2rem;
        display: flex;
        gap: 2rem;
        transition: var(--transition);
    }
    
    .profile-container:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.15);
        transform: translateY(-3px);
    }
    
    .profile-picture-section {
        flex: 0 0 200px;
        text-align: center;
    }
    
    .profile-picture {
        width: 180px;
        height: 180px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid var(--orange);
        margin-bottom: 1rem;
        transition: var(--transition);
    }
    
    .profile-picture:hover {
        transform: scale(1.03);
        box-shadow: 0 5px 15px rgba(255,115,0,0.3);
    }
    
    .change-photo-btn {
        display: inline-block;
        background-color: var(--orange);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.9rem;
        transition: var(--transition);
    }
    
    .change-photo-btn:hover {
        background-color: #e66900;
        transform: translateY(-2px);
    }
    
    .profile-details {
        flex: 1;
    }
    
    .profile-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    
    .profile-title {
        color: var(--orange);
        margin: 0;
    }
    
    .edit-btn {
        background-color: var(--orange);
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 4px;
        cursor: pointer;
        font-weight: 500;
        transition: var(--transition);
    }
    
    .edit-btn:hover {
        background-color: #e66900;
        transform: translateY(-2px);
    }
    
    .form-group {
        margin-bottom: 1.2rem;
    }
    
    label {
        display: block;
        margin-bottom: 0.5rem;
        color: var(--gray);
        font-weight: 500;
    }
    
    input[type="text"],
    input[type="password"] {
        width: 100%;
        padding: 0.6rem;
        border: 1px solid var(--light-gray);
        border-radius: 4px;
        font-family: 'Poppins', sans-serif;
        transition: var(--transition);
    }
    
    input[type="text"]:focus,
    input[type="password"]:focus {
        border-color: var(--orange);
        box-shadow: 0 0 0 2px rgba(255,115,0,0.2);
        outline: none;
    }
    
    input[readonly] {
        background-color: #f9f9f9;
    }
    
    .password-mask {
        letter-spacing: 0.2em;
    }
    
    .action-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 1.5rem;
    }
    
    .save-btn {
        background-color: #28a745;
        color: white;
        border: none;
        padding: 0.6rem 1.2rem;
        border-radius: 4px;
        cursor: pointer;
        display: none;
        transition: var(--transition);
    }
    
    .save-btn:hover {
        background-color: #218838;
        transform: translateY(-2px);
    }
    
    .cancel-btn {
        background-color: #6c757d;
        color: white;
        border: none;
        padding: 0.6rem 1.2rem;
        border-radius: 4px;
        cursor: pointer;
        display: none;
        transition: var(--transition);
    }
    
    .cancel-btn:hover {
        background-color: #5a6268;
        transform: translateY(-2px);
    }
    
    .message {
        padding: 1rem;
        margin-bottom: 1rem;
        border-radius: 4px;
        animation: slideIn 0.3s ease-out;
    }
    
    @keyframes slideIn {
        from { opacity: 0; transform: translateX(-20px); }
        to { opacity: 1; transform: translateX(0); }
    }
    
    .error {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    
    .success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    
    #profilePictureInput {
        display: none;
    }
    
    .file-name {
        font-size: 0.8rem;
        color: var(--gray);
        margin-top: 0.5rem;
        animation: fadeIn 0.5s ease-out;
    }
</style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-left">Tricom</div>
        <ul class="nav-list">
            <li><a href="home.php" class="nav-link">Home</a></li>
            <li><a href="membersPage.php" class="nav-link">Members</a></li>
            <li><a href="profile.php" class="nav-link active">Profile</a></li>
        </ul>
        <button class="sign-out-btn" id="signOutBtn">
            <i class="fas fa-sign-out-alt"></i> Sign Out
        </button>
    </nav>

    <main>
        <?php if (!empty($error)): ?>
            <div class="message error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div class="message success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        
        <form id="profileForm" method="POST" action="profile.php" enctype="multipart/form-data">
            <div class="profile-container">
                <div class="profile-picture-section">
                    <img src="<?php 
                        // Check if file exists and is not empty
                        if (!empty($profile_picture) && (filter_var($profile_picture, FILTER_VALIDATE_URL) || file_exists($profile_picture))) {
                            echo htmlspecialchars($profile_picture);
                        } else {
                            echo 'https://via.placeholder.com/150';
                        }
                    ?>" alt="Profile Picture" class="profile-picture" id="profileImage">
                    <label for="profilePictureInput" class="change-photo-btn">
                        <i class="fas fa-camera"></i> Change Photo
                    </label>
                    <input type="file" id="profilePictureInput" name="profile_picture" accept="image/*">
                    <div id="fileName" class="file-name">No file chosen</div>
                </div>
                
                <div class="profile-details">
                    <div class="profile-header">
                        <h2 class="profile-title">Admin Profile</h2>
                        <button type="button" class="edit-btn" id="editProfileBtn">
                            <i class="fas fa-edit"></i> Edit Profile
                        </button>
                    </div>
                    
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" value="********" readonly class="password-mask">
                        <input type="hidden" id="password_changed" name="password_changed" value="0">
                    </div>
                    
                    <div class="action-buttons">
                        <button type="submit" class="save-btn" id="saveProfileBtn">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                        <button type="button" class="cancel-btn" id="cancelEditBtn">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </main>

    <script>
    $(document).ready(function() {
        // Initialize button states
        $('#saveProfileBtn').hide();
        $('#cancelEditBtn').hide();
        
        // Edit button click handler
        $('#editProfileBtn').click(function() {
            // Enable editing
            $('#username').removeAttr('readonly');
            $('#password').removeAttr('readonly')
                          .removeClass('password-mask')
                          .val('')
                          .attr('placeholder', 'Enter new password');
            
            // Set flag that password might be changed
            $('#password_changed').val('1');
            
            // Show save/cancel buttons
            $('#editProfileBtn').hide();
            $('#saveProfileBtn').show();
            $('#cancelEditBtn').show();
        });
        
        // Cancel button click handler
        $('#cancelEditBtn').click(function() {
            // Disable editing
            $('#username').attr('readonly', true);
            $('#password').attr('readonly', true)
                         .addClass('password-mask')
                         .val('********')
                         .removeAttr('placeholder');
            
            // Reset password changed flag
            $('#password_changed').val('0');
            
            // Reset file input
            $('#profilePictureInput').val('');
            $('#fileName').text('No file chosen');
            
            // Reset profile image
            $('#profileImage').attr('src', $('#profileImage').attr('src'));
            
            // Show edit button
            $('#editProfileBtn').show();
            $('#saveProfileBtn').hide();
            $('#cancelEditBtn').hide();
        });
        
        // File input change handler
        $('#profilePictureInput').change(function() {
            const file = this.files[0];
            if (file) {
                $('#fileName').text(file.name);
                
                // Preview image before upload
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#profileImage').attr('src', e.target.result);
                }
                reader.readAsDataURL(file);
            } else {
                $('#fileName').text('No file chosen');
                // Revert to current profile picture
                $('#profileImage').attr('src', $('#profileImage').attr('src'));
            }
        });
        
        // Form submission handler
        $('#profileForm').submit(function(e) {
            const username = $('#username').val().trim();
            if (username === '') {
                alert('Username cannot be empty!');
                e.preventDefault();
                return false;
            }
            
            // If password was changed but left empty, keep the old one
            if ($('#password_changed').val() === '1' && $('#password').val() === '') {
                $('#password').val('********');
                $('#password_changed').val('0');
            }
            
            return true;
        });
        
        // Sign out button
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
    });
    </script>
</body>
</html>