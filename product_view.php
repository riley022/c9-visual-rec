<?php include 'includes/header.php'; ?>
<div class="mdl-layout__tab-panel is-active" id="overview" style="padding: 32px">
  <?php 
    $query = 'select * FROM product WHERE ref = '.$_GET['ref'].'';
    $result = mysqli_query($connection, $query);
    if (empty($result)) {
      echo "<section class='section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp' style='width: 860px;'><div class='mdl-cell mdl-cell--12-col'><div class='mdl-card__title mdl-color--amber mdl-color-text--white'><h4 class='mdl-card__title-text'>Error</h4></div></div></section>";
    }
    else {
      $row = mysqli_fetch_array($result);
      if (empty($row)) {
        echo "<section class='section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp' style='width: 860px;'><div class='mdl-cell mdl-cell--12-col'><div class='mdl-card__title mdl-color--amber mdl-color-text--white'><h4 class='mdl-card__title-text'>Error</h4></div></div></section>";
        header('Location: '.$url.'/product/identify-image');
      }
      else {
  ?>
  <section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp" style="width: 860px;">
    <div class="mdl-card mdl-cell mdl-cell--12-col">
      <div class="mdl-card__title mdl-color--primary mdl-color-text--white">
        <h4 class="mdl-card__title-text">View Product</h4>
      </div>
      <div class="mdl-card__supporting-text">
        <p>This page will display the image processed and the tags detected within the image and their confidence level.</p>
        <h5 style="margin: 0 0 16px">Information</h5>
        <p>
          <label><b>Product Name</b></label>
          <p style="text-transform: capitalize;"><?php echo $row['name'] ?></p>
          <label><b>Processed Image</b></label>
          <br>
          <img src="<?php echo $url."/product-images/".$row['ref'].".jpg"; ?>" style="width: 300px; border-radius: 3px"></img>
          <br>
          <br>
          <p><a href="<?php echo $url."/product/delete/".$row['ref']; ?>" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">Delete Product</a></p>
        </p>
        <h5 style="margin: 0 0 16px">Tags Detected</h5>
        <div class="mdl-grid">
        <?php
        
        /* AZURE COMPUTER VISION TAGS */
        $aquery = 'SELECT * FROM product, tag WHERE product.ref = tag.product_ref AND (tag.type = "azure-tags" OR tag.type = "azure-description") AND tag.product_ref = '.$row['ref'].'';
        $aresult = mysqli_query($connection, $aquery);
        $acount = mysqli_num_rows($aresult);
        
        if (!empty($acount)) {
          echo "<div class='mdl-cell mdl-cell--3-col'><label><b>Azure Computer Vision</b></label><ul>";
          for($i=0;$i<$acount;$i++) {
          	$arow[$i] = mysqli_fetch_array($aresult);
          }
          
          foreach ($arow as $anext) {
          echo "<li><a href='".$url."/tag/".$anext['ref']."' style='text-transform: capitalize;'>".$anext['name']."<p style='margin: 0;'>Accuracy: ".$anext['confidence']."%</p></a></li>";
          }
          echo "</ul></div>";
        }
        
        /* DARKNET TAGS */
        $dquery = 'SELECT * FROM product, tag WHERE product.ref = tag.product_ref AND tag.type = "darknet" AND tag.product_ref = '.$row['ref'].'';
        $dresult = mysqli_query($connection, $dquery);
        $dcount = mysqli_num_rows($dresult);
        
        if (!empty($dcount)) {
          echo "<div class='mdl-cell mdl-cell--3-col'><label><b>DarkNet</b></label><ul>";
          for($i=0;$i<$dcount;$i++) {
          	$drow[$i] = mysqli_fetch_array($dresult);
          }
          
          foreach ($drow as $dnext) {
          echo "<li><a href='".$url."/tag/".$dnext['ref']."' style='text-transform: capitalize;'>".$dnext['name']."<p style='margin: 0;'>Accuracy: ".$dnext['confidence']."%</p></a></li>";
          }
          echo "</ul></div>";
        }

        /* GOOGLE CLOUD VISION TAGS */
        $gquery = 'SELECT * FROM product, tag WHERE product.ref = tag.product_ref AND (tag.type = "google-faces" OR tag.type = "google-text" OR tag.type = "google-labels")  AND tag.product_ref = '.$row['ref'].'';
        $gresult = mysqli_query($connection, $gquery);
        $gcount = mysqli_num_rows($gresult);
        
        if (!empty($gcount)) {
          echo "<div class='mdl-cell mdl-cell--3-col'><label><b>Google Cloud Vision</b></label><ul>";
          for($i=0;$i<$gcount;$i++) {
          	$grow[$i] = mysqli_fetch_array($gresult);
          }
          
          foreach ($grow as $gnext) {
          echo "<li><a href='".$url."/tag/".$gnext['ref']."' style='text-decoration: underline'>".$gnext['name']." (Accuracy: ".$gnext['confidence']."%)</a></li>";
          }
          echo "</ul></div>";
        }

        /* TENSORFLOW TAGS */
        $t10query = 'SELECT * FROM product, tag WHERE product.ref = tag.product_ref AND tag.type = "tensor-10" AND tag.product_ref = '.$row['ref'].'';
        $t10result = mysqli_query($connection, $t10query);
        $t10count = mysqli_num_rows($t10result);
        
        if (!empty($t10count)) {
          echo "<div class='mdl-cell mdl-cell--3-col'><label><b>TensorFlow</b></label><ul>";
          for($i=0;$i<$t10count;$i++) {
          	$t10row[$i] = mysqli_fetch_array($t10result);
          }

          foreach ($t10row as $t10next) {
          echo "<li><a href='".$url."/tag/".$t10next['ref']."' style='text-transform: capitalize;'>".$t10next['name']."<p style='margin: 0;'>Accuracy: ".($t10next['confidence']* 100)."%</p></a></li>";
          }
          echo "</ul></div>";
        }
        ?>
        </div>
    </div>
  </section>
  <?php
    }
  }
  ?>
</div>
<?php include 'includes/footer.php'; ?>