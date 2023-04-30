<?php
/* Initialise the Application */
if(empty($_SERVER['SCRIPT_NAME'])) header("Location: 500"); // No request server variable is available
if(empty($_SERVER['REQUEST_URI'])) $_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_NAME'];
$url = $_SERVER['REQUEST_URI'];
$url = substr($url, strrpos($url, '/') + 1);
if (strpos($url, '?') !== false) $url = substr($url, 0, strpos($url, "?"));
/* Initialise Displays */
if ($url == "dataProcessing") {
    /* Form Submission Handler */
    require "./Modules/functions.php";
    require "./Public/dataProcessing.php";
    return;
}
if ($url == "view") {
    /* View Message Page */
    require "./Modules/functions.php";
    require "./Public/view.php";
    return;
}
if ($url == "") {
    /* Primary Display Page */
    require "./Modules/functions.php";
    initialiseSystem(); // Call system functions to initialise
    require "./Public/index.php";
} elseif ($url == "404") {
    /* Not Found Page */
    require "./Modules/functions.php";
    return require "./Public/error_docs/404.php";
} elseif ($url == "404") {
    /* Not Found Page */
    require "./Modules/functions.php";
    return require "./Public/error_docs/404.php";
} elseif ($url == "DatabaseConfig") {
    /* Not Found Page */
    require "./Modules/functions.php";
    return require "./Public/error_docs/DatabaseConfig.php";
} elseif ($url == "DatabaseCredentials") {
    /* Not Found Page */
    require "./Modules/functions.php";
    return require "./Public/error_docs/DatabaseCredentials.php";
} elseif ($url == "ServerConfiguration") {
    /* Not Found Page */
    require "./Modules/functions.php";
    return require "./Public/error_docs/ServerConfiguration.php";
} else {
    if ($url == "500") {
        /* Server Error Page */
        require "./Modules/functions.php";
        return require "./Public/error_docs/500.php";
    } else {
        /* Not Found Page */
        require "./Modules/functions.php";
        return require "./Public/error_docs/404.php";
    }
}
