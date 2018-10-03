<?php

require_once("../config.php");

use \Tsugi\UI\Output;
use \Tsugi\Util\LTI13;

$debug_log = false;
$retval = false;

$lineitem_url = $_REQUEST['id'];
$token = $_REQUEST['token'];
$debug_log = array();

echo("<pre>\n");

$lineitem = LTI13::loadLineItem($lineitem_url, $token, $debug_log);
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
