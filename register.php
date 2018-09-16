<?php

$REGISTER_LTI2 = array(
"name" => "LMS Test",
"FontAwesome" => "fa-medkit",
"short_name" => "LMSTest",
"description" => "This tool exercises various LMS Activities.
",
    "messages" => array("launch", "launch_grade"),
    "privacy_level" => "anonymous",  // anonymous, name_only, public
    "license" => "Apache",
    "languages" => array(
        "English"
    ),
    "source_url" => "https://github.com/tsugicontrib/lmstest",
    // For now Tsugi tools delegate this to /lti/store
    "placements" => array(
        /*
        "course_navigation", "homework_submission",
        "course_home_submission", "editor_button",
        "link_selection", "migration_selection", "resource_selection",
        "tool_configuration", "user_navigation"
        */
    ),
    "screen_shots" => array(
/*
        "store/screen-01.png",
        "store/screen-02.png",
        "store/screen-03.png",
        "store/screen-views.png",
        "store/screen-analytics.png"
*/
    )
);
