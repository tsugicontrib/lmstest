<?php

require_once("../config.php");

use \Tsugi\Core\LTIX;
use \Tsugi\Util\U;

function tweak($postval) {
    if ( strcasecmp($postval, 'true') == 0 ) return true;
    if ( strcasecmp($postval, 'false') == 0 ) return false;
    if ( strcasecmp($postval, 'null') == 0 ) return null;
    if ( intval($postval) && strpos($postval, '.') === false ) return intval($postval);
    if ( floatval($postval) ) return floatval($postval);
    return $postval;
}

$LTI = LTIX::requireData();

$lineitem_url = $_REQUEST['id'];

echo("<pre>\n");

$debug_log = array();
if ( isset($_POST['id']) && isset($_POST['scoreMaximum']) ) {

    $newitem = new \stdClass();
    if ( isset($_POST['scoreMaximum']) && $_POST['scoreMaximum'] > 0 ) $newitem->scoreMaximum = $_POST['scoreMaximum']+0;
    if ( isset($_POST['label']) && strlen($_POST['label']) > 0 ) $newitem->label = $_POST['label'];
    if ( isset($_POST['resourceId']) && strlen($_POST['resourceId']) > 0 ) $newitem->resourceId = $_POST['resourceId'];
    if ( isset($_POST['tag']) && strlen($_POST['tag']) > 0 ) $newitem->tag = $_POST['tag'];
    if ( strlen(U::get($_POST,'startDateTime')) > 0 ) $newitem->startDateTime = U::get($_POST,'startDateTime');
    if ( strlen(U::get($_POST,'endDateTime')) > 0 ) $newitem->endDateTime = U::get($_POST,'endDateTime');
    if ( strlen($_POST['key1']) > 0 && strlen($_POST['val1']) > 0 ) $newitem->{$_POST['key1']} = tweak($_POST['val1']);
    if ( strlen($_POST['key2']) > 0 && strlen($_POST['val2']) > 0 ) $newitem->{$_POST['key2']} = tweak($_POST['val2']);
    
    echo("Updating line item\n");
    var_dump($newitem);
    $retval = $LTI->context->updateLineItem($lineitem_url, $newitem, $debug_log);

    if ( $retval ) {
        var_dump($retval);
    }
    if ( $debug_log ) {
        echo("--- Debug Log --\n");
        var_dump($debug_log);
    }
    echo("</pre>\n");
    return;
}

$lineitem = $LTI->context->loadLineItem($lineitem_url, $debug_log);
if ( is_string($lineitem) ) {
    echo("Failed loading ".htmlentities($lineitem_url)."\n");
    echo("Status: ".$lineitem."\n");
    return;
}

$label = isset($lineitem->label) ? $lineitem->label : '';
$tag = isset($lineitem->tag) ? $lineitem->tag : '';
$scoreMaximum = isset($lineitem->scoreMaximum) ? $lineitem->scoreMaximum : '';
$resourceId = isset($lineitem->resourceId) ? $lineitem->resourceId : '';
$startDateTime = isset($lineitem->startDateTime) ? $lineitem->startDateTime : '';
$endDateTime = isset($lineitem->endDateTime) ? $lineitem->endDateTime : '';
?>
</pre>
<h1>Update a Lineitem</h1>
<p>Fields left blank will not be updated.</p>
<p>
<form method="POST">
<input type="hidden" name="id" value="<?= htmlentities($_REQUEST['id']) ?>">
<p>scoreMaximum <input type="text" name="scoreMaximum" value="<?= htmlentities($scoreMaximum) ?>"></p>
<p>label <input type="text" name="label" value="<?= htmlentities($label) ?>"></p>
<p>resourceId <input type="text" name="resourceId" value="<?= htmlentities($resourceId) ?>"></p>
<p>tag <input type="text" name="tag" value="<?= htmlentities($tag) ?>"></p>
<p>endDateTime<input type="text" name="endDateTime" placeholder="2022-08-11T01:31:07Z" value="<?= htmlentities($endDateTime) ?>"></p>
<p>startDateTime<input type="text" name="startDateTime" placeholder="2022-08-11T01:31:07Z" value="<?= htmlentities($startDateTime) ?>"></p>
<p><input type="text" name="key1">: <input type="text" name="val1"></p>
<p><input type="text" name="key2">: <input type="text" name="val2"></p>
<input type="submit" value="Update LineItem">
<input type="reset" value="Reset">
</form>
</p>
<p>
Some extensions
<pre>
https://www.sakailms.org/spec/lti-ags/v2p0/releaseToStudent true
https://www.sakailms.org/spec/lti-ags/v2p0/includeInComputation false
</pre>
</p>

<!--
{
  "scoreMaximum" : 60,
  "label" : "Chapter 5 Test",
  "resourceId" : "quiz-231",
  "tag" : "grade"
}
-->

<pre>
<?php
if ( $debug_log ) {
    echo("--- Debug Log --\n");
    var_dump($debug_log);
}
?>
</pre>
