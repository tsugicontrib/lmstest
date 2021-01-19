<?php
function print_jwt($jwt) {
    if ( ! $jwt ) return;
    if ( is_string($jwt) ) {
        echo("Error parsing JWT: $jwt\n");
    } else {
        echo("Parsed JWT:\n");
        $header = htmlentities(json_encode($jwt->header, JSON_PRETTY_PRINT));
        echo($header);
        echo("\n");
        $body = htmlentities(json_encode($jwt->body, JSON_PRETTY_PRINT));
        echo($body);
        echo("\n");
    }
}

function print_debug_log($div, $debug_log) {
    echo('<div id="'.$div.'"><pre>'."\n");
    if ( count($debug_log) > 0 ) {
        echo(htmlentities(\Tsugi\UI\Output::safe_print_r($debug_log), ENT_SUBSTITUTE));
    }
    echo("\n</pre>\n</div>\n");
}
