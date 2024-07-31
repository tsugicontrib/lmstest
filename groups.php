<?php
require_once "../config.php";

use \Tsugi\Core\LTIX;
use \Tsugi\Util\U;
use \Tsugi\UI\Output;

require_once "util.php";

// Handle all forms of launch
$LTI = LTIX::requireData();

require_once("nav.php");

// Render view
$OUTPUT->header();
$OUTPUT->bodyStart();
$OUTPUT->topNav($menu);

?>
<div id="tabs">
  <ul>
    <li><a href="#tabs-1">Groups</a></li>
    <li><a href="#tabs-1d">Debug Log</a></li>
    <li><a href="#tabs-2">Groups for a User</a></li>
    <li><a href="#tabs-2d">Debug Log</a></li>
  </ul>
  <div id="tabs-1">
    <pre>
<?php
$roster_access_token = false;
$debug_log = array();
echo('Calling  $LTI->context->loadAllGroups'."\n");
$lti_groups = $LTI->context->loadAllGroups($debug_log);
if ( ! $lti_groups ) {
    echo("Unable to load groups\n");
} else if ( is_string($lti_groups) ) {
    echo("Unable to load groups\n\n");
    echo(htmlentities($lti_groups));
} else if ( ! is_object($lti_groups) ) {
    echo("Unable to load groups\n\n");
    var_dump($lti_groups);
} else if ( !isset($lti_groups->groups) || !is_array($lti_groups->groups) ) {
    echo("context->loadGroups groups data incorrect format\n");
    var_dump($lti_groups);
} else {
    echo("Loaded ".count($lti_groups->groups)." groups\n");
    echo(htmlentities(Output::safe_print_r($lti_groups)));
}
?>
</pre>
</div>
<?php print_debug_log("tabs-1d", $debug_log); ?>
<div id="tabs-2">
    <pre>
<?php
$roster_access_token = false;
$debug_log = array();
$subject_key = $LTI->ltiParameter("subject_key");
echo('Calling  $LTI->context->loadGroups("'.$subject_key.'")'."\n");
$nrps = $LTI->context->loadGroups($subject_key, $debug_log);
if ( ! $nrps ) {
    echo("context->loadGroups retrieval failed\n");
} else if ( is_string($nrps) ) {
    echo("Unable to load groups\n\n");
    echo(htmlentities($nrps));
} else if ( ! is_object($nrps) ) {
    echo("Unable to load names and roles\n\n");
    var_dump($nrps);
} else if ( !isset($nrps->groups) || !is_array($nrps->groups) ) {
    echo("context->loadGroups groups data incorrect format\n");
    var_dump($nrps);
} else {
    echo("Loaded ".count($nrps->groups)." groups\n");
    echo(htmlentities(Output::safe_print_r($nrps)));
}
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


