<?php include 'includes/header.php'; ?>
<div class="mdl-layout__tab-panel is-active" id="overview" style="padding: 32px">
  <section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp" style="width: 860px;">
    <div class="mdl-card mdl-cell mdl-cell--12-col">
      <div class="mdl-card__title mdl-color--primary mdl-color-text--white">
        <h4 class="mdl-card__title-text">Identify Product By Video</h4>
      </div>
      <div class="mdl-card__supporting-text">
      <head>
      	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
      	
      	<style>
      		#camera_wrapper, #show_saved_img{float:left; width: 650px;}
      	</style>
      	
      	<script type="text/javascript" src="../assets/js/webcam.js"></script>
      	<script>
          // $.wait = function(seconds){
          //   return seconds;
          // }
      		$(function(){
      			//give the php file path
      			webcam.set_api_url( '../saveimage.php' );
      			webcam.set_swf_url( '../assets/js/webcam.swf' );//flash file (SWF) file path
      			webcam.set_quality( 100 ); // Image quality (1 - 100)
      			webcam.set_shutter_sound( true ); // play shutter click sound
      			
      			var camera = $('#camera');
      			camera.html(webcam.get_html(600, 460)); //generate and put the flash embed code on page
      			
      			$('#capture_btn').click(function(){
      				//take snap
      				webcam.snap();
      				$('#show_saved_img').html('<h4>Please Wait...</h4>');
      			});
      			
      			//after taking snap call show image
      			webcam.set_hook( 'onComplete', function(img){
      				//$.wait(1).delay( 5000);
      				$('#show_saved_img').html('<img src="' + img + '">');
      				//reset camera for the next shot
      				webcam.reset();
      			});
      			
      		});
      	</script>
      </head>
      <body>
      	<!-- camera screen -->
      	<div id="camera_wrapper">
      	<div id="camera"></div>
      	<br />
      	<button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent" id="capture_btn">Capture</button>
      	</div>
      	<!-- show captured image -->
      	<br>
      	<br>
      	<div id="show_saved_img" ></div>
      </body>
    </div>
  </section>
  <?php
  ?>
</div>
<?php include 'includes/footer.php'; ?>