<?php
include 'includes/header.php';
?>
<div class="mdl-layout__tab-panel is-active" id="overview" style="padding: 32px">
  <section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp" style="width: 860px;">
    <div class="mdl-card mdl-cell mdl-cell--12-col">
      <div class="mdl-card__title mdl-color--primary mdl-color-text--white">
        <h4 class="mdl-card__title-text">Welcome</h4>
      </div>
      <div class="mdl-card mdl-cell mdl-cell--12-col">
          <div class="mdl-card__supporting-text">
            <p style="text-align:center"><img src="<?php echo $url ?>/assets/img/welcome.jpg" width="100%"></img></p>
            <p><b>SmartVision</b> was developed by Riley Green <i>(In collaboration with Accenture and the University of Sydney)</i> for the Purpose of Prototyping various OCR and Image Recognition Technologies.</p>
            <p>SmartVision is also accessable from an API, the documentation can be viewed below.</p>
            <p>If you wish to use this application please contact <b>Riley Green</b>.</p>
            <br>
            <p><a href="https://visual-recognition-rgre2543.c9users.io/api/documentation" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent" style="margin-right: 10px">API Documentation</a><a href="mailto:riley.green@accenture.com?Subject=Image%20Recognition%20Application" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">Contact</a></p>
            <br>
            <p><b><i>Copyright &copy; 2017</i></b></p>
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