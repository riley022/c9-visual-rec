<?php include 'includes/header.php'; ?>
<div class="mdl-layout__tab-panel is-active" id="overview" style="padding: 32px">
  <section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp" style="width: 860px;">
    <div class="mdl-card mdl-cell mdl-cell--12-col">
      <div class="mdl-card__title mdl-color--primary mdl-color-text--white">
        <h4 class="mdl-card__title-text">Explore Products</h4>
      </div>
      <div class="mdl-card__supporting-text">
        <?php 
        $query = 'SELECT * FROM product';
        $result = mysqli_query($connection, $query);
        $count = mysqli_num_rows($result);
        
        for($i=0;$i<$count;$i++) {
        	$row[$i] = mysqli_fetch_array($result);
        }
        
        foreach ($row as $next) {
        echo "<img href='".$url."/product-images/".$next['image']."'>";
        }
        ?>
    </div>
  </section>
  <?php
  ?>
</div>
<?php include 'includes/footer.php'; ?>