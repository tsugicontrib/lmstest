<?php
require_once "../config.php";

use \Tsugi\Core\LTIX;
use \Tsugi\Util\U;
use \Tsugi\Util\LTI13;
use \Tsugi\UI\Output;

require_once "util.php";

// Handle all forms of launch
$LTI = LTIX::requireData();

// Render view
$OUTPUT->header();
$OUTPUT->bodyStart();
$OUTPUT->topNav();

require_once("nav.php");

$key_key = LTIX::ltiParameter('key_key');
$lti13_token_url = LTIX::ltiParameter('lti13_token_url');
$lti13_privkey = LTIX::decrypt_secret(LTIX::ltiParameter('lti13_privkey'));
$lti13_membership_url = LTIX::ltiParameter('lti13_membership_url');
$lti13_client_id = LTIX::ltiParameter('lti13_client_id');

$missing = '';
if ( strlen($key_key) < 1 ) $missing .= ' ' . 'key_key';
if ( strlen($lti13_privkey) < 1 ) $missing .= ' ' . 'private_key';
if ( strlen($lti13_token_url) < 1 ) $missing .= ' ' . 'token_url';

if ( strlen($missing) > 0 ) {
    echo('Missing '.$missing);
    $OUTPUT->footer();
    return;
}

?>
<div id="tabs">
  <ul>
    <li><a href="#tabs-1">Token</a></li>
    <li><a href="#tabs-2">Memberships</a></li>
    <li><a href="#tabs-3">Debug Log</a></li>
    <li><a href="#tabs-4">With Sourcedids</a></li>
    <li><a href="#tabs-5">Debug Log</a></li>
  </ul>
  <div id="tabs-1">
    <pre>
<?php
$roster_access_token = false;
$debug_log = array();
echo("Loading token...\n");
echo("Token URL: ".$lti13_token_url."\n");
if ( strlen($lti13_membership_url) > 0 && strlen($lti13_token_url) > 0 ) {
    echo("Client_id=".$lti13_client_id." subject=".$key_key."\n");
    $roster_token_data = LTI13::getRosterToken($CFG->wwwroot, $lti13_client_id, $lti13_token_url, $lti13_privkey, $debug_log);
    echo("Roster token data:\n");
    if ( $roster_token_data ) {
        echo(htmlentities(Output::safe_print_r($roster_token_data)));
    } else {
        var_dump($roster_token_data);
    }
    echo("\n");
    if ( $roster_token_data ) {
        if ( isset($roster_token_data['access_token']) ) {
            $roster_access_token = $roster_token_data['access_token'];
            echo("Roster Access Token=".$roster_access_token."\n");
            $required_fields = false;
            $jwt = LTI13::parse_jwt($roster_access_token, $required_fields);
            if ( ! is_string($jwt) ) print_jwt($jwt);
        } else {
            $status = U::get($roster_token_data, 'error', 'Did not receive access token');
            echo($status."\n");
            echo("See debug log\n");
        }
    } else {
        echo("Did not receive access_token\n");
        echo("Private Key: ".substr($lti13_privkey, 0, 50)."\n");
    }
} else {
    echo("Did not receive membership_url\n");
}
?>
    </pre>
  </div>
  <div id="tabs-2">
    <pre>
<?php
if ( $roster_access_token ) {
    $debug_log = array();
    echo("Loading roster...\n");
    echo("Membership URL: ".$lti13_membership_url."\n");
    $roster = LTI13::loadRoster($lti13_membership_url, $roster_access_token, $debug_log);
    if ( is_string($roster) ) {
        echo($roster."\n");
    } else {
        echo("Loaded ".count($roster)." members\n");
        echo(htmlentities(Output::safe_print_r($roster)));
    }
} else {
    echo("Did not get roster_access_token\n");
}
?>
    </pre>
  </div>
  <div id="tabs-3">
    <pre>
<?php
if ( count($debug_log) > 0 ) {
    echo(htmlentities(Output::safe_print_r($debug_log)));
}
?>
    </pre>
  </div>
  <div id="tabs-4">
    <pre>
<?php
$debug_log = array();
if ( strlen($lti13_membership_url) > 0 ) {
    echo("Getting token for membership with sourcedids...\n");
    echo("Token URL: ".$lti13_membership_url."\n");
    echo("Client_id=".$lti13_client_id." subject=".$key_key."\n");
    $roster_token_data = LTI13::getRosterWithSourceDidsToken($CFG->wwwroot, $lti13_client_id, $lti13_token_url, $lti13_privkey, $debug_log);
    echo("Roster token data:\n");
    if ( $roster_token_data ) {
        echo(htmlentities(Output::safe_print_r($roster_token_data)));
    } else {
        var_dump($roster_token_data);
    }
    echo("\n");
    if ( isset($roster_token_data['access_token']) ) {
        $roster_access_token = $roster_token_data['access_token'];
        echo("Roster Access Token=".$roster_access_token."\n");
        $jwt = LTI13::parse_jwt($roster_access_token, $required_fields);
        if ( ! is_string($jwt) ) print_jwt($jwt);
    } else {
        $status = U::get($roster_token_data, 'error', 'Did not receive access token');
        error_log($status);
        echo($status."\n");
        echo("See debug log\n");
        echo("Private Key: ".substr($lti13_privkey, 0, 50)."\n");
    }
} else {
    echo("Did not receive membership_url\n");
}
if ( $roster_access_token ) {
    $debug_log = array();
    echo("Membership URL: ".$lti13_membership_url."\n");
    $roster = LTI13::loadRoster($lti13_membership_url, $roster_access_token, $debug_log);
    if ( is_string($roster) ) {
        echo($roster."\n");
    } else {
        echo("Loaded ".count($roster)." members\n");
        echo(htmlentities(Output::safe_print_r($roster)));
    }
} else {
    echo("Did not get roster_access_token\n");
}
?>
    </pre>
  </div>
  <div id="tabs-5">
    <pre>
<?php
if ( count($debug_log) > 0 ) {
    echo(htmlentities(Output::safe_print_r($debug_log)));
}
?>
    </pre>
  </div>
</div>

<?php
$OUTPUT->footerStart();
?>
<script>
  $( function() {
    $( "#tabs" ).tabs();
  } );
  </script>
<?php
$OUTPUT->footerEnd();


