<?php
require_once 'features.php';

function getChartData() {
    $conn = connectDB();
    
    // Get gender counts
    $genderQuery = "SELECT gender, COUNT(*) as count FROM members GROUP BY gender";
    $genderResult = $conn->query($genderQuery);
    
    $male_count = 0;
    $female_count = 0;
    
    while ($row = $genderResult->fetch_assoc()) {
        if ($row['gender'] == 'Male') {
            $male_count = $row['count'];
        } elseif ($row['gender'] == 'Female') {
            $female_count = $row['count'];
        }
    }
    
    // Get role counts
    $roleQuery = "SELECT role, COUNT(*) as count FROM members GROUP BY role";
    $roleResult = $conn->query($roleQuery);
    
    $student_count = 0;
    $officer_count = 0;
    
    while ($row = $roleResult->fetch_assoc()) {
        if ($row['role'] == 'Student') {
            $student_count = $row['count'];
        } elseif ($row['role'] == 'Officer') {
            $officer_count = $row['count'];
        }
    }
    
    $conn->close();
    
    return [
        'male_count' => $male_count,
        'female_count' => $female_count,
        'student_count' => $student_count,
        'officer_count' => $officer_count
    ];
}

header('Content-Type: application/json');
echo json_encode(getChartData());
?>