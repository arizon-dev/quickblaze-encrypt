<?php
error_reporting(0);
header("Access-Control-Allow-Origin: *"); // "*" could also be a site such as http://www.example.com
if (!isset($_GET["action"]) || !$_GET["action"]) $_GET["action"] = "";

if ($_GET["action"] == "decrypt" && $_GET["key"]) {
    echo '{"response": "' . htmlspecialchars(decryptData(htmlspecialchars($_GET["key"]))) . '", "key": "' . $_GET["key"] . '"}';
    destroyRecord(htmlspecialchars($_GET["key"], ENT_QUOTES, 'UTF-8')); // destroy record
} else if ($_GET["action"] == "checkConfig") {
    $configuration = json_decode(file_get_contents("./.config", true), true);
    if (!file_exists("./.config")) {
        echo '{"response": "false"}'; // Config file does not exist
    } else {
        if($configuration["LANGUAGE"] == "" || $configuration["INSTALLATION_PATH"] == ""){
            echo '{"response": "false"}'; // Config file is missing a configuration value
        } else{
            if (strtolower($configuration["STORAGE_METHOD"]) == "mysql" || strtolower($configuration["STORAGE_METHOD"]) == "filetree") {
                echo '{"response": "true"}'; // Config file has all values present and valid storage method
            } else{
                echo '{"response": "false"}'; // Config file has all values present and valid storage method
            }
        }
    }
} else if ($_GET["action"] == "isDebugMode") {
    $configuration = json_decode(file_get_contents("./.config", true), true);
    echo '{"response": "' . $configuration["DEBUG_MODE"] . '"}';
} else if ($_GET["action"] == "submit") {
    echo '{"response": "' . processData($_GET["data"], $_GET["password"]) . '"}';
}
