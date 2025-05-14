<?php
require_once 'features.php';

if (isset($_POST['id'])) {
    if (deleteMember($_POST['id'])) {
        echo 'success';
    } else {
        echo 'Failed to delete member';
    }
}
?>