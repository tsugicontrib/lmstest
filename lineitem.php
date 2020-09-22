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
   echo("Note: Instructors can't send grades to most LMS systems\n\n");
}
$debug_log = array();
$retval = $LTI->result->gradeSend(0.85, false, $debug_log);
echo("Sent score of 0.85, result:\n");
echo(Output::safe_var_dump($retval));
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

