<?php

require_once("../config.php");

use \Tsugi\UI\Output;
use \Tsugi\Util\U;
use \Tsugi\Core\LTIX;

$LTI = LTIX::requireData();

$debug_log = array();
$retval = false;

$lineitem = $_REQUEST['id'];

$subject_key = LTIX::ltiParameter('subject_key');

$missing = '';
if ( strlen($subject_key) < 1 ) $missing .= ' ' . 'subject_key';

if ( strlen($missing) > 0 ) {
    echo("<pre>\n");
    echo('Missing '.$missing);
    return;
}

$grade = U::get($_REQUEST,'grade');
$scoreMaximum = U::get($_REQUEST,'scoreMaximum');
$comment = U::get($_REQUEST,'comment');
$delete = U::get($_REQUEST,'delete');
?>
<h1>Send a grade</h1>
<pre>
Lineitem: <?= htmlentities($lineitem) ?>
</pre>
<?php if ( ! ($grade || $delete) ) { ?>
<form>
<input type="hidden" name="id" value="<?= htmlentities($_REQUEST['id']) ?>">
<p>grade <input type="text" name="grade"></p>
<p>scoreMaximum <input type="text" name="scoreMaximum"></p>
<p>comment <input type="text" name="comment"></p>
<input type="submit" name="send" value="Send Score">
<input type="submit" name="delete" value="Delete Score">
</form>
<?php
    return;
}

echo("<pre>\n");

// TODO: Maybe we should make delete its own method
// Per Eric Preston, since we are overloading update - comments should work with null grades
$retval = $LTI->context->sendLineItemResult($lineitem, $subject_key, $grade, $scoreMaximum, $comment, $debug_log);

if ( $retval ) {
    echo("\nReturn value\n");
    var_dump($retval);
}
if ( $debug_log ) {
    echo("--- Debug Log --\n");
    var_dump($debug_log);
}
?>
</pre>
