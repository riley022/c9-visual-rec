<?php
include 'includes/header.php';
$query = 'SELECT * FROM product WHERE ref = '.$_GET['ref'].'';
$result = mysqli_query($connection, $query);
$row = mysqli_fetch_array($result);
$query = 'DELETE FROM product WHERE ref = '.$_GET['ref'].'';
if (mysqli_query($connection, $query)) {
    header('Location: '.$url.'/product/identify-image');
}
include 'includes/footer.php';
?>