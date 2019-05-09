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

?>
<div id="iframe-dialog" title="Read Only Dialog" style="display: none;">
   <img src="<?= $OUTPUT->getSpinnerUrl() ?>" id="iframe-spinner"><br/>
   <iframe name="iframe-frame" style="height:600px" id="iframe-frame"
    onload="document.getElementById('iframe-spinner').style.display='none';">
   </iframe>
</div>
<div id="tabs">
  <ul>
    <li><a href="#tabs-2">LineItems List</a></li>
    <li><a href="#tabs-2d">Debug Log</a></li>
  </ul>
  <div id="tabs-2">
    <pre>
<?php
$debug_log = array();
$search = false;
$lineitems = $LTI->context->loadLineItems($search, $debug_log);
if ( is_string($lineitems) ) {
    echo($lineitems."\n");
} else {
    echo("Loaded ".count($lineitems)." members\n");
    echo("\nAll lineitems:\n");
    echo(htmlentities(Output::safe_print_r($lineitems)));
    if ( count($lineitems) > 0 && isset($lineitems[0]->id) && is_string($lineitems[0]->id) ) {
        $lineitem_url = $lineitems[0]->id;
        echo("Loading data for the first lineitem...\n");
        $lineitem = $LTI->context->loadLineItem($lineitem_url, $debug_log);
        if ( is_string($lineitem) ) {
            echo("Failed loading ".htmlentities($lineitem_url)."\n");
            echo("Status: ".$lineitem."\n");
        } else {
            echo(htmlentities(Output::safe_print_r($lineitem)));
        }
    } else {
        echo("Did not find valid lineitem to test single item GET...\n");
    }
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


