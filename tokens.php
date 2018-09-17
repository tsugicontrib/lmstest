<?php
require_once "../config.php";

use \Tsugi\Core\LTIX;
use \Tsugi\Util\U;
use \Tsugi\Util\LTI13;

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
    <li><a href="#tabs-2">Roster Token</a></li>
    <li><a href="#tabs-3">Assignments (AGS) Token</a></li>
  </ul>
  <div id="tabs-1">
    <pre>
<?php
if ( strlen($lti13_lineitem) > 0 ) {
    $grade_token_data = LTI13::getGradeToken($CFG->wwwroot, $key_key, $lti13_token_url, $lti13_privkey);
    print_r($grade_token_data);
    if ( ! isset($grade_token_data['access_token']) ) {
        $status = U::get($grade_token_data, 'error', 'Did not receive access token');
        error_log($status);
        return $status;
    }
    $grade_access_token = $grade_token_data['access_token'];
    echo("Grade Access Token=".$grade_access_token);
} else {
    echo("Did not receive lineitem url\n");
}
?>
    </pre>
  </div>
  <div id="tabs-2">
    <p>Yada 2</p>
  </div>
  <div id="tabs-3">
    <p>Yada 3</p>
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


