<?php
include 'includes/header.php';
?>
<!DOCTYPE html>
<html>
<head>
  <title></title>
</head>
<body>
  <div class="mdl-layout__tab-panel is-active" id="overview" style="padding: 32px">
    <?php
      if (empty($_GET["ref"])) {
        $query = 'select * FROM user WHERE ref = '.$_SESSION['logged_in']['ref'].'';
      }
      else {
        $query = 'select * FROM user WHERE ref = '.$_GET["ref"].'';
      }
      $result = mysqli_query($connection, $query);
      if (empty($result)) {
        echo "<div class='row'><div class='col-md-2'></div><div class='col-md-8'><h3>Error</h3><p>Sorry, this user can't be found.</p></div><div class='col-md-2'></div></div>";
      }
      else {
        $row = mysqli_fetch_array($result);
        if (empty($row)) {
          echo "<div class='row'><div class='col-md-2'></div><div class='col-md-8'><h3>Error</h3><p>Sorry, this user can't be found.</p></div><div class='col-md-2'></div></div>";
        }
        else {
      ?>
    <section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp" style="width: 860px;">
      <div class="mdl-card mdl-cell mdl-cell--12-col">
        <div class="mdl-card__title mdl-color--primary mdl-color-text--white">
          <h4 class="mdl-card__title-text">View Profile</h4>
        </div>
        <div class="mdl-card__supporting-text">
          <div class="mdl-grid">
            <div class='mdl-cell mdl-cell--6-col'>
              <form>
                <fieldset>
                  <h5 style="margin: 0 0 16px">Basic Information</h5>
                  <p><label><b>Username</b></label></p>
                  <p><?php echo $row['username']; ?></p>
                  <p></p>
                  <p><label><b>Full Name</b></label></p>
                  <p><?php echo $row['fname']; ?> <?php echo $row['lname']; ?></p>
                  <p></p>
                  <p><label><b>Email Address</b></label></p>
                  <p><?php echo $row['email']; ?></p>
                  <p></p>
                  <p><?php if($_SESSION['logged_in']['ref'] ==$row['ref']) { echo "<a class='mdl-button mdl-button--colored mdl-button--raised mdl-js-button mdl-js-ripple-effect' href='".$url."/user/edit'>Edit Profile</a>";}?></p>
                </fieldset>
              </form>
            </div>
            <div class='mdl-cell mdl-cell--6-col'>
              <form>
                <fieldset>
                  <h5 style="margin: 0 0 16px">Products Identified</h5>
                  <ul>
                    <?php 
                    $mqquery = 'SELECT * FROM product WHERE creator_ref = '.$_SESSION['logged_in']['ref'].'';
                    $mqresult = mysqli_query($connection, $mqquery);
                    $mqcount = mysqli_num_rows($mqresult);
                
                    if (empty($mqcount)) {
                      echo "Sorry, this user hasn't processed any products.";
                    }
                    else {
                      for($i=0;$i<$mqcount;$i++) {
                        $mqrow[$i] = mysqli_fetch_array($mqresult);
                      }
                      
                      foreach ($mqrow as $mqnext) {
                        echo "<li><a style='text-transform: capitalize' href='".$url."/product/".$mqnext['ref']."'>".$mqnext['name']."</a></li>";
                      }
                    }
                    ?>
                  </ul>
                </fieldset>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
    <?php
        }
      }
    ?>
  </div>
  <?php
  include 'includes/footer.php';
  ?>
</body>
</html>