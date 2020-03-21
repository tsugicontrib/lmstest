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

$filter_tag = U::get($_GET, 'tag', '');
$filter_lti_link_id = U::get($_GET, 'lti_link_id', '');
$filter_resource_id = U::get($_GET, 'resource_id', '');

?>
<div id="iframe-dialog" title="Read Only Dialog" style="display: none;">
   <img src="<?= $OUTPUT->getSpinnerUrl() ?>" id="iframe-spinner"><br/>
   <iframe name="iframe-frame" style="height:600px" id="iframe-frame"
    onload="document.getElementById('iframe-spinner').style.display='none';">
   </iframe>
</div>
<?php
$lineitems_access_token = false;
$grade_token = false;
$debug_log = array();
$url = false;

    $debug_log = array();

    $search = array();
    if ( strlen($filter_tag) > 0 ) $search['tag'] = $filter_tag;
    if ( strlen($filter_lti_link_id) > 0 ) $search['lti_link_id'] = $filter_lti_link_id;
    if ( strlen($filter_resource_id) > 0 ) $search['resource_id'] = $filter_resource_id;
    $lineitems = $LTI->context->loadLineItems($search, $debug_log);

    if ( is_string($lineitems) ) {
        echo($lineitems."\n");
    } else {
        echo("<h1>Retrieved ".count($lineitems)." LineItems</h1>\n");
        echo("<ul>\n");
        foreach($lineitems as $lineitem) {
            if ( ! isset($lineitem->id) ) continue;
            $detail_parms =  "id=".urlencode($lineitem->id);
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
    }
?>
<p>
  <a href="lineitems_add.php" title="Add LineItem" target="iframe-frame"
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

$OUTPUT->footerStart();
$OUTPUT->footerEnd();


