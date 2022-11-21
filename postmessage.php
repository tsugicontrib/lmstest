<?php
require_once "../config.php";

use \Tsugi\Core\LTIX;
use \Tsugi\Util\U;
use \Tsugi\UI\Output;

require_once "util.php";

// Handle all forms of launch
$LTI = LTIX::requireData();

require_once("nav.php");

// Render view
$OUTPUT->header();
$OUTPUT->bodyStart();
$OUTPUT->topNav($menu);

?>
<form>
<label for="subject"> subject:</label>
<select name="subject" id="subject">
   <option value="">Other -&gt;</option>
   <option value="lti.capabilities">lti.capabilities</option>
   <option value="lti.put_data">lti.put_data</option>
   <option value="lti.get_data">lti.get_data</option>
   <option value="lti.close">lti.close</option>
   <option value="lti.frameResize">lti.frameResize</option>
   <option value="lti.pageRefresh">lti.pageRefresh</option>
   <option value="org.imsglobal.lti.capabilities">org.imsglobal.lti.capabilities</option>
   <option value="org.imsglobal.lti.put_data">org.imsglobal.lti.put_data</option>
   <option value="org.imsglobal.lti.get_data">org.imsglobal.lti.get_data</option>
</select>
<input type="text" name="other_subject" id="other_subject"><br/>
<label for="message_id"> message_id:</label>
<input type="text" name="message_id" id="message_id"> (optional)<br/>
<label for="key"> height:</label>
<input type="text" name="height" id="height"> (for lti.frameResize only)<br/>
<label for="key"> key:</label>
<input type="text" name="key" id="key"> (optional)<br/>
<label for="value"> value:</label>
<input type="value" name="value" id="value"> (optional)<br/>
<input type="submit" value="send" onclick="sendForm(this); return false;">
</form>
<pre id="sent">
</pre>
<pre id="received">
</pre>
<!--
<pre>
<?php
var_dump($_SESSION);
?>
</pre>
-->


<?php
$OUTPUT->footerStart();
?>
<script>
window.addEventListener("message", function(event) {
    console.log(window.location.origin + " Got post message from " + event.origin);
    console.debug(JSON.stringify(event.data, null, '    '));
    document.getElementById('received').innerHTML = JSON.stringify(event.data, null, '    ');
}, false);

function sendForm() {
    document.getElementById('sent').innerHTML = '';
    document.getElementById('received').innerHTML = '';
    let subject = document.getElementById('subject').value;
    if ( subject.length < 1 ) subject = document.getElementById('other_subject').value;
    let message_id = document.getElementById('message_id').value;
    let height = document.getElementById('height').value;
    let key = document.getElementById('key').value;
    let value = document.getElementById('value').value;

    let send_data = {
        subject: subject
    };

    if ( message_id.length > 0 ) send_data.message_id = message_id;
    if ( height.length > 0 ) send_data.height = height;
    if ( key.length > 0 ) send_data.key = key;
    if ( value.length > 0 ) send_data.value = value;
    console.log(send_data);
    document.getElementById('sent').innerHTML = JSON.stringify(send_data, null, '    ');
    window.parent.postMessage(send_data, "*");

}
</script>
<?php
$OUTPUT->footerEnd();

