<?php
include 'includes/header.php';
?>
<div class="mdl-layout__tab-panel is-active" id="overview" style="padding: 32px">
  <?php
  $uquery = 'SELECT * FROM user WHERE ref = '.$_SESSION['logged_in']['ref'].'';
  $uresult = mysqli_query($connection, $uquery);
  $urow = mysqli_fetch_array($uresult);
  
  if(isset($_POST['submit'])) {
    $username = strip_tags(htmlspecialchars(mysqli_real_escape_string($connection, $_POST['username'])));
    $password = strip_tags(htmlspecialchars(mysqli_real_escape_string($connection, $_POST['password'])));
    $fname = strip_tags(htmlspecialchars(mysqli_real_escape_string($connection, $_POST['fname'])));
    $lname = strip_tags(htmlspecialchars(mysqli_real_escape_string($connection, $_POST['lname'])));
    $email = strip_tags(htmlspecialchars(mysqli_real_escape_string($connection, $_POST['email'])));
    
    $query = "UPDATE user SET username = '".$username."', password = '".$password."', fname = '".$fname."', lname = '".$lname."', email = '".$email."' WHERE ref = ".$_SESSION['logged_in']['ref']."";
    
    if (mysqli_query($connection, $query)) {
      header('Location: '.$url.'/user');
    }
    else {
      echo "<section class='section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp' style='width: 860px;'><div class='mdl-cell mdl-cell--12-col'><div class='mdl-card__title mdl-color--amber mdl-color-text--white'><h4 class='mdl-card__title-text'>Error</h4></div></div></section>";
    }
    mysqli_close($connection);
  }
  ?>
  <section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp" style="width: 860px;">
    <div class="mdl-card mdl-cell mdl-cell--12-col">
      <div class="mdl-card__title mdl-color--primary mdl-color-text--white">
        <h4 class="mdl-card__title-text">Edit Profile</h4>
      </div>
      <div class="mdl-card__supporting-text">
        <form action="" method="POST">
            <div class="mdl-textfield mdl-js-textfield">
                <input class="mdl-textfield__input" type="text" name="username" value="<?php echo $urow['username']; ?>" required/>
                <label class="mdl-textfield__label" for="username">Username</label>
            </div>
            <br>
            <div class="mdl-textfield mdl-js-textfield">
                <input class="mdl-textfield__input" type="password" name="password" value="<?php echo $urow['password']; ?>" required/>
                <label class="mdl-textfield__label" for="password">Password</label>
            </div>
            <br>
            <div class="mdl-textfield mdl-js-textfield">
                <input class="mdl-textfield__input" type="text" name="fname" value="<?php echo $urow['fname']; ?>" required/>
                <label class="mdl-textfield__label" for="fname">First Name</label>
            </div>
            <br>
            <div class="mdl-textfield mdl-js-textfield">
                <input class="mdl-textfield__input" type="text" name="lname" value="<?php echo $urow['lname']; ?>" required/>
                <label class="mdl-textfield__label" for="lname">Last Name</label>
            </div>
            <br>
            <div class="mdl-textfield mdl-js-textfield">
                <input class="mdl-textfield__input" type="email" name="email" value="<?php echo $urow['email']; ?>" required/>
                <label class="mdl-textfield__label" for="email">Email Address</label>
            </div>
            <br>
            <div>
                <input type="submit" value="Save Changes" name="submit" class="mdl-button mdl-button--colored mdl-button--raised mdl-js-button mdl-js-ripple-effect">
                <a href="<?php echo $url ?>/user/delete" class="mdl-button mdl-button--colored mdl-button--raised mdl-js-button mdl-js-ripple-effect">Delete Profile</a>
            </div>
        </form>
      </div>
    </div>
  </section>
</div>
<?php
include 'includes/footer.php';
?>