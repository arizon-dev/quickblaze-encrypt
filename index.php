<?php
/* Initialise the Application */
$url = $_SERVER['REQUEST_URI'];
$url = substr($url, strrpos($url, '/') + 1);
$url = strstr($url, '?', true);

/* Initialise Displays */
if ($url == "") {
    /* Primary Display Page */
    require("./Modules/Functions.php");
    setupDatabase(); // Initialise Database
    require("./Public/index.php");
}
if ($url == "processForm") {
    /* Form Submission Handler */
    require("./Modules/Functions.php");
    require("./Public/processForm.php");
    return;
}
if ($url == "view") {
    /* View Message Page */
    require("./Modules/Functions.php");
    require("./Public/view.php");
    return;
}
?>