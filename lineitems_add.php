<?php

require_once("../config.php");

use \Tsugi\Util\U;
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

if ( isset($_POST['scoreMaximum']) && isset($_POST['label']) && isset($_POST['resourceId']) ) {

    $newitem = new \stdClass();
    $newitem->scoreMaximum = U::get($_POST,'scoreMaximum');
    $newitem->label = U::get($_POST,'label');
    $newitem->resourceId = U::get($_POST,'resourceId');
    if ( strlen(U::get($_POST,'tag')) > 0 ) $newitem->tag = U::get($_POST,'tag');
    if ( strlen(U::get($_POST,'key1')) > 0 && strlen(U::get($_POST,'val1')) > 0 ) $newitem->{$_POST['key1']} = tweak($_POST['val1']);
    if ( strlen(U::get($_POST,'key2')) > 0 && strlen(U::get($_POST,'val2')) > 0 ) $newitem->{$_POST['key2']} = tweak($_POST['val2']);
    if ( strlen(U::get($_POST,'startDateTime')) > 0 ) $newitem->startDateTime = U::get($_POST,'startDateTime');
    if ( strlen(U::get($_POST,'endDateTime')) > 0 ) $newitem->endDateTime = U::get($_POST,'endDateTime');
    
    $debug_log = array();
    $retval = $LTI->context->createLineItem($newitem, $debug_log);
    echo("<pre>\n");var_dump($retval);echo("</pre>\n");
}
?>
<h1>Add a new Lineitem</h1>
<form method="POST">
<p>scoreMaximum (*) <input type="text" name="scoreMaximum"></p>
<p>label (*) <input type="text" name="label"></p>
<p>resourceId <input type="text" name="resourceId"></p>
<p>tag <input type="text" name="tag"></p>
<p>startDateTime<input type="text" name="startDateTime" placeholder="2022-08-11T01:31:07Z"></p>
<p>endDateTime<input type="text" name="endDateTime" placeholder="2022-08-11T01:31:07Z"></p>
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
