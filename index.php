<?php
/* Initialise the Application */
$url = $_SERVER['REQUEST_URI'];
$url = substr($url, strrpos($url, '/') + 1);
if (strpos($url, '?') !== false) $url = substr($url, 0, strpos($url, "?"));

/* Initialise Displays */
if ($url == "dataProcessing") {
    /* Form Submission Handler */
    require("./Modules/functions.php");
    require("./Public/dataProcessing.php");
    return;
}
if ($url == "view") {
    /* View Message Page */
    require("./Modules/functions.php");
    require("./Public/view.php");
    return;
}
if ($url == "") {
    /* Primary Display Page */
    require("./Modules/functions.php");
    setupStorageMethod(); // Check database
    require("./Public/index.php");
} elseif ($url == "404") {
    /* Not Found Page */
    require("./Modules/functions.php");
    return require("./Public/error/404.php");
} elseif ($url == "403") {
    /* Not Found Page */
    require("./Modules/functions.php");
    return require("./Public/error/403.php");
} else {
    if ($url == "500") {
        /* Server Error Page */
        require("./Modules/functions.php");
        return require("./Public/error/500.php");
    } else {
        /* Not Found Page */
        require("./Modules/functions.php");
        return require("./Public/error/404.php");
    }
}
