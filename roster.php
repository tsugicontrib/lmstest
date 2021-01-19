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
    <li><a href="#tabs-1">Roster</a></li>
    <li><a href="#tabs-1d">Debug Log</a></li>
    <li><a href="#tabs-2">With Sourcedids</a></li>
    <li><a href="#tabs-2d">Debug Log</a></li>
  </ul>
  <div id="tabs-1">
    <pre>
<?php
$roster_access_token = false;
$debug_log = array();
echo('Calling  $LTI->context->loadNamesAndRoles'."\n");
$nrps = $LTI->context->loadNamesAndRoles(false, $debug_log);
if ( ! $nrps ) {
    echo("Unable to load names and roles\n");
} else if ( is_string($nrps) ) {
    echo("Unable to load names and roles\n\n");
    echo(htmlentities($nrps));
} else if ( ! is_object($nrps) ) {
    echo("Unable to load names and roles\n\n");
    var_dump($nrps);
} else {
    echo("Loaded ".count($nrps->members)." members\n");
    echo(htmlentities(Output::safe_print_r($nrps)));
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


