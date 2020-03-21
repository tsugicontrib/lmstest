<?php
// https://github.com/tsugicontrib/lmstest
require_once "../config.php";

use \Tsugi\Core\LTIX;
use \Tsugi\Util\Net;
use \Tsugi\Util\U;
use \Tsugi\Util\LTI13;
use \Tsugi\UI\Output;

require_once "util.php";

// Handle all forms of launch
$LTI = LTIX::requireData();

require_once("nav.php");

// Render view
$OUTPUT->header();
$OUTPUT->bodyStart();
$OUTPUT->topNav($menu);


$OUTPUT->welcomeUserCourse();

$sc = $_SESSION;  // Make a copy;
$post = $sc['lti_post'];
unset($sc['lti_post']);
$lti_data = $sc['lti'];
unset($sc['lti']);

$raw_jwt = U::get($post, 'id_token');
$jwt = LTI13::parse_jwt($raw_jwt);

function preSafe($var) {
echo("<pre>\n");
echo(Output::safe_var_dump($var));
echo("\n</pre>\n");
}

?>
<div id="tabs">
  <ul>
    <li><a href="#tabs-1">LTI Post Data</a></li>
    <li><a href="#tabs-2">Tsugi LTI Data</a></li>
    <li><a href="#tabs-3">Tsugi Session Data</a></li>
    <li><a href="#tabs-4">Tsugi User Object</a></li>
  </ul>
  <div id="tabs-1">
    <pre>
<?php 
    echo(Output::safe_var_dump($post));
    print_jwt($jwt);
?>
    </pre>
  </div>
  <div id="tabs-2">
    <?php preSafe($lti_data); ?>
  </div>
  <div id="tabs-3">
    <?php preSafe($sc); ?>
  </div>
  <div id="tabs-4">
    <pre>
<?php var_dump($USER); ?>
    </pre>
  </div>
</div>

<?php
$OUTPUT->footerStart();
?>
<script>
  $( function() {
    $( "#tabs" ).tabs();
  } );
  </script>
<?php
$OUTPUT->footerEnd();
