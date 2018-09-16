<?php
// https://github.com/tsugicontrib/lmstest
require_once "../config.php";

use \Tsugi\Core\LTIX;
use \Tsugi\Util\Net;
use \Tsugi\UI\Output;

// Handle all forms of launch
$LTI = LTIX::requireData();

// Render view
$OUTPUT->header();
$OUTPUT->bodyStart();
$OUTPUT->topNav();

$OUTPUT->welcomeUserCourse();

?>
<ul>
<li><a href="grades.php">Test Grades</a></li>
</ul>
<pre>
<?php

echo("IP Address: ".Net::getIP()."\n");
echo(Output::safe_var_dump($_SESSION));
var_dump($USER);
echo("\n</pre>\n");

$OUTPUT->footer();
