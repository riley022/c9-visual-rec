<?php include 'includes/header.php'; ?>
<div class="mdl-layout__tab-panel is-active" id="overview" style="padding: 32px">
    <?php
    $query = 'DELETE FROM user WHERE ref = '.$_SESSION['logged_in']['ref'].'';
    if (mysqli_query($connection, $query)) {
        header('Location: '.$url.'/logout');
    }
    else {
      echo "<section class='section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp' style='width: 860px;'><div class='mdl-cell mdl-cell--12-col'><div class='mdl-card__title mdl-color--amber mdl-color-text--white'><h4 class='mdl-card__title-text'>Error</h4></div></div></section>";
    }
    mysqli_close($connection);
    ?>
</div>
<?php include 'includes/footer.php'; ?>