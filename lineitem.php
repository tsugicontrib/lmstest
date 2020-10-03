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
    <li><a href="#tabs-1">Grade Send</a></li>
    <li><a href="#tabs-1d">Debug Log</a></li>
  </ul>
  <div id="tabs-1">
    <pre>
<?php
if ( $LTI->user->instructor ) {
   echo("Note: Most LMS systems will not store instructor grades\n\n");
}
$debug_log = array();
$retval = $LTI->result->gradeSend(0.85, false, $debug_log);
$transport = $LTI->result->lastSendTransport;
if ( $retval === true && $transport ) {
    echo("Sent score of 0.85 via ".htmlentities($transport).", result:\n");
    echo(Output::safe_var_dump($retval));
} else if ( $retval === true ) {
    echo("Score of 0.85 was stored lcally (i.e. not sent to the server)\n");
} else {
    echo("Grade failure: ".htmlentities($retval)."\n");
}
echo("<hr/>\n");
echo("Debug Log:\n");
var_dump($debug_log);
?>
    </pre>
  </div>
<?php print_debug_log("tabs-1d", $debug_log); ?>
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

