<?php include 'includes/header.php'; ?>
<div class="mdl-layout__tab-panel is-active" id="overview" style="padding: 32px">
  <?php
    if(isset($_POST['submit']) && (isset($_POST['azure']) or isset($_POST['azure2']) or isset($_POST['google']) or isset($_POST['tensor-10']) or isset($_POST['walmart-maps']) or isset($_POST['tensor-100']) or isset($_POST['video']) or isset($_POST['darknet']) or isset($_POST['darknet19']) or isset($_POST['darknet19-Large']) or isset($_POST['yolo']) or isset($_POST['yolo-better']) or isset($_POST['google-text']) or isset($_POST['google-faces']) or isset($_POST['google-label']))) {
      
      $creator_ref = $_SESSION['logged_in']['ref'];
      
      $file_name = $_FILES['image']['name'];
      $file_size = $_FILES['image']['size'];
      $file_tmp = $_FILES['image']['tmp_name'];
      $file_type = $_FILES['image']['type'];
      $file_ext = strtolower(end(explode('.',$_FILES['image']['name'])));
      
      $query = "INSERT INTO product(creator_ref) VALUES('$creator_ref')";
      
      if (mysqli_query($connection, $query)) {
        // Establishes product ID
        $productid = mysqli_insert_id($connection);
        
        // Establishes image name
        $file_name = $productid.".".$file_ext;
        
        $file_unique_name = $productid.".jpg";
        
        error_log($file_tmp);
        
        // Saves image into global catalogue
        move_uploaded_file($file_tmp,"product-images/".$file_name);
        
        if ($_POST['video'] == 'true'){
          shell_exec(escapeshellcmd("python scripts/run_video.py ".$file_name));
          shell_exec(escapeshellcmd("python scripts/run_azure_description.py  ".$file_unique_name));
        }
        shell_exec(escapeshellcmd("./product-images/convert.sh"));
        
        // Initalizes Azure Computer Vision, generates CSV file, Writes tags into SQL database.
        if ($_POST['azure'] == 'true') {
          shell_exec(escapeshellcmd("python scripts/run_azure.py ".$file_unique_name));
        }
        // Initalizes Azure Computer Vision, generates CSV file, Writes tags into SQL database.
        if ($_POST['azure2'] == 'true') {
          shell_exec(escapeshellcmd("python scripts/run_azure_description.py  ".$file_unique_name));
        }
        //Sends Image and List file to exampleData
        if ($_POST['walmart-maps'] == 'true') {
          shell_exec(escapeshellcmd("cd  /home/ubuntu/workspace/Map; python  /home/ubuntu/workspace/Map/Run-me.py ".$file_unique_name.";cd .."));
        }
        // Initalizes TensorFlow (Cifar-10), generates CSV file.
        if ($_POST['tensorflow-10'] == 'true'){
          shell_exec(escapeshellcmd("python scripts/run_tensor-10.py ".$file_unique_name));
        }
        // Initalizes TensorFlow (Cifar-100), generates CSV file.
        if ($_POST['tensorflow-100'] == 'true'){
          shell_exec(escapeshellcmd("python scripts/run_tensor-100.py ".$file_unique_name));
        }
        // Initalizes DarkNet, generates CSV file.
        if ($_POST['darknet'] == 'true'){
          shell_exec(escapeshellcmd("sudo python scripts/run_darknet.py darknet ".$file_unique_name));
        }
        if ($_POST['darknet19'] == 'true'){
          shell_exec(escapeshellcmd("sudo python scripts/run_darknet.py darknet19 ".$file_unique_name));
        }
        if ($_POST['darknet19-Large'] == 'true'){
          shell_exec(escapeshellcmd("sudo python scripts/run_darknet.py darknet19-large ".$file_unique_name));
        }
        if ($_POST['yolo'] == 'true'){
          shell_exec(escapeshellcmd("sudo python scripts/run_darknet.py yolo ".$file_unique_name));
        }
        if ($_POST['yolo-better'] == 'true'){
          shell_exec(escapeshellcmd("sudo python scripts/run_darknet.py yolo-better ".$file_unique_name));
        }
        
        // Initalizes Google, generates CSV file.
        if ($_POST['google-text'] == 'true'){
          shell_exec(escapeshellcmd("python google-cloud/label.py text product-images/".$file_unique_name));
        }
        if ($_POST['google-faces'] == 'true'){
          shell_exec(escapeshellcmd("python google-cloud/label.py faces product-images/".$file_unique_name));
        }
        if ($_POST['google-label'] == 'true'){
          shell_exec(escapeshellcmd("python google-cloud/label.py labels product-images/".$file_unique_name));
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
        <h4 class="mdl-card__title-text">Identify Image/Video</h4>
      </div>
      <div class="mdl-card__supporting-text">
        <p>Select an image using the drag and drop area shown below, the picture must clearly show the product in order for detection to be accurate.</p>
        <form action="" method="POST" enctype="multipart/form-data">
          <input type="file" name="image" accept="video/mp4,video/x-m4v,video/*,image/*,images/jpg,images/png" />
          <h6 style="margin-bottom:0"><b>Select Image Recognition Service:</b></h6>
          <div style="padding-left: 15px">
            <div class="mdl-grid">
              <div class='mdl-cell mdl-cell--6-col'>
                <span class="mdl-checkbox__label"><b>TensorFlow</b></span>
                <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox-13">
                  <input type="checkbox" id="checkbox-13" class="mdl-checkbox__input" value="true" name="walmart-maps" disabled>
                  <span class="mdl-checkbox__label">Interactive Map (TensorFlow - NIST)</span>
                </label>
                <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox-3">
                  <input type="checkbox" id="checkbox-3" class="mdl-checkbox__input" value="true" name="tensorflow-10">
                  <span class="mdl-checkbox__label">TensorFlow (Cifar-10)</span>
                </label>
                <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox-4">
                  <input type="checkbox" id="checkbox-4" class="mdl-checkbox__input" value="true" name="tensorflow-100" disabled>
                  <span class="mdl-checkbox__label">TensorFlow (Cifar-100)</span>
                </label>
                
                <br><br>
                <span class="mdl-checkbox__label"><b>DarkNet</b></span>
                <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox-5">
                  <input type="checkbox" id="checkbox-5" class="mdl-checkbox__input" value="true" name="darknet">
                  <span class="mdl-checkbox__label">DarkNet (Darknet Reference)</span>
                </label>
                <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox-6">
                  <input type="checkbox" id="checkbox-6" class="mdl-checkbox__input" value="true" name="darknet19" disabled>
                  <span class="mdl-checkbox__label">DarkNet (Darknet19)</span>
                </label>
                <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox-7">
                  <input type="checkbox" id="checkbox-7" class="mdl-checkbox__input" value="true" name="darknet19-Large" disabled>
                  <span class="mdl-checkbox__label">DarkNet (Darknet19 448x448)</span>
                </label>
                <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox-8">
                  <input type="checkbox" id="checkbox-8" class="mdl-checkbox__input" value="true" name="yolo" disabled>
                  <span class="mdl-checkbox__label">DarkNet (YOLOv2)</span>
                </label>
                <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox-9">
                  <input type="checkbox" id="checkbox-9" class="mdl-checkbox__input" value="true" name="yolo-better">
                  <span class="mdl-checkbox__label">DarkNet (YOLOv2 544x544)</span>
                </label>
              </div>
              <div class='mdl-cell mdl-cell--6-col'>
                <span class="mdl-checkbox__label"><b>Azure Computer Vision</b></span>
                <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox-1">
                  <input type="checkbox" id="checkbox-1" class="mdl-checkbox__input" value="true" name="azure">
                  <span class="mdl-checkbox__label">Azure Tags Detection</span>
                </label>
                <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox-15">
                  <input type="checkbox" id="checkbox-15" class="mdl-checkbox__input" value="true" name="azure2">
                  <span class="mdl-checkbox__label">Azure Descriptor Detection</span>
                </label>
                
                <br><br>
                <span class="mdl-checkbox__label"><b>Google Cloud Vision</b></span>
                <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox-10">
                  <input type="checkbox" id="checkbox-10" class="mdl-checkbox__input" value="true" name="google-text">
                  <span class="mdl-checkbox__label">Google Text Detection</span>
                </label>
                <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox-11">
                  <input type="checkbox" id="checkbox-11" class="mdl-checkbox__input" value="true" name="google-faces">
                  <span class="mdl-checkbox__label">Google Faces Detection</span>
                </label>
                <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox-12">
                  <input type="checkbox" id="checkbox-12" class="mdl-checkbox__input" value="true" name="google-label">
                  <span class="mdl-checkbox__label">Google Labels Detection</span>
                </label>
                
                <br><br>
                <span class="mdl-checkbox__label"><b>Video</b></span>
                <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox-16">
                  <input type="checkbox" id="checkbox-16" class="mdl-checkbox__input" value="true" name="video">
                  <span class="mdl-checkbox__label">Video Label</span>
                </label>
              </div>
            </div>
          </div>
          <input type="submit" value="Scan Product" name="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">
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