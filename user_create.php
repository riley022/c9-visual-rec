<?php
include 'includes/header.php';
?>
<!--
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
-->
<div class="mdl-layout__tab-panel is-active" id="overview" style="padding: 32px">
  <?php
  if(isset($_POST['submit'])) {
    
    $username = strip_tags(htmlspecialchars(mysqli_real_escape_string($connection, $_POST['username'])));
    $password = strip_tags(htmlspecialchars(mysqli_real_escape_string($connection, $_POST['password'])));
    $fname = strip_tags(htmlspecialchars(mysqli_real_escape_string($connection, $_POST['fname'])));
    $lname = strip_tags(htmlspecialchars(mysqli_real_escape_string($connection, $_POST['lname'])));
    $email = strip_tags(htmlspecialchars(mysqli_real_escape_string($connection, $_POST['email'])));
    
    $query = "INSERT INTO user(username, password, fname, lname, email) VALUES('$username', '$password', '$fname', '$lname', '$email')";
    
    if (mysqli_query($connection, $query)) {
      echo "<section class='section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp' <!-- style='width: 860px;' -->><div class='mdl-card mdl-cell mdl-cell--12-col'><div class='mdl-card__title mdl-color--green mdl-color-text--white'><h4 class='mdl-card__title-text'>Registation Sucessful</h4></div><div class='mdl-card__supporting-text'><p>Your account was successfully created.</p><br><a href='".$url."/login' class='mdl-button mdl-color--green mdl-button--colored mdl-button--raised mdl-js-button mdl-js-ripple-effect' data-upgraded=',MaterialButton,MaterialRipple'>Login</a></div></div></section>";
    }
    else {
      echo "<section class='section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp' style='width: 860px;'><div class='mdl-cell mdl-cell--12-col'><div class='mdl-card__title mdl-color--amber mdl-color-text--white'><h4 class='mdl-card__title-text'>Error</h4></div></div></section>";
    }
    // mysqli_close($connection);
  }
  /* --CODE NOT WORKING-- */
  /*
  if(isset($_POST['submit']) && !empty($_POST['submit']) && (password == password_confirm) ){
    if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])){
      //your site secret key
      $secret = '6LdUxR4UAAAAANB144ZJ2StsuD1DBFN15e3rZeiK';
      //get verify response data
      $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
      $responseData = json_decode($verifyResponse);
      if($responseData->success){
          $username = strip_tags(htmlspecialchars(mysqli_real_escape_string($connection, $_POST['username'])));
          $password = strip_tags(htmlspecialchars(mysqli_real_escape_string($connection, $_POST['password'])));
          $fname = strip_tags(htmlspecialchars(mysqli_real_escape_string($connection, $_POST['fname'])));
          $lname = strip_tags(htmlspecialchars(mysqli_real_escape_string($connection, $_POST['lname'])));
          $email = strip_tags(htmlspecialchars(mysqli_real_escape_string($connection, $_POST['email'])));
  
          $query = "INSERT INTO user(username, password, fname, lname, email) VALUES('$username', '$password', '$fname', '$lname', '$email')";
  
          if (mysqli_query($connection, $query)) {
            echo "<section class='section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp'><div class='mdl-card mdl-cell mdl-cell--12-col'><div class='mdl-card__title mdl-color--green mdl-color-text--white'><h4 class='mdl-card__title-text'>Registation Sucessful</h4></div><div class='mdl-card__supporting-text'><p>Your account was successfully created.</p><br><a href='".$url."/login' class='mdl-button mdl-color--green mdl-button--colored mdl-button--raised mdl-js-button mdl-js-ripple-effect' data-upgraded=',MaterialButton,MaterialRipple'>Login</a></div></div></section>";
          }
          else {
            echo "<section class='section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp'><div class='mdl-cell mdl-cell--12-col'><div class='mdl-card__title mdl-color--amber mdl-color-text--white'><h4 class='mdl-card__title-text'>Error</h4></div></div></section>";
          }
          mysqli_close($connection);
          $succMsg = 'Your contact request have submitted successfully.';
          }else{
              $errMsg = 'Robot verification failed, please try again.';
          }
      }else{
        $errMsg = 'Please click on the reCAPTCHA box.';
      }
    }
  */

  ?>
  <section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp">
    <div class="mdl-card mdl-cell mdl-cell--12-col">
      <div class="mdl-card__title mdl-color--primary mdl-color-text--white">
        <h4 class="mdl-card__title-text">Register Account</h4>
      </div>
      <div class="mdl-card__supporting-text">
        <form action="" method="POST">
            <div class="mdl-textfield mdl-js-textfield">
                <input class="mdl-textfield__input" type="text" name="username" required/>
                <label class="mdl-textfield__label" for="username">Username</label>
            </div>
            <br>
            <div class="mdl-textfield mdl-js-textfield">
                <input class="mdl-textfield__input" type="password" name="password" required/>
                <label class="mdl-textfield__label" for="password">Password</label>
            </div>
            <br>
            <div class="mdl-textfield mdl-js-textfield">
                <input class="mdl-textfield__input" type="password" name="password_confirm" required/>
                <label class="mdl-textfield__label" for="password_confirm">Confirm Password</label>
            </div>
            <br>
            <div class="mdl-textfield mdl-js-textfield">
                <input class="mdl-textfield__input" type="text" name="fname" required/>
                <label class="mdl-textfield__label" for="fname">First Name</label>
            </div>
            <br>
            <div class="mdl-textfield mdl-js-textfield">
                <input class="mdl-textfield__input" type="text" name="lname" required/>
                <label class="mdl-textfield__label" for="lname">Last Name</label>
            </div>
            <br>
            <div class="mdl-textfield mdl-js-textfield">
                <input class="mdl-textfield__input" type="email" name="email" required/>
                <label class="mdl-textfield__label" for="email">Email Address</label>
            </div>
            <br>
            <!--
            <div class="g-recaptcha" data-sitekey="6LdUxR4UAAAAALVWJkOo8ETXh1KZ41gPT8p6knVO"></div>
            <br>
            -->
            <input type="submit" value="Register" name="submit" class="mdl-button mdl-button--colored mdl-button--raised mdl-js-button mdl-js-ripple-effect">
        </form>
      </div>
    </div>
  </section>
</div>
<?php
include 'includes/footer.php';
?>