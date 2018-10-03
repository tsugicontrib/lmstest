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

$lti13_lineitems = LTIX::ltiParameter('lti13_lineitems');

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
<div id="iframe-dialog" title="Read Only Dialog" style="display: none;">
   <img src="<?= $OUTPUT->getSpinnerUrl() ?>" id="iframe-spinner"><br/>
   <iframe name="iframe-frame" style="height:600px" id="iframe-frame"
    onload="document.getElementById('iframe-spinner').style.display='none';">
   </iframe>
</div>
<?php
$lineitems_access_token = false;
$debug_log = array();
$parms = false;
if ( strlen($lti13_lineitems) > 0 ) {
    $lineitems_token_data = LTI13::getLineItemsToken($CFG->wwwroot, $key_key, $lti13_token_url, $lti13_privkey);
    if ( ! isset($lineitems_token_data['access_token']) ) {
        $status = U::get($lineitems_token_data, 'error', 'Did not receive access token');
        error_log($status);
        return $status;
    }
    $lineitems_access_token = $lineitems_token_data['access_token'];
    $required_fields = false;
    $jwt = LTI13::parse_jwt($lineitems_access_token, $required_fields);
    if ( $lineitems_access_token && strlen($lti13_lineitems) > 0 ) {
        $parms = "token=".urlencode($lineitems_access_token) . "&url=" . urlencode($lti13_lineitems);
    }
} else {
    echo("Did not receive lineitems url\n");
}

if ( $lineitems_access_token ) {
    $debug_log = array();
    $lineitems = LTI13::loadLineItems($lti13_lineitems, $lineitems_access_token, $debug_log);
    if ( is_string($lineitems) ) {
        echo($lineitems."\n");
    } else {
        echo("<h1>Retrieved ".count($lineitems)." LineItems</h1>\n");
        echo("<ul>\n");
        foreach($lineitems as $lineitem) {
$detail_parms = $parms . "&id=".urlencode($lineitem->id);
?>
<li>
  <?= htmlentities($lineitem->label) ?>  (
  <a href="lineitems_detail.php?<?= $detail_parms ?>" title="detail" target="iframe-frame"
  onclick="showModalIframe(this.title, 'iframe-dialog', 'iframe-frame', _TSUGI.spinnerUrl, true); return true;" >
  Detail
  </a> | 
  <a href="lineitems_delete.php?<?= $detail_parms ?>" title="delete" target="iframe-frame"
  onclick="showModalIframe(this.title, 'iframe-dialog', 'iframe-frame', _TSUGI.spinnerUrl, true); return true;" >
  Delete
  </a> | 
  <a href="lineitems_update.php?<?= $detail_parms ?>" title="update" target="iframe-frame"
  onclick="showModalIframe(this.title, 'iframe-dialog', 'iframe-frame', _TSUGI.spinnerUrl, true); return true;" >
  Update
  </a>
)
</li>
<?php
        }
        echo("</ul>\n");
?>
<p>
  <a href="lineitems_add.php?<?= $parms ?>" title="Add LineItem" target="iframe-frame"
  onclick="showModalIframe(this.title, 'iframe-dialog', 'iframe-frame', _TSUGI.spinnerUrl, true); return true;" >
  Add LineItem
  </a>
</p>
<?php
    }
} else {
    echo("Did not get lineitems_access_token\n");
}

$OUTPUT->footerStart();
$OUTPUT->footerEnd();


