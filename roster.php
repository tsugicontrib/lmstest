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

$lti13_token_url = LTIX::ltiParameter('lti13_token_url');
$lti13_privkey = LTIX::decrypt_secret(LTIX::ltiParameter('lti13_privkey'));
$lti13_membership_url = LTIX::ltiParameter('lti13_membership_url');
$lti13_client_id = LTIX::ltiParameter('lti13_client_id');

$missing = '';
if ( strlen($lti13_client_id) < 1 ) $missing .= ' ' . 'lti13_client_id';
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
    <li><a href="#tabs-0">Roster</a></li>
    <li><a href="#tabs-3">Debug Log</a></li>
    <li><a href="#tabs-4">With Sourcedids</a></li>
    <li><a href="#tabs-5">Debug Log</a></li>
  </ul>
  <div id="tabs-0">
    <pre>
<?php
$roster_access_token = false;
$debug_log = array();
echo('Calling  $LTI->context->loadNamesAndRoles'."\n");
$nrps = $LTI->context->loadNamesAndRoles(false, $debug_log);
if ( ! $nrps ) {
    echo("Magic failed\n");
} else {
    echo("Loaded ".count($nrps->members)." members\n");
    echo(htmlentities(Output::safe_print_r($nrps)));
}
?>
</pre>
</div>
  <div id="tabs-3">
    <pre>
<?php
if ( count($debug_log) > 0 ) {
    echo(htmlentities(Output::safe_print_r($debug_log)));
}
?>
    </pre>
  </div>
  <div id="tabs-4">
    <pre>
<?php
$roster_access_token = false;
$debug_log = array();
// Note - for now, when we ask for sourcedids, the cert suite fails, (I think)
echo('Calling  $LTI->context->loadNamesAndRoles(with sourcedids)'."\n");
$nrps = $LTI->context->loadNamesAndRoles(true, $debug_log);
if ( ! $nrps ) {
    echo("Magic failed\n");
} else {
    echo("Loaded ".count($nrps->members)." members\n");
    echo(htmlentities(Output::safe_print_r($nrps)));
}
?>
</pre>
</div>
  <div id="tabs-5">
    <pre>
<?php
if ( count($debug_log) > 0 ) {
    echo(htmlentities(Output::safe_print_r($debug_log)));
}
?>
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


