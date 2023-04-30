<?php
// error_reporting(0);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

if (empty($_GET["action"])) $_GET["action"] = "";
if (empty($_GET["key"])) $_GET["key"] = "";
if (empty($_GET["data"])) $_GET["data"] = "";
if (empty($_GET["password"])) $_GET["password"] = "";

if ($_GET["action"] == "decrypt" && isset($_GET["key"]) && isset($_GET["password"])) {
    if (decryptData("password", $_GET["key"]) == $_GET["password"]) {
        echo '{"response": "' . htmlspecialchars(decryptData("encrypted_contents", stripslashes($_GET["key"]))) . '"}';
        destroyRecord(stripslashes($_GET["key"])); // Destroy record after message decryption
    } else {
        echo '{"response": "badpass"}'; // Password is incorrect
    }
} else if ($_GET["action"] == "validatePassword" && isset($_GET["key"]) && isset($_GET["password"])) {
    if (decryptData("password", stripslashes($_GET["key"])) == $_GET["password"]) {
        echo '{"response": true}'; // Password is correct
    } else {
        echo '{"response": false}'; // Password is incorrect
    }
} else if ($_GET["action"] == "checkConfig") {
    $configuration = json_decode(file_get_contents("./.config", true), true);
    if (!file_exists("./.config")) {
        echo '{"response": "false"}'; // Config file does not exist
    } else {
        if ($configuration["LANGUAGE"] == "" || $configuration["INSTALLATION_PATH"] == "") {
            echo '{"response": "false"}'; // Config file is missing a configuration value
        } else {
            if (strtolower($configuration["STORAGE_METHOD"]) == "mysql" || strtolower($configuration["STORAGE_METHOD"]) == "filetree") {
                echo '{"response": "true"}'; // Config file has all values present and valid storage method
            } else {
                echo '{"response": "false"}'; // Config file has all values present and valid storage method
            }
        }
    }
} else if ($_GET["action"] == "isDebugMode") {
    $configuration = json_decode(file_get_contents("./.config", true), true);
    echo '{"response": "' . $configuration["DEBUG_MODE"] . '"}';
} else if ($_GET["action"] == "submit") {
    $dat = processData(stripslashes($_GET["data"]), stripslashes($_GET["password"]));
    echo '{"response": "' . $dat . '"}';
} else if ($_GET["action"] == "doesMessageExist" && isset($_GET["key"])) {
    $dat = decryptData("password", $_GET["key"]);
    if ($dat == null || $dat == false || $dat == "" || $dat == "false") {
        echo '{"response": false}';
    } else {
        echo '{"response": true}';
    }
} else {
    echo '{"response": "error"}';
}
