<?php

require_once("../config.php");

use \Tsugi\UI\Output;
use \Tsugi\Util\LTI13;

$debug_log = false;
$retval = false;

$lineitem_url = $_REQUEST['id'];
$token = $_REQUEST['token'];
$debug_log = array();

if ( isset($_POST['token']) && isset($_POST['id']) && isset($_POST['doDelete']) ) {

    $debug_log = array();
    $retval = LTI13::deleteLineItem($_POST['url'], $_POST['token'], $debug_log);
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

?>
<h1>Delete Lineitem</h1>
<p><?= htmlentities($lineitem->label) ?></p>
<form method="POST">
<input type="hidden" name="token" value="<?= htmlentities($_REQUEST['token']) ?>">
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
