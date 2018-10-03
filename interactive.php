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

$filter_tag = U::get($_GET, 'tag', '');
$filter_lti_link_id = U::get($_GET, 'lti_link_id', '');
$filter_resource_id = U::get($_GET, 'resource_id', '');

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

$url = false;
if ( $lineitems_access_token ) {
    $debug_log = array();
    $url = $lti13_lineitems;
    if ( strlen($filter_tag) > 0 ) $url = U::add_url_parm($url, 'tag', $filter_tag);
    if ( strlen($filter_lti_link_id) > 0 ) $url = U::add_url_parm($url, 'lti_link_id', $filter_lti_link_id);
    if ( strlen($filter_resource_id) > 0 ) $url = U::add_url_parm($url, 'resource_id', $filter_resource_id);
    $lineitems = LTI13::loadLineItems($url, $lineitems_access_token, $debug_log);
    if ( is_string($lineitems) ) {
        echo($lineitems."\n");
    } else {
        echo("<h1>Retrieved ".count($lineitems)." LineItems</h1>\n");
        echo("<ul>\n");
        foreach($lineitems as $lineitem) {
            $detail_parms = $parms . "&id=".urlencode($lineitem->id);
            echo("<li>\n");
            echo(htmlentities($lineitem->label)) ;
            $auto_created = isset($lineitem->ltiLinkId);
            if ( $auto_created ) echo(" (auto-created) ");
?>
 (
  <a href="lineitems_detail.php?<?= $detail_parms ?>" title="detail" target="iframe-frame"
  onclick="showModalIframe(this.title, 'iframe-dialog', 'iframe-frame', _TSUGI.spinnerUrl, true); return true;" >
  Detail</a> | 
  <a href="lineitems_score.php?<?= $detail_parms ?>" title="score" target="iframe-frame"
  onclick="showModalIframe(this.title, 'iframe-dialog', 'iframe-frame', _TSUGI.spinnerUrl, true); return true;" >
  Send Score</a> | 
  <a href="lineitems_results.php?<?= $detail_parms ?>" title="results" target="iframe-frame"
  onclick="showModalIframe(this.title, 'iframe-dialog', 'iframe-frame', _TSUGI.spinnerUrl, true); return true;" >
  Show Results</a> 
<?php if ( ! $auto_created ) { ?>
  | 
  <a href="lineitems_update.php?<?= $detail_parms ?>" title="update" target="iframe-frame"
  onclick="showModalIframe(this.title, 'iframe-dialog', 'iframe-frame', _TSUGI.spinnerUrl, true); return true;" >
  Update</a> |
  <a href="lineitems_delete.php?<?= $detail_parms ?>" title="delete" target="iframe-frame"
  onclick="showModalIframe(this.title, 'iframe-dialog', 'iframe-frame', _TSUGI.spinnerUrl, true); return true;" >
  Delete</a>
<?php } ?>
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
<h2>Filter Values</h2>
<p>
<form>
<p>resource_id <input type="text" name="resource_id" value="<?= htmlentities($filter_resource_id) ?>"></p>
<p>tag <input type="text" name="tag" value="<?= htmlentities($filter_tag) ?>"></p>
<p>lti_link_id <input type="text" name="lti_link_id" value="<?= htmlentities($filter_lti_link_id) ?>"></p>
<input type="submit" value="Filter Results">
</form>
</p>
<?php
        if ( $url ) {
            echo("<p>LineItems Url: ".htmlentities($url)."</p>\n");
        }
    }
} else {
    echo("Did not get lineitems_access_token\n");
}

$OUTPUT->footerStart();
$OUTPUT->footerEnd();


