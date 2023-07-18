<?php
/*
 * Quickblaze Encrypt 
 * Copyright @ 2023 - Arizon Software 
*/

/* Application Initilisation */
if (empty($_SERVER['REQUEST_URI']) || !isset($_SERVER['REQUEST_URI'])) header("Location: 500"); // Check if valid server variable is available
if (empty($_SERVER['DOCUMENT_ROOT']) || !isset($_SERVER['DOCUMENT_ROOT'])) $_SERVER['DOCUMENT_ROOT'] = "."; // Check if document root variable is available
$request = $_SERVER['REQUEST_URI']; // Get requested file name
$request = substr($request, strrpos($request, '/') + 1);
if (empty($request)) $request = "index"; // Replace with index if empty
if (strpos($request, '?') !== false) $request = substr($request, 0, strpos($request, "?")); // Remove query string
$errorPages = array("DatabaseConfig", "DatabaseCredentials", "ServerConfiguration", "404", "500", "403");

/* File Rendering */
require $_SERVER['DOCUMENT_ROOT'] . "/modules/functions.php"; // Require functions regardless of request file

// Check if page is an error page
if (in_array($request, $errorPages)) {
    $_GET["errorCode"] = $request; // Set error code 
    return require $_SERVER['DOCUMENT_ROOT'] . "/public/error.php"; // Render page

} else if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/public/" . $request . ".php")) { // Check if page exists
    if (empty($request) || $request == "index") initialiseSystem(); // Initialise system
    return require $_SERVER['DOCUMENT_ROOT'] . "/public/" . $request . ".php"; // Render page

} else {  // Page not found (404)
    $_GET["errorCode"] = "404"; // Set error code 
    return require $_SERVER['DOCUMENT_ROOT'] . "/public/error.php"; // Render page
}
