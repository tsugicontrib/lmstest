<?php
require_once "../config.php";

use \Tsugi\Core\LTIX;

// Handle all forms of launch
$LTI = LTIX::requireData();

// Render view
$OUTPUT->header();
$OUTPUT->bodyStart();
$OUTPUT->topNav();

$OUTPUT->welcomeUserCourse();

if ( $LTI->user->instructor ) {
   echo("Instructors can't send grades");
} else {
   echo('<center><i class="fa fa-trophy fa-5x" style="color: blue;"></i>');
   echo('<br/>You earned a trophy!</center>');
   $LTI->result->gradeSend(0.95, false);
}

$OUTPUT->footer();
