<?php include 'includes/header.php'; ?>
<div class="mdl-layout__tab-panel is-active" id="overview" style="padding: 32px">
  <section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp" style="width: 1400px;"> 
    <div class="mdl-card mdl-cell mdl-cell--12-col">
      <div class="mdl-card__title mdl-color--primary mdl-color-text--white">
        <h4 class="mdl-card__title-text">Interactive Store Map</h4>
      </div>
      <div class="mdl-card__supporting-text">
        
        <!--
        <form method="post" action="">
          <select name="csv">
            <option selected disabled>Select Data</option>
            <option value="List_0001.csv">List_0001.csv</option>
            <option value="List_0002.csv">List_0001.csv</option>
            <option value="List_0003.csv">List_0001.csv</option>
          </select>
          <input type="submit" value="Load Data"/>
        </form>
        <?php
          include $url.'/usercase_ap.php?csv='.$_POST['csv'];
        ?>
        -->
        
        <div class="Wrapper">
            <img id="ap_map" src="https://visual-recognition-rgre2543.c9users.io/exampleData/0001-Store.jpg" class="coveredMap"/>
            <canvas class="overlay" id="ap_overlay"></canvas>
        </div>
      </div>
    </div>
  </section>
  <script type="text/javascript" src="/assets/js/APOveraly.js"></script>
</div>
<?php include 'includes/footer.php'; ?>