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
$OUTPUT->welcomeUserCourse();
?>
<h1>Send a score for current user</h1>
<?php
if ( $LTI->user->instructor ) {
   echo("<p style=\"color:red;\">Note: Instructors can't send grades to most LMS systems</p>\n\n");
}
?>
<pre>
Lineitem: <?= htmlentities($lineitem) ?>
</pre>
<?php if ( ! ($grade || $delete) ) { ?>
<form>
<input type="hidden" name="id" value="<?= htmlentities($_REQUEST['id']) ?>">
<p>grade (*) <input type="text" name="grade"></p>
<p>scoreMaximum <input type="text" name="scoreMaximum"></p>
<p>comment <input type="text" name="comment"></p>
<input type="submit" name="send" value="Send Score">
<input type="submit" name="delete" value="Delete Score">
</form>
<p>
Note that sending a score with a <b>scoreMaximum</b> does not
change the stored <b>scoreMaximum</b> for the lineitem.
It is simply the "denominator" used to scale the score to match the
stored <b>scoreMaximum</b> for the lineitem.  You need to Update the LineItem to change
the stored <b>scoreMaximum</b>.  If the <b>scoreMaximum</b> in this message matches
the <b>scoreMaximum</b> value in the gradebook the score is updated.  If there is a mis-match
the score is scaled.
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
