<?php
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

// Function to display all members alphabetically by name with optional filters
function displayMembers($genderFilter = 'All', $roleFilter = 'All') {
    $conn = connectDB();
    
    // Base SQL query
    $sql = "SELECT * FROM members WHERE 1=1";
    
    // Add gender filter if needed
    if ($genderFilter !== 'All') {
        $sql .= " AND gender = '" . $conn->real_escape_string($genderFilter) . "'";
    }
    
    // Add role filter if needed
    if ($roleFilter !== 'All') {
        $sql .= " AND role = '" . $conn->real_escape_string($roleFilter) . "'";
    }
    
    // Always order by name
    $sql .= " ORDER BY name ASC";
    
    $result = $conn->query($sql);
    
    $output = '<div class="members-table">
                 <div class="table-header">
                    <div class="header-id">Student No.</div>
                    <div class="header-profile">Profile</div>
                    <div class="header-name">Name</div>
                    <div class="header-age">Age</div>
                    <div class="header-gender">Gender</div>
                    <div class="header-contact">Contact</div>
                    <div class="header-address">Address</div>
                    <div class="header-role">Role</div>
                    <div class="header-actions">Actions</div>
                </div>';
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $output .= '
            <div class="table-row">
                <div class="cell-id">'.(!empty($row["student_id"]) ? htmlspecialchars($row["student_id"]) : 'N/A').'</div>
                <div class="cell-profile">
                    <img src="'.(!empty($row["profile_picture"]) ? htmlspecialchars($row["profile_picture"]) : 'https://via.placeholder.com/40').'" class="member-photo">
                </div>
                <div class="cell-name">'.(!empty($row["name"]) ? htmlspecialchars($row["name"]) : 'Unknown').'</div>
                <div class="cell-age">'.(!empty($row["age"]) ? htmlspecialchars($row["age"]) : 'N/A').'</div>
                <div class="cell-gender">'.(!empty($row["gender"]) ? htmlspecialchars($row["gender"]) : 'N/A').'</div>
                <div class="cell-contact">'.(!empty($row["contact_number"]) ? htmlspecialchars($row["contact_number"]) : 'N/A').'</div>
                <div class="cell-address">'.(!empty($row["address"]) ? htmlspecialchars($row["address"]) : 'N/A').'</div>
                <div class="cell-role">'.(!empty($row["role"]) ? htmlspecialchars($row["role"]) : 'Student').'</div>
                <div class="cell-actions">
                    <button class="edit-btn" data-id="'.$row["id"].'">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="delete-btn" data-id="'.$row["id"].'">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>';
        }
    } else {
        $output .= '<div class="no-members">No members found matching the selected filters.</div>';
    }
    
    $output .= '</div>'; // Close table
    $conn->close();
    return $output;
}


// Function to add a new member
function addMember($data) {
    $conn = connectDB();
    
    // Handle file upload for profile picture
    $profilePicture = "https://via.placeholder.com/150"; // default
    if (isset($_FILES['profilePicture'])) {
        $targetDir = "uploads/";
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $fileName = uniqid() . '_' . basename($_FILES["profilePicture"]["name"]);
        $targetFile = $targetDir . $fileName;
        if (move_uploaded_file($_FILES["profilePicture"]["tmp_name"], $targetFile)) {
            $profilePicture = $targetFile;
        }
    }
    
    // Combine name fields
    $name = $data['lastName'] . ', ' . $data['firstName'];
    if (!empty($data['middleInitial'])) {
        $name .= ' ' . $data['middleInitial'] . '.';
    }
    
    $stmt = $conn->prepare("INSERT INTO members (student_id, profile_picture, name, age, gender, contact_number, address, role) 
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssissss", 
        $data['studentNo'],
        $profilePicture,
        $name,
        $data['age'],
        $data['gender'],
        $data['contact'],
        $data['address'],
        $data['role']
    );
    
    $result = $stmt->execute();
    $stmt->close();
    $conn->close();
    
    return $result;
}

// Function to get member data by ID
function getMemberById($id) {
    $conn = connectDB();
    $stmt = $conn->prepare("SELECT * FROM members WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $member = $result->fetch_assoc();
    $stmt->close();
    $conn->close();
    return $member;
}

// Function to update member
function updateMember($id, $data) {
    $conn = connectDB();
    
    // Handle file upload if new profile picture is provided
    $profilePicture = $data['existingProfilePicture'];
    if (isset($_FILES['profilePicture']) && $_FILES['profilePicture']['size'] > 0) {
        $targetDir = "uploads/";
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $fileName = uniqid() . '_' . basename($_FILES["profilePicture"]["name"]);
        $targetFile = $targetDir . $fileName;
        if (move_uploaded_file($_FILES["profilePicture"]["tmp_name"], $targetFile)) {
            $profilePicture = $targetFile;
        }
    }
    
    // Combine name fields
    $name = $data['lastName'] . ', ' . $data['firstName'];
    if (!empty($data['middleInitial'])) {
        $name .= ' ' . $data['middleInitial'] . '.';
    }
    
    $stmt = $conn->prepare("UPDATE members SET 
        student_id = ?,
        profile_picture = ?,
        name = ?,
        age = ?,
        gender = ?,
        contact_number = ?,
        address = ?,
        role = ?
        WHERE id = ?");
    
    $stmt->bind_param("sssissssi", 
        $data['studentNo'],
        $profilePicture,
        $name,
        $data['age'],
        $data['gender'],
        $data['contact'],
        $data['address'],
        $data['role'],
        $id
    );
    
    $result = $stmt->execute();
    $stmt->close();
    $conn->close();
    return $result;
}

// Function to delete member
function deleteMember($id) {
    $conn = connectDB();
    
    // First, get the member's profile picture path
    $stmt = $conn->prepare("SELECT profile_picture FROM members WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $member = $result->fetch_assoc();
    $stmt->close();
    
    // Delete the member from database
    $stmt = $conn->prepare("DELETE FROM members WHERE id = ?");
    $stmt->bind_param("i", $id);
    $result = $stmt->execute();
    $stmt->close();
    $conn->close();
    
    // If deletion was successful and profile picture exists (not the default placeholder)
    if ($result && isset($member['profile_picture']) && 
        strpos($member['profile_picture'], 'uploads/') !== false && 
        file_exists($member['profile_picture'])) {
        // Delete the profile picture file
        unlink($member['profile_picture']);
    }
    
    return $result;
}

