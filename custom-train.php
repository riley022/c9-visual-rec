<?php include 'includes/header.php'; ?>
<div class="mdl-layout__tab-panel is-active" id="overview" style="padding: 32px">
  <?php
    if(isset($_POST['submit']) && (isset($_POST['google-retrain']) or isset($_POST['tensor-10']) or isset($_POST['tensor-100']) or isset($_POST['darknet'])  or isset($_POST['testing']) or isset($_POST['keras-1']))) {
      
      $creator_ref = $_SESSION['logged_in']['ref'];

      
      $query = "INSERT INTO product(creator_ref) VALUES('$creator_ref')";
      
      if (mysqli_query($connection, $query)) {
        // Establishes product ID
        $productid = mysqli_insert_id($connection);
        
        if(isset($_FILES['training-files-upload'])) {
          $file_name = $_FILES['training-files-upload']['name'];
          $file_size = $_FILES['training-files-upload']['size'];
          $file_tmp = $_FILES['training-files-upload']['tmp_name'];
          $file_type = $_FILES['training-files-upload']['type'];
          $file_ext = strtolower(end(explode('.',$_FILES['training-files-upload']['name'])));
          move_uploaded_file($file_tmp,'/home/ubuntu/workspace/training-zips/'.$productid.'-training.zip');
          
        } 
        if(!empty($_POST['training-files-link'])) {
          $file_link = $_POST['training-files-link'];
          $link = 'wget -O /home/ubuntu/workspace/training-zips/'.$productid.'-training.zip '.$file_link.'"';
          shell_exec(escapeshellcmd($link));
        
        }
      
      
        if(isset($_FILES['test-files-upload'])) {
          $file_name = $_FILES['test-files-upload']['name'];
          $file_size = $_FILES['test-files-upload']['size'];
          $file_tmp = $_FILES['test-files-upload']['tmp_name'];
          $file_type = $_FILES['test-files-upload']['type'];
          $file_ext = strtolower(end(explode('.',$_FILES['test-files-upload']['name'])));
          move_uploaded_file($file_tmp,'/home/ubuntu/workspace/training-zips/'.$productid.'-test.zip');
        } 
        if (!empty($_POST['test-files-link'])) {
          $file_link = $_POST['test-files-link'];
          $link = 'wget -O /home/ubuntu/workspace/training-zips/'.$productid.'-test.zip '.$file_link.'"';
          shell_exec(escapeshellcmd($link));
        
        }
        
        // Initalizes TensorFlow (Cifar-10), generates CSV file.
        if ($_POST['tensorflow-10'] == 'true'){
          shell_exec(escapeshellcmd(""));
        }
        // Initalizes TensorFlow (Cifar-100), generates CSV file.
        if ($_POST['tensorflow-100'] == 'true'){
          shell_exec(escapeshellcmd(""));
        }
        // Initalizes DarkNet, generates CSV file.
        if ($_POST['darknet'] == 'true'){
          shell_exec(escapeshellcmd(""));
        }
        
        // Initalizes Google, generates CSV file.
        if ($_POST['google-retrain'] == 'true'){
          $steps = $_POST['google-retrain-steps'];
          shell_exec(escapeshellcmd("scp -i scripts/rsa-private-key.pem training-zips/".$productid."-training.zip ubuntu@54.153.238.139:/opt/retrain/"));
          shell_exec(escapeshellcmd("ssh -i scripts/rsa-private-key.pem ubuntu@54.153.238.139 'cd /opt/retrain/ ; rm /opt/retrain/screenlog.0 ; screen -d -m -L ./run_me.sh ".$productid."-training.zip'"));
        }
        
        
        // For upload testing purposes
        if ($_POST['testing'] == 'true'){
          shell_exec(escapeshellcmd(""));
        }
        
        
        // Initalizes Keras, generates CSV file.
        if ($_POST['keras-1'] == 'true'){
          shell_exec(escapeshellcmd(""));
        }

        
        // Saves name into database
        $nquery = 'SELECT tag.name, MAX(confidence) FROM tag WHERE tag.product_ref = '.$productid.'';
        $nresult = mysqli_query($connection, $nquery);
        $ncount = mysqli_num_rows($nresult);
    
        if (empty($ncount)) {
          $update = "UPDATE product SET product.name = 'N/A' WHERE product.ref = '".$productid."'";
          mysqli_query($connection, $update);
        }
        else {
          for($i=0;$i<$ncount;$i++) {
            $nrow[$i] = mysqli_fetch_array($nresult);
          }
          
          foreach ($nrow as $nnext) {
            $update = "UPDATE product SET product.name = '".$nnext['name']."' WHERE product.ref = '".$productid."'";
            mysqli_query($connection, $update);
          }
        }
        echo "<!--<section class='section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp'><div class='mdl-card mdl-cell mdl-cell--12-col'><div class='mdl-card__title mdl-color--green mdl-color-text--white'><h4 class='mdl-card__title-text'>Image Processing Sucessful</h4></div><div class='mdl-card__supporting-text'><p>The product was successfully processed.</p><br><a href='".$url."/product/".$productid."' class='mdl-button mdl-color--green mdl-button--colored mdl-button--raised mdl-js-button mdl-js-ripple-effect' data-upgraded=',MaterialButton,MaterialRipple'>View Results</a></div></div></section>-->";
      }
      else {
      echo "<!--<section class='section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp'><div class='mdl-cell mdl-cell--12-col'><div class='mdl-card__title mdl-color--amber mdl-color-text--white'><h4 class='mdl-card__title-text'>Error</h4></div></div></section>-->";      }
      // mysqli_close($connection);
    }
  ?>
  <section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp" style="width: 860px;">
    <div class="mdl-card mdl-cell mdl-cell--12-col">
      <div class="mdl-card__title mdl-color--primary mdl-color-text--white">
        <h4 class="mdl-card__title-text">Custom Train on Images</h4>
      </div>
      <div class="mdl-card__supporting-text">
        <p>Select an data training set using the drag and drop area shown below, then select a image recogntion training service. Some image recogntion training services will require a seperated training and test dataset.</p>
        <p><u>The folder structure must be as follows:</u></p>
        <p style="text-align:center"><img src="<?php echo $url ?>/assets/img/folder_structure.png" width="60%"></img></p>
        <br>
        <p><u>Currently Implemented Services:</u></p>
        <p>- <b>Google Inception:</b> Requires only a training set.</p>
        <br>
        <p><u>Examples:</u></p>
        <p>1. https://www.dropbox.com/s/m02om8sn3va5ezv/flower_photos.zip</p>
        <hr>
        <br>
        <form action="" method="POST" enctype="multipart/form-data">
          <span class="mdl-checkbox__label"><b>Training Data Set</b></span><br><br>
          <input type="file" name="training-files-upload" accept="video/mp4,video/x-m4v,video/*,image/*,images/jpg,images/png,*" />Less Than 100MB <br><br>
            If file is over 100MB, please give sharable dropbox link:<br><br>
            <input type="text" name="training-files-link" id="training-files-link"><br><br><br>
          <span class="mdl-checkbox__label"><b>Test Data Set</b></span><br><br>
          <input type="file" name="test-files-upload" accept="video/mp4,video/x-m4v,video/*,image/*,images/jpg,images/png,*" /> Less Than 100MB <br><br>
            If file is over 100MB, please give sharable dropbox link:<br><br>
            <input type="text" name="test-files-link" id="test-files-link"><br>
          <br>
          <hr>
          <h6 style="margin-bottom:0"><b>Select Image Recognition Training Service:</b></h6>
          <div style="padding-left: 15px">
            <div class="mdl-grid">
              <div class='mdl-cell mdl-cell--6-col'>
                <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox-1">
                  <input type="checkbox" id="checkbox-1" class="mdl-checkbox__input" value="true" name="testing">
                  <span class="mdl-checkbox__label">Testing Upload</span>
                </label>
                <br><br>
                <span class="mdl-checkbox__label"><b>TensorFlow</b></span>
                <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox-2">
                  <input type="checkbox" id="checkbox-2" class="mdl-checkbox__input" value="true" name="tensorflow-10" disabled>
                  <span class="mdl-checkbox__label">TensorFlow (< 10 Classes)</span>
                </label>
                <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox-3">
                  <input type="checkbox" id="checkbox-3" class="mdl-checkbox__input" value="true" name="tensorflow-100" disabled>
                  <span class="mdl-checkbox__label">TensorFlow (< 100 Classes)</span>
                </label>
                
                <br><br>
                <span class="mdl-checkbox__label"><b>DarkNet</b></span>
                <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox-4">
                  <input type="checkbox" id="checkbox-4" class="mdl-checkbox__input" value="true" name="darknet" disabled>
                  <span class="mdl-checkbox__label">DarkNet (< 1000 Classes)</span>
                </label>
              </div>
              <div class='mdl-cell mdl-cell--6-col'>
                <span class="mdl-checkbox__label"><b>Google Inception</b></span>
                <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox-5">
                  <input type="checkbox" id="checkbox-5" class="mdl-checkbox__input" value="true" name="google-retrain">
                  <span class="mdl-checkbox__label">Google Inception (Last Node Retrain)</span><br>
                  How many Training Steps? (Default: 4000)<br>
                  <input type="text" name="google-retrain-steps" id="google-retrain-steps"><br>
                </label>
                <br><br>
                <br><br>
                <span class="mdl-checkbox__label"><b>Keras</b></span>
                <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox-6">
                  <input type="checkbox" id="checkbox-6" class="mdl-checkbox__input" value="true" name="keras-1" disabled>
                  <span class="mdl-checkbox__label">Keras</span>
                </label>
              </div>
            </div>
          </div>
          <input type="submit" value="Start Training" id="submit" name="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">
          <button href="<?php echo $url."/product/".mysqli_insert_id($connection) ?>" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">See Most Recent</button>
        </form>
      </div>
    </div>
  </section>
  <section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp" style="width: 860px;">
    <div class="mdl-card mdl-cell mdl-cell--12-col">
      <div class="mdl-card__title mdl-color--primary mdl-color-text--white">
        <h4 class="mdl-card__title-text">Products Identified</h4>
      </div>
      <!--<div class="mdl-card__supporting-text">-->
        <div class="demo-grid-ruler mdl-grid">
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
              echo "<a style='text-transform: capitalize;' href='".$url."/product/".$mqnext['ref']."'><div class='mdl-cell mdl-cell--3-col'><img src='".$url."/product-images/".$mqnext['ref'].".jpg'; style='width: 100%; border-radius: 3px'></img><p style='margin: 5px; text-align: center; font-weight: bold; line-height: normal;'>".$mqnext['name']."</p></a></div>";
            }
          }
          ?>
        </div>
      <!--</div>-->
    </div>
  </selection>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="<?php echo $url ?>/assets/js/vendor/dropify.min.js"></script>
<script>
    $(document).ready(function(){
        // Basic
        $('.dropify').dropify();
        // Used events
        var drEvent = $('#input-file-events').dropify();
        drEvent.on('dropify.beforeClear', function(event, element){
            return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
        });
        drEvent.on('dropify.afterClear', function(event, element){
            alert('File deleted');
        });
        drEvent.on('dropify.errors', function(event, element){
            console.log('Has Errors');
        });
        var drDestroy = $('#input-file-to-destroy').dropify();
        drDestroy = drDestroy.data('dropify')
        $('#toggleDropify').on('click', function(e){
            e.preventDefault();
            if (drDestroy.isDropified()) {
                drDestroy.destroy();
            } else {
                drDestroy.init();
            }
        })
    });
</script>
<?php include 'includes/footer.php'; ?>