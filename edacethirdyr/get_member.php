<?php
require_once 'features.php';

if (isset($_POST['id'])) {
    $member = getMemberById($_POST['id']);
    echo json_encode($member);
}
?>
