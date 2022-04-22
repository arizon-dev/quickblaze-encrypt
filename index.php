<?php
/* Initialise the Application */
$url = $_SERVER['REQUEST_URI'];
$url = substr($url, strrpos($url, '/') + 1);
if (strpos($url, '?') !== false) $url = substr($url, 0, strpos($url, "?"));

// /* Initialise Displays */
if ($url == "") {
    /* Primary Display Page */
    require("./Modules/Functions.php");
    checkDatabase(); // Check database
    setupDatabase(); // Initialise Database
    require("./Public/index.php");
}
if ($url == "404") {
    /* Not Found Page */
    require("./Modules/Functions.php");
    require("./Public/Error/404.html");
    return;
} elseif ($url == "403") {
    /* Not Found Page */
    require("./Modules/Functions.php");
    require("./Public/Error/403.html");
    return;
} elseif ($url == "500") {
    /* Server Error Page */
    require("./Modules/Functions.php");
    require("./Public/Error/500.html");
    return;
} else {
    /* Not Found Page */
    require("./Modules/Functions.php");
    require("./Public/Error/404.html");
    return;
}

/* View Requested Page */
require("./Modules/Functions.php");
checkDatabase(); // Check database
require("./Public/$url.php");
return;