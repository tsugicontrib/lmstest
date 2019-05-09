<?php

require_once("../config.php");

use \Tsugi\UI\Output;
use \Tsugi\Core\LTIX;

$debug_log = false;
$retval = false;

// Handle all forms of launch
$LTI = LTIX::requireData();

$lineitem_url = $_REQUEST['id'];
$debug_log = array();

echo("<pre>\n");

echo("Results: $lineitem_url\n");

$lineitem = $LTI->context->loadLineItem($lineitem_url, $debug_log);
if ( is_string($lineitem) ) {
    echo("Failed loading ".htmlentities($lineitem_url)."\n");
    echo("Status: ".$lineitem."\n");
} else {
    echo(htmlentities(Output::safe_print_r($lineitem)));
}

if ( $retval ) {
    var_dump($retval);
}
if ( $debug_log ) {
    echo("--- Debug Log --\n");
    var_dump($debug_log);
}
?>
</pre>
