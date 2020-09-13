<?php

require_once("../config.php");

use \Tsugi\Core\LTIX;

// Handle all forms of launch
$LTI = LTIX::requireData();

$debug_log = false;
$retval = false;

function tweak($postval) {
    if ( strcasecmp($postval, 'true') == 0 ) return true;
    if ( strcasecmp($postval, 'false') == 0 ) return false;
    if ( strcasecmp($postval, 'null') == 0 ) return null;
    if ( intval($postval) && strpos($postval, '.') === false ) return intval($postval);
    if ( floatval($postval) ) return floatval($postval);
    return $postval;
}

if ( isset($_POST['token']) && isset($_POST['url']) && isset($_POST['scoreMaximum']) &&
 isset($_POST['label']) && isset($_POST['resourceId']) && isset($_POST['tag']) ) {

    $newitem = new \stdClass();
    if ( $_POST['scoreMaximum'] > 0 ) $newitem->scoreMaximum = $_POST['scoreMaximum']+0;
    if ( strlen($_POST['label']) > 0 ) $newitem->label = $_POST['label'];
    if ( strlen($_POST['resourceId']) > 0 ) $newitem->resourceId = $_POST['resourceId'];
    if ( strlen($_POST['tag']) > 0 ) $newitem->tag = $_POST['tag'];
    if ( strlen($_POST['key1']) > 0 && strlen($_POST['val1']) > 0 ) $newitem->{$_POST['key1']} = tweak($_POST['val1']);
    if ( strlen($_POST['key2']) > 0 && strlen($_POST['val2']) > 0 ) $newitem->{$_POST['key2']} = tweak($_POST['val2']);
    
    $debug_log = array();
    $retval = $LTI->context->createLineItem($newitem, $debug_log);
}
?>
<h1>Add a new Lineitem</h1>
<form method="POST">
<input type="hidden" name="token" value="<?= htmlentities($_REQUEST['token']) ?>">
<input type="hidden" name="url" value="<?= htmlentities($_REQUEST['url']) ?>">
<p>scoreMaximum (*) <input type="text" name="scoreMaximum"></p>
<p>label (*) <input type="text" name="label"></p>
<p>resourceId <input type="text" name="resourceId"></p>
<p>tag <input type="text" name="tag"></p>
<p>Extensions (key:value)</p>
<p><input type="text" name="key1">: <input type="text" name="val1"></p>
<p><input type="text" name="key2">: <input type="text" name="val2"></p>
<input type="submit" value="Add LineItem">
</form>
</p>
<p>
Two Sakai extensions
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
if ( $retval ) {
    var_dump($retval);
}
if ( $debug_log ) {
    echo("--- Debug Log --\n");
    var_dump($debug_log);
}
?>
</pre>
