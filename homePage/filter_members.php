<?php
require_once 'features.php';

// Get filter values from POST request
$genderFilter = isset($_POST['gender']) ? $_POST['gender'] : 'All';
$roleFilter = isset($_POST['role']) ? $_POST['role'] : 'All';

// Display members with filters applied
echo displayMembers($genderFilter, $roleFilter);
?>