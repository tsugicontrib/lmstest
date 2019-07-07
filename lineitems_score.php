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
?>
<h1>Send a grade</h1>
<pre>
Lineitem: <?= htmlentities($lineitem) ?>
</pre>
<?php if ( ! $grade ) { ?>
<form>
<input type="hidden" name="url" value="<?= htmlentities($_REQUEST['url']) ?>">
<input type="hidden" name="id" value="<?= htmlentities($_REQUEST['id']) ?>">
<p>grade <input type="text" name="grade"></p>
<input type="submit" value="Send Score">
</form>
<?php
    return;
}

echo("<pre>\n");

$comment = "Sending grade $grade subject_key=$subject_key";

$retval = $LTI->context->sendLineItemResult($lineitem, $subject_key, $grade, $comment, $debug_log);

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
