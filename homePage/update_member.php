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
        'role' => $_POST['role'],
        'existingProfilePicture' => $_POST['existingProfilePicture']
    );
    
    if (updateMember($_POST['id'], $memberData)) {
        echo 'success';
    } else {
        echo 'Failed to update member';
    }
}
?>