<?php
include 'includes/header.php';
session_destroy();
header("Location: ".$url."/");
include 'includes/footer.php';
?>