<?php

require_once("../config.php");

use \Tsugi\UI\Output;
use \Tsugi\Util\U;
use \Tsugi\Util\LTI13;
use \Tsugi\Core\LTIX;

$LTI = LTIX::requireData();

$debug_log = false;
$retval = false;

$lti13_lineitem = $_REQUEST['id'];
$token = $_REQUEST['token'];
$debug_log = array();

// TODO: This is operating at a too low abstraction level for now but we will move the code into Result to be cleaner
$key_key = LTIX::ltiParameter('key_key');
$lti13_token_url = LTIX::ltiParameter('lti13_token_url');
$lti13_privkey = LTIX::decrypt_secret(LTIX::ltiParameter('lti13_privkey'));
$user_key = LTIX::ltiParameter('user_key');

$missing = '';
if ( strlen($key_key) < 1 ) $missing .= ' ' . 'key_key';
if ( strlen($lti13_privkey) < 1 ) $missing .= ' ' . 'private_key';
if ( strlen($lti13_token_url) < 1 ) $missing .= ' ' . 'token_url';
if ( strlen($user_key) < 1 ) $missing .= ' ' . 'user_key';

if ( strlen($missing) > 0 ) {
    echo("<pre>\n");
    echo('Missing '.$missing);
    return;
}

$grade = U::get($_REQUEST,'grade');
if ( ! $grade ) {
?>
<h1>Send a grade</h1>
<p>LineItem: <?= htmlentities($_REQUEST['id']) ?></p>
<form>
<input type="hidden" name="token" value="<?= htmlentities($_REQUEST['token']) ?>">
<input type="hidden" name="url" value="<?= htmlentities($_REQUEST['url']) ?>">
<input type="hidden" name="id" value="<?= htmlentities($_REQUEST['id']) ?>">
<p>grade <input type="text" name="grade"></p>
<input type="submit" value="Send Score">
</form>
<?php
    return;
}

echo("<pre>\n");
echo("UR=$lti13_lineitem\n");
echo("Getting token key_key=$key_key lti13_token_url=$lti13_token_url\n");

$token_data = LTI13::getGradeToken($CFG->wwwroot, $key_key, $lti13_token_url, $lti13_privkey, $debug_log);
if ( ! isset($token_data['access_token']) ) {
    $retval = U::get($token_data, 'error', 'Did not receive access token');
} else{
    $access_token = $token_data['access_token'];
    $tmp = "Sending grade $grade user_key=$user_key lti13_lineitem=$lti13_lineitem access_token=$access_token\n";
    echo($tmp);
    $tmp = "Sending grade $grade user_key=$user_key";
    $retval = LTI13::sendLineItem($user_key, $grade, /*$comment*/ $tmp, $lti13_lineitem,
        $access_token, $debug_log);
}

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
