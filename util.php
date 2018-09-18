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
    }
}
