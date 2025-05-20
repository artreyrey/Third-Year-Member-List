<?php
require_once 'features.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $memberData = array(
        'studentNo' => $_POST['studentNo'],
        'firstName' => $_POST['firstName'],
        'middleInitial' => $_POST['middleInitial'],
        'lastName' => $_POST['lastName'],
        'age' => $_POST['age'],
        'gender' => $_POST['gender'],
        'contact' => $_POST['contact'],
        'address' => $_POST['address'],
        'role' => $_POST['role']
    );
    
    if (addMember($memberData)) {
        echo 'success';
    } else {
        echo 'Failed to add member to database';
    }
} else {
    echo 'Invalid request method';
}
?>