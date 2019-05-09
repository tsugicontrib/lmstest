<?php
require_once "../config.php";

use \Tsugi\Core\LTIX;
use \Tsugi\Util\U;
use \Tsugi\Util\LTI13;
use \Tsugi\UI\Output;

require_once "util.php";

// Handle all forms of launch
$LTI = LTIX::requireData();

// Render view
$OUTPUT->header();
$OUTPUT->bodyStart();
$OUTPUT->topNav();

require_once("nav.php");

$key_key = LTIX::ltiParameter('key_key');
$lti13_token_url = LTIX::ltiParameter('lti13_token_url');
$lti13_privkey = LTIX::decrypt_secret(LTIX::ltiParameter('lti13_privkey'));

$lti13_lineitem = LTIX::ltiParameter('lti13_lineitem');

$missing = '';
if ( strlen($key_key) < 1 ) $missing .= ' ' . 'key_key';
if ( strlen($lti13_privkey) < 1 ) $missing .= ' ' . 'private_key';
if ( strlen($lti13_token_url) < 1 ) $missing .= ' ' . 'token_url';

if ( strlen($missing) > 0 ) {
    echo('Missing '.$missing);
    $OUTPUT->footer();
    return;
}

?>
<div id="tabs">
  <ul>
    <li><a href="#tabs-1">Grade Token</a></li>
    <li><a href="#tabs-1d">Debug Log</a></li>
    <li><a href="#tabs-2">Grade Send</a></li>
    <li><a href="#tabs-2d">Debug Log</a></li>
  </ul>
  <div id="tabs-1">
    <pre>
<?php
$debug_log = array();
if ( strlen($lti13_lineitem) > 0 ) {
    echo("Line Item URL: ".$lti13_lineitem."\n");
    $grade_access_token = LTI13::getGradeToken($CFG->wwwroot, $key_key, $lti13_token_url, $lti13_privkey, $debug_log);
    echo("Grade Access Token=".$grade_access_token);
} else {
    echo("Did not receive lineitem url\n");
}
?>
    </pre>
  </div>
<?php print_debug_log("tabs-1d", $debug_log); ?>
  <div id="tabs-2">
    <pre>
<?php
if ( $LTI->user->instructor ) {
   echo("Note: Instructors can't send grades to most LMS systems\n\n");
}
$debug_log = array();
$retval = $LTI->result->gradeSend(0.95, false, $debug_log);
echo("Result of grade_send:\n");
echo(Output::safe_var_dump($retval));
?>
    </pre>
  </div>
<?php print_debug_log("tabs-2d", $debug_log); ?>
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


