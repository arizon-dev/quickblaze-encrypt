<?php
/*
    -==- Quickblaze Encrypt -==-
    https://github.com/arizon-dev/quickblaze-encrypt
    @ 2023 Arizon Development by axtonprice
*/

/* Application Initilisation */
if (empty($_SERVER['REQUEST_URI'])) header("Location: 500");
$request = $_SERVER['REQUEST_URI'];
$request = substr($request, strrpos($request, '/') + 1);
if (empty($request)) $request = "index"; // Replace with index if empty
if (strpos($request, '?') !== false) $request = substr($request, 0, strpos($request, "?")); // Remove query string
$errorPages = array("DatabaseConfig", "DatabaseCredentials", "ServerConfiguration", "404", "500", "403");

/* Automatic File Rendering */
require "./Modules/functions.php"; // Require functions regardless of request file
if (in_array($request, $errorPages)) { // Check if page is an error page
    $_GET["code"] = $request; // Set error code 
    return require "./Public/error.php"; // Render page
} else if (file_exists("./Public/" . $request . ".php")) { // Check if page exists
    if (empty($request) || $request == "index") initialiseSystem(); // Initialise system
    return require "./Public/" . $request . ".php"; // Render page
} else { // Page not found
    $_GET["code"] = "404"; // Set error code 
    return require "./Public/error.php"; // Render page
}
