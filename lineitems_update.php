<?php

require_once("../config.php");

use \Tsugi\Util\LTI13;

$debug_log = false;
$retval = false;

if ( isset($_POST['token']) && isset($_POST['url']) && isset($_POST['scoreMaximum']) &&
 isset($_POST['label']) && isset($_POST['resourceId']) && isset($_POST['tag']) ) {

    $newitem = new \stdClass();
    if ( $_POST['scoreMaximum'] > 0 ) $newitem->scoreMaximum = $_POST['scoreMaximum']+0;
    if ( strlen($_POST['label']) > 0 ) $newitem->label = $_POST['label'];
    if ( strlen($_POST['resourceId']) > 0 ) $newitem->resourceId = $_POST['resourceId'];
    if ( strlen($_POST['tag']) > 0 ) $newitem->tag = $_POST['tag'];
    
    $debug_log = array();
    $retval = LTI13::createLineItem($_POST['url'], $_POST['token'], $newitem, $debug_log);
}
?>
<h1>Update a Lineitem</h1>
<form method="POST">
<input type="hidden" name="token" value="<?= htmlentities($_REQUEST['token']) ?>">
<input type="hidden" name="url" value="<?= htmlentities($_REQUEST['url']) ?>">
<input type="hidden" name="id" value="<?= htmlentities($_REQUEST['id']) ?>">
<p>scoreMaximum <input type="text" name="scoreMaximum"></p>
<p>label <input type="text" name="label"></p>
<p>resourceId <input type="text" name="resourceId"></p>
<p>tag <input type="text" name="tag"></p>
<input type="submit" value="Add LineItem">
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
if ( $retval ) {
    var_dump($retval);
}
if ( $debug_log ) {
    echo("--- Debug Log --\n");
    var_dump($debug_log);
}
?>
</pre>
