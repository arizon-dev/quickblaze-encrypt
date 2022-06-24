<?php
/* Process the Data */
error_reporting(0);

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
} else {
    echo '{"response": "' . processData($_GET["data"]) . '"}';
}
