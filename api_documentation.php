<?php
include 'includes/header.php';
?>
<div class="mdl-layout__tab-panel is-active" id="overview" style="padding: 32px">
  <section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp" style="width: 860px;">
    <div class="mdl-card mdl-cell mdl-cell--12-col">
      <div class="mdl-card__title mdl-color--primary mdl-color-text--white">
        <h4 class="mdl-card__title-text">Accenture SmartVision API Documentation</h4>
      </div>
      <div class="mdl-card mdl-cell mdl-cell--12-col">
          <div class="mdl-card__supporting-text">
            <p>This Documentation is correct as of the <b>09/05/2017</b>.</p>
            <br>
            <p>To access the API, use the following URL:</p>
            <p>https://visual-recognition-rgre2543.c9users.io/api?__________</p>
            <br>
            <p><b>The API Call has 3 of the following parameters (in input order):</b></p>
            <p>- <u>Application:</u> There is currently only one application <i>"image Recognition (ir)".</i></p>
            <p>- <u>Service:</u> Currently the services avaliable are: azure, tensor, yolo, darknet.</p>
            <p>- <u>Image Name:</u> There is 2 ways a user can enter the image file, you can either read an image on the ec2 server <i>"http://52.63.124.131/img/1.jpg"</i> (A file can be ftp'ed onto the server - Please contact your admin), for example <i>"1.jpg"</i> or entering a URL of an image e.g. google (replacing / for +) , for example <i>"http:++52.63.124.131+img+1.jpg"</i>.</p>
            <br>
            <p><b>Full Example:</b></p>
            <p style="display: inline-block; word-wrap: break-word">https://visual-recognition-rgre2543.c9users.io/api?ir/azure/1.jpg or https://visual-recognition-rgre2543.c9users.io/api?ir/azure/http:++52.63.124.131+img+1.jpg</p> 
            <br>
            <p>If you wish to use this application please contact <b>Riley Green</b>.</p>
            <br>
            <hr>
            <p><b><i>Copyright &copy; 2017 - Riley Green</i></b></p>
            <p>Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License.</p>
            <p>Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.</p>
          </div>
      </div>
    </div>
  </section>
</div>
<?php
include 'includes/footer.php';
?>