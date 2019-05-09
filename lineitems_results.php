<?php

require_once("../config.php");

use \Tsugi\UI\Output;
use \Tsugi\Core\LTIX;

$LTI = LTIX::requireData();

$retval = false;

$lineitem_url = $_REQUEST['id'];
$debug_log = array();

echo("<pre>\n");

echo("Results: $lineitem_url\n");

$results = $LTI->context->loadResults($lineitem_url, $debug_log);
if ( is_string($results) ) {
    echo("Failed loading ".htmlentities($lineitem_url)."\n");
    echo("Status: ".$results."\n");
} else {
    echo(htmlentities(Output::safe_print_r($results)));
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
