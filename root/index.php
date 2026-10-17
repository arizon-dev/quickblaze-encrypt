<?php
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");
header("Referrer-Policy: no-referrer");

/* Initialise the Application */
$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$url = trim($url, '/');
$url = basename($url);

/* Initialise Displays */
if ($url == "DataIngest") {
    /* Form Submission Handler */
    require("./local/functions.php");
    require("./DataIngest.php");
    return;
}
if ($url == "view") {
    /* View Message Page */
    require("./local/functions.php");
    require("./public/view.php");
    return;
}
if ($url == "") {
    /* Primary Display Page */
    require("./local/functions.php");
    initialiseSystem(); // Call system functions to initialise
    require("./public/index.php");
} elseif ($url == "404") {
    /* Not Found Page */
    require("./local/functions.php");
    return require("./public/error/404.php");
} elseif ($url == "403") {
    /* Not Found Page */
    require("./local/functions.php");
    return require("./public/error/403.php");
} else if ($url == "500") {
    /* Server Error Page */
    require("./local/functions.php");
    return require("./public/error/500.php");
} else {
    /* Not Found Page */
    require("./local/functions.php");
    return require("./public/error/404.php");
}