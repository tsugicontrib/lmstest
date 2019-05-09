<?php

require_once("../config.php");

use \Tsugi\UI\Output;
use \Tsugi\Core\LTIX;

$LTI = LTIX::requireData();

$retval = false;

$lineitem_url = $_REQUEST['id'];
$debug_log = array();

echo("<pre>\n");

if ( isset($_POST['id']) && isset($_POST['doDelete']) ) {

    $debug_log = array();
    echo("Deleting line item: ".$_POST['id']."\n");
    $retval = $LTI->context->deleteLineItem($_POST['id'], $debug_log);

    if ( $retval ) {
        var_dump($retval);
    }
    if ( $debug_log ) {
        echo("--- Debug Log --\n");
        var_dump($debug_log);
    }
    return;
}

$lineitem = $LTI->context->loadLineItem($lineitem_url, $debug_log);
if ( is_string($lineitem) ) {
    echo("Failed loading ".htmlentities($lineitem_url)."\n");
    echo("Status: ".$lineitem."\n");
    return;
}

?>
</pre>
<h1>Delete Lineitem</h1>
<p><?= htmlentities($lineitem->label) ?></p>
<form method="POST">
<input type="hidden" name="id" value="<?= htmlentities($_REQUEST['id']) ?>">
<input type="submit" name="doDelete" value="Delete LineItem">
</form>
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
