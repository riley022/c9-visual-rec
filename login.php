<?php
include 'includes/header.php';
?>
<!--
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
-->
<div class="mdl-layout__tab-panel is-active" id="overview" style="padding: 32px">
  <?php
  if(isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($connection, $_POST['username']);
    $password = mysqli_real_escape_string($connection, $_POST['password']);
    
    if(!empty($username) && !empty($password)) {
      $query = "SELECT * FROM user WHERE username = '$username' and password = '$password'";
      $result = mysqli_query($connection, $query);
      $row = mysqli_fetch_array($result);
      if(mysqli_num_rows($result) == 1) {
        session_start();
        $_SESSION['logged_in'] = array("logged_in" => $row['username'], "ref" => $row['ref'], "username" => $row['username'], "fname" => $row['fname'], "lname" => $row['lname'], "email" => $row['email'],);
        header('Location: '.$url.'/');
      }
      else {
        echo "<section class='section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp' style='width: 860px;'><div class='mdl-cell mdl-cell--12-col'><div class='mdl-card__title mdl-color--red-500 mdl-color-text--white'><h4 class='mdl-card__title-text'>Unable to login</h4></div></div></section>";
      }
    }
    else {
      echo "<section class=section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp' style='width: 860px;'><div class='mdl-cell mdl-cell--12-col'><div class='mdl-card__title mdl-color--red-500 mdl-color-text--white'><h4 class='mdl-card__title-text'>Unable to login</h4></div></div></section>";
    }
  }
  /* --CODE NOT WORKING-- */
  /*
  if(isset($_POST['submit']) && !empty($_POST['submit'])){
    if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])){
      //your site secret key
      $secret = '6LdUxR4UAAAAANB144ZJ2StsuD1DBFN15e3rZeiK';
      //get verify response data
      $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
      $responseData = json_decode($verifyResponse);
      if($responseData->success){
          $username = mysqli_real_escape_string($connection, $_POST['username']);
          $password = mysqli_real_escape_string($connection, $_POST['password']);
    
          if(!empty($username) && !empty($password)) {
            $query = "SELECT * FROM user WHERE username = '$username' and password = '$password'";
            $result = mysqli_query($connection, $query);
            $row = mysqli_fetch_array($result);
            if(mysqli_num_rows($result) == 1) {
              session_start();
              $_SESSION['logged_in'] = array("logged_in" => $row['username'], "ref" => $row['ref'], "username" => $row['username'], "fname" => $row['fname'], "lname" => $row['lname'], "email" => $row['email'],);
              header('Location: '.$url.'/product/create');
            }
            else {
              echo "<section class='section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp' style='width: 860px;'><div class='mdl-cell mdl-cell--12-col'><div class='mdl-card__title mdl-color--red-500 mdl-color-text--white'><h4 class='mdl-card__title-text'>Unable to login</h4></div></div></section>";
            }
          }
          else {
            echo "<section class='section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp'><div class='mdl-cell mdl-cell--12-col'><div class='mdl-card__title mdl-color--red-500 mdl-color-text--white'><h4 class='mdl-card__title-text'>Unable to login</h4></div></div></section>";
          }
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
  <section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp " style="width: 860px;">
    <div class="mdl-card mdl-cell mdl-cell--12-col">
      <div class="mdl-card__title mdl-color--primary mdl-color-text--white">
        <h4 class="mdl-card__title-text">Account Login</h4>
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
            <!--
            <div class="g-recaptcha" data-sitekey="6LdUxR4UAAAAALVWJkOo8ETXh1KZ41gPT8p6knVO"></div>
            <br>
            -->
            <input type="submit" value="Login" name="submit" class="mdl-button mdl-button--colored mdl-button--raised mdl-js-button mdl-js-ripple-effect">
        </form>
      </div>
    </div>
  </section>
<?php
include 'includes/footer.php';
?>