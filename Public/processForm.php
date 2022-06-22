<?php
/* Process the Data */
error_reporting(0);

if ($_GET["action"] == "decrypt" && $_GET["key"]) {
    echo '{"response": "' . htmlspecialchars(decryptData(htmlspecialchars($_GET["key"]))) . '", "key": "' . $_GET["key"] . '"}';
    destroyRecord(htmlspecialchars($_GET["key"], ENT_QUOTES, 'UTF-8')); // destroy record
} else {
    echo '{"response": "' . processData($_GET["data"]) . '"}';
}
