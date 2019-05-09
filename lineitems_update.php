<?php

require_once("../config.php");

use \Tsugi\Core\LTIX;

$LTI = LTIX::requireData();

$lineitem_url = $_REQUEST['id'];
$debug_log = array();

echo("<pre>\n");

$debug_log = array();

if ( isset($_POST['id']) && isset($_POST['scoreMaximum']) ) {

    $newitem = new \stdClass();
    if ( isset($_POST['scoreMaximum']) && $_POST['scoreMaximum'] > 0 ) $newitem->scoreMaximum = $_POST['scoreMaximum']+0;
    if ( isset($_POST['label']) && strlen($_POST['label']) > 0 ) $newitem->label = $_POST['label'];
    if ( isset($_POST['resourceId']) && strlen($_POST['resourceId']) > 0 ) $newitem->resourceId = $_POST['resourceId'];
    if ( isset($_POST['tag']) && strlen($_POST['tag']) > 0 ) $newitem->tag = $_POST['tag'];
    
    echo("Updating line item\n");
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
?>
</pre>
<h1>Update a Lineitem</h1>
<p>Fields left blank will not be updated.</p>
<form method="POST">
<input type="hidden" name="id" value="<?= htmlentities($_REQUEST['id']) ?>">
<p>scoreMaximum <input type="text" name="scoreMaximum" value="<?= htmlentities($scoreMaximum) ?>"></p>
<p>label <input type="text" name="label" value="<?= htmlentities($label) ?>"></p>
<p>resourceId <input type="text" name="resourceId" value="<?= htmlentities($resourceId) ?>"></p>
<p>tag <input type="text" name="tag" value="<?= htmlentities($tag) ?>"></p>
<input type="submit" value="Update LineItem">
<input type="reset" value="Reset">
</form>

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
