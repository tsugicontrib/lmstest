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
$lti13_lineitems = LTIX::ltiParameter('lti13_lineitems');
$lti13_client_id = LTIX::ltiParameter('lti13_client_id');

$missing = '';
if ( strlen($lti13_client_id) < 1 ) $missing .= ' ' . 'client_id';
if ( strlen($lti13_privkey) < 1 ) $missing .= ' ' . 'private_key';
if ( strlen($lti13_token_url) < 1 ) $missing .= ' ' . 'token_url';

if ( strlen($missing) > 0 ) {
    echo('Missing '.$missing);
    $OUTPUT->footer();
    return;
}

?>
<div id="iframe-dialog" title="Read Only Dialog" style="display: none;">
   <img src="<?= $OUTPUT->getSpinnerUrl() ?>" id="iframe-spinner"><br/>
   <iframe name="iframe-frame" style="height:600px" id="iframe-frame"
    onload="document.getElementById('iframe-spinner').style.display='none';">
   </iframe>
</div>
<div id="tabs">
  <ul>
    <li><a href="#tabs-1">Token</a></li>
    <li><a href="#tabs-2">LineItems List</a></li>
    <li><a href="#tabs-3">Debug Log</a></li>
  </ul>
  <div id="tabs-1">
    <pre>
<?php
$lineitems_access_token = false;
$debug_log = array();
if ( strlen($lti13_lineitems) > 0 && strlen($lti13_lineitems) > 0 ) {
    echo("Token URL: ".$lti13_token_url."\n");
    $lineitems_token_data = LTI13::getLineItemsToken($CFG->wwwroot, $lti13_client_id, $lti13_token_url, $lti13_privkey, $debug_log);
    if ( $lineitems_token_data ) {
        echo(htmlentities(Output::safe_print_r($lineitems_token_data)));
    } else {
        var_dump($lineitems_token_data);
    }
    if ( isset($lineitems_token_data['access_token']) ) {
        $lineitems_access_token = $lineitems_token_data['access_token'];
        echo("LineItems Access Token=".$lineitems_access_token."\n");
        $required_fields = false;
        $jwt = LTI13::parse_jwt($lineitems_access_token, $required_fields);
        if ( ! is_string($jwt) ) print_jwt($jwt);
    } else {
        $status = U::get($lineitems_token_data, 'error', 'Did not receive access token');
        error_log($status);
        echo($status."\n");
        echo("Private Key: ".substr($lti13_privkey, 0, 50)."\n");
    }
} else {
    echo("Did not receive lineitems url\n");
}
?>
    </pre>
  </div>
  <div id="tabs-2">
    <pre>
<?php
if ( $lineitems_access_token ) {
    echo("LineItems URL: ".$lti13_lineitems."\n");
    $debug_log = array();
    $lineitems = LTI13::loadLineItems($lti13_lineitems, $lineitems_access_token, $debug_log);
    if ( is_string($lineitems) ) {
        echo($lineitems."\n");
    } else {
        echo("Loaded ".count($lineitems)." members\n");
        echo("\nAll lineitems:\n");
        echo(htmlentities(Output::safe_print_r($lineitems)));
        if ( count($lineitems) > 0 && isset($lineitems[0]->id) && is_string($lineitems[0]->id) ) {
            $lineitem_url = $lineitems[0]->id;
            echo("Loading data for the first lineitem...\n");
            $lineitem = LTI13::loadLineItem($lineitem_url, $lineitems_access_token, $debug_log);
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
} else {
    echo("Did not get lineitems_access_token\n");
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


