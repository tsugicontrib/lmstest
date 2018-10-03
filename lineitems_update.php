<?php

require_once("../config.php");

use \Tsugi\Util\LTI13;

$lineitem_url = $_REQUEST['id'];
$token = $_REQUEST['token'];
$debug_log = array();

if ( isset($_POST['token']) && isset($_POST['id']) && isset($_POST['scoreMaximum']) ) {

    $newitem = new \stdClass();
    if ( isset($_POST['scoreMaximum']) && $_POST['scoreMaximum'] > 0 ) $newitem->scoreMaximum = $_POST['scoreMaximum']+0;
    if ( isset($_POST['label']) && strlen($_POST['label']) > 0 ) $newitem->label = $_POST['label'];
    if ( isset($_POST['resourceId']) && strlen($_POST['resourceId']) > 0 ) $newitem->resourceId = $_POST['resourceId'];
    if ( isset($_POST['tag']) && strlen($_POST['tag']) > 0 ) $newitem->tag = $_POST['tag'];
    
    $debug_log = array();
    $retval = LTI13::updateLineItem($_POST['id'], $_POST['token'], $newitem, $debug_log);
    echo("<pre>\n");

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

$lineitem = LTI13::loadLineItem($lineitem_url, $token, $debug_log);
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
<h1>Update a Lineitem</h1>
<p>Fields left blank will not be updated.</p>
<form method="POST">
<input type="hidden" name="token" value="<?= htmlentities($_REQUEST['token']) ?>">
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
