<?php
// error_reporting(0); // disable error reporting
header("Access-Control-Allow-Origin: *"); // "*" could also be a site such as http://www.example.com


/* Internal Script Functions */
function processData($data, $password)
{
    $encryptionKey = generateKey(64); // Create new key
    $encryptedData = encryptData($data, $encryptionKey); // Encrypt data
    $encryptedPassword = encryptData($password, $encryptionKey); // Encrypt data
    insertRecord($encryptedData, $encryptionKey, $encryptedPassword); // Insert new database record
    return $encryptionKey;
}
function getInstallationPath()
{
    $config = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/.config", true), true);
    return htmlspecialchars($config["INSTALLATION_PATH"]);
}
function determineSystemVersion()
{
    if (!file_exists($_SERVER['DOCUMENT_ROOT'] . "/.version")) {
        touch($_SERVER['DOCUMENT_ROOT'] . "/.version"); // Create version file if not exists
        if (!is_dir($_SERVER['DOCUMENT_ROOT'] . "/local-storage/")) mkdir($_SERVER['DOCUMENT_ROOT'] . "/local-storage/");
        touch($_SERVER['DOCUMENT_ROOT'] . "/local-storage/.version-cache"); // Create version file cache if not exists
        $latestVersion = json_decode(file_get_contents("https://raw.githubusercontent.com/arizon-dev/quickblaze-encrypt/main/.version?cacheUpdate=" . rand(0, 100), true), true);
        $date = date("Y-m-d H:i:s"); // Current date for cache
        file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/.version", json_encode(array("BRANCH" => $latestVersion["BRANCH"], "VERSION" => $latestVersion["VERSION"])));
        file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/local-storage/.version-cache", json_encode(array("cacheDate" => $date, "BRANCH" => $latestVersion["BRANCH"], "VERSION" => $latestVersion["VERSION"])));
    }
    $thisVersion = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/.version", true), true);
    $latestVersion = json_decode(file_get_contents("https://raw.githubusercontent.com/arizon-dev/quickblaze-encrypt/" . htmlspecialchars($thisVersion["BRANCH"]) . "/.version?cacheUpdate=" . rand(0, 100), true), true);
    $releaseType = ($thisVersion["BRANCH"] == "canary") ? $releaseType = "Canary" : $releaseType = "Stable";
    return 'v' . $thisVersion["VERSION"] . '-' . $releaseType;
}
function generateKey($length)
{
    $length = 16;
    $bytes = openssl_random_pseudo_bytes($length);
    $hex = bin2hex($bytes);
    return $hex;
}
function getMessageCreationDate($key)
{
    $config = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/.config", true), true);
    $timezone = (empty($config["TIMEZONE"]) || !$config["TIMEZONE"]) ? date('e') : $config["TIMEZONE"];
    date_default_timezone_set($timezone); // Set default timezone
    if (empty($key)) return "--";
    return date('F jS, Y \a\t H:ia', strtotime(getRecord("record_date", $key)));
}


/* Data Conversion Functions */
function encryptData($dataForEncryption, $encryption_key)
{
    $encryption_iv = hex2bin($encryption_key);
    return openssl_encrypt($dataForEncryption, "AES-128-CTR", $encryption_key, 0, $encryption_iv);
}
function decryptData($dataForDecryption, $encryption_key)
{
    $encryption_iv = hex2bin($encryption_key);
    return openssl_decrypt(getRecord($dataForDecryption, $encryption_key), "AES-128-CTR", $encryption_key, 0, $encryption_iv);
}
function validatePassword($encryption_key, $password_attempt)
{
    $encryption_iv = hex2bin($encryption_key);
    if (openssl_decrypt(getRecord("password", $encryption_key), "AES-128-CTR", $encryption_key, 0, $encryption_iv) == $password_attempt) {
        return true;
    } else {
        return false;
    }
}


/* System Setup & Checking Functions */
function initialiseSystem()
{
    function createStorageMethodEndpoints()
    {
        if (!is_dir($_SERVER['DOCUMENT_ROOT'] . "/local-storage/")) mkdir($_SERVER['DOCUMENT_ROOT'] . "/local-storage/"); // Create storage folder if not present
        if (!file_exists($_SERVER['DOCUMENT_ROOT'] . "/local-storage/.cache")) touch($_SERVER['DOCUMENT_ROOT'] . "/local-storage/.cache"); // Create cache file if not present
        if (!file_exists($_SERVER['DOCUMENT_ROOT'] . "/.config")) touch($_SERVER['DOCUMENT_ROOT'] . "/.config"); // Create config file if not present
    }
    function checkConfigValues()
    {
        if (empty($_SERVER['SERVER_PORT']) || empty($_SERVER['SERVER_NAME']) || empty($_SERVER['HTTPS']) || empty($_SERVER['REQUEST_URI'])) header("Location: ./500"); // Kill request if server vars are empty
        $configuration = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/.config", true), true);

        /* Config File Variables */
        if (!$configuration["STORAGE_METHOD"] || $configuration["STORAGE_METHOD"] == "") {
            $TEMP_STORAGE_METHOD = "mysql"; // Reset configuration to default value
        } else {
            $TEMP_STORAGE_METHOD = $configuration["STORAGE_METHOD"];
        }
        $TEMP_PATH = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
        if ($_SERVER["SERVER_PORT"] == "80" || $_SERVER["SERVER_PORT"] == "443") {
            $TEMP_PATH .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
            $TEMP_PATH = rtrim($TEMP_PATH, '/'); // Remove last slash from the new URL
        } else { // Webserver is using a custom port!
            $TEMP_PATH .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
            $TEMP_PATH = rtrim($TEMP_PATH, '/'); // Remove last slash from the new URL
        }
        if (!$configuration["LANGUAGE"] || $configuration["LANGUAGE"] == "") {
            $TEMP_LANGUAGE = "auto"; // Reset configuration to default value
        } else {
            $TEMP_LANGUAGE = $configuration["LANGUAGE"];
        }
        if (!$configuration["TIMEZONE"] || $configuration["TIMEZONE"] == "") { // "Europe/London" Format
            if (!date('e')) $TEMP_TIMEZONE = "auto";
            else $TEMP_TIMEZONE = date('e');
        } else {
            $TEMP_TIMEZONE = $configuration["TIMEZONE"];
        }
        if (!$configuration["DEBUG_MODE"] || $configuration["DEBUG_MODE"] == "") {
            $TEMP_DEBUGMODE = "false"; // Reset configuration to default value
        } else {
            $TEMP_DEBUGMODE = $configuration["DEBUG_MODE"];
        }

        /* Config File If Empty Validation*/
        if (isset($configuration["STORAGE_METHOD"]) == false || isset($configuration["LANGUAGE"]) == false || isset($configuration["TIMEZONE"]) == false || isset($configuration["INSTALLATION_PATH"]) == false || isset($configuration["DEBUG_MODE"]) == false) {
            file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/.config", json_encode(array("STORAGE_METHOD" => "$TEMP_STORAGE_METHOD", "LANGUAGE" => "$TEMP_LANGUAGE", "TIMEZONE" => "$TEMP_TIMEZONE", "INSTALLATION_PATH" => "$TEMP_PATH", "DEBUG_MODE" => "$TEMP_DEBUGMODE"))); // Set contents of new config file
        }
    }
    function setupStorageMethod()
    {
        $cache = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/local-storage/.cache", true), true);
        $configuration = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/.config", true), true);
        file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/local-storage/.cache", '{"DO-NOT-TOUCH:database_installation_status": "notset", "config_created": "true"}');

        if (strtolower($configuration["STORAGE_METHOD"]) == "mysql") {
            if (!file_exists($_SERVER['DOCUMENT_ROOT'] . "/modules/Database.env")) {
                touch($_SERVER['DOCUMENT_ROOT'] . "/modules/Database.env"); // Create database configuration file
                $_GET["errorCode"] = "DatabaseConfig"; // set error code
                require $_SERVER['DOCUMENT_ROOT'] . "/public/error.php"; // throw error page if invalid configuration
                die();
            } else {
                $json = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/modules/Database.env", true), true);
                if ($json["DATABASE"] == "" || $json["HOSTNAME"] == "") {
                    $_GET["errorCode"] = "DatabaseConfig"; // set error code
                    require $_SERVER['DOCUMENT_ROOT'] . "/public/error.php"; // throw error page if invalid configuration
                    die();
                } else { // Test database connection
                    $conn = new mysqli($json["HOSTNAME"], $json["USERNAME"], $json["PASSWORD"], $json["DATABASE"]);
                    if ($conn->connect_error) {
                        $_GET["errorCode"] = "DatabaseCredentials"; // set error code
                        require $_SERVER['DOCUMENT_ROOT'] . "/public/error.php"; // throw error page if invalid configuration
                        die();
                    } else {
                        $cache = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/local-storage/.cache"), true);
                        if ($cache["DO-NOT-TOUCH:database_installation_status"] == "false") {
                            $tableCreateSQL = "CREATE TABLE IF NOT EXISTS `quickblaze_records` (`record_id` int(11) NOT NULL, `encrypted_contents` longtext NOT NULL, `password` varchar(128) NOT NULL,`encryption_token` varchar(128) NOT NULL, `source_ip` varchar(100) NOT NULL, `record_date` timestamp(5) NOT NULL DEFAULT current_timestamp(5)) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
                            $addPrimaryKeySQL = "ALTER TABLE `quickblaze_records` ADD PRIMARY KEY (`record_id`);";
                            if ($conn->query($tableCreateSQL)) {
                                if ($conn->query($addPrimaryKeySQL)) {
                                    file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/local-storage/.cache", '{"DO-NOT-TOUCH:database_installation_status": "true", "config_created": "true"}');
                                }
                            } else {
                                $_GET["errorCode"] = "DatabaseCredentials"; // set error code
                                require $_SERVER['DOCUMENT_ROOT'] . "/public/error.php"; // throw error page if invalid configuration
                                die();
                            }
                        }
                        // Always reset auto-increment
                        if (!$conn->query("ALTER TABLE `quickblaze_records` MODIFY `record_id` int(11) NOT NULL AUTO_INCREMENT;")) {
                            $_GET["errorCode"] = "DatabaseConfig"; // set error code
                            require $_SERVER['DOCUMENT_ROOT'] . "/public/error.php"; // throw error page if invalid configuration
                            die();
                        }
                    }
                    $conn->close();
                }
            }
        } else if (strtolower($configuration["STORAGE_METHOD"]) == "filetree") {
            $baseStorageFolder = $_SERVER['DOCUMENT_ROOT'] . "/local-storage";
            if (!is_dir("$baseStorageFolder/")) mkdir("$baseStorageFolder/");
            if (!is_dir("$baseStorageFolder/encryptions/")) mkdir("$baseStorageFolder/encryptions/");
        } else { // Server storage method not set
            $_GET["errorCode"] = "ServerConfiguration"; // set error code
            require $_SERVER['DOCUMENT_ROOT'] . "/public/error.php"; // throw error page if invalid configuration
            die();
        }
    }

    /* Call Functions */
    createStorageMethodEndpoints(); // Setup files and folders the system will store data.
    checkConfigValues(); // Validate if configuration values are correct & present.
    setupStorageMethod(); // Setup how the system will store the data via the configured method.
}


/* Database Interaction Functions */
function insertRecord($encrypted_contents, $encryption_token, $password)
{
    $configuration = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/.config", true), true);
    $json = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/modules/Database.env", true), true);
    if (empty($_SERVER['HTTP_CF_CONNECTING_IP']) || empty($_SERVER['REMOTE_ADDR'])) header("Location: ./500"); // Kill request if server vars are empty
    if ($_SERVER['HTTP_CF_CONNECTING_IP'] == "" || !isset($_SERVER['HTTP_CF_CONNECTING_IP'])) $_SERVER['HTTP_CF_CONNECTING_IP'] = $_SERVER["REMOTE_ADDR"];
    if (strtolower($configuration["STORAGE_METHOD"]) == "mysql") {
        $mysqli = new mysqli($json["HOSTNAME"], $json["USERNAME"], $json["PASSWORD"], $json["DATABASE"]);
        if ($mysqli->connect_errno) {
            $_GET["errorCode"] = "DatabaseCredentials"; // set error code
            require $_SERVER['DOCUMENT_ROOT'] . "/public/error.php"; // throw error page if invalid configuration
            die();
        }
        $source_ip = filter_var($_SERVER['HTTP_CF_CONNECTING_IP'], FILTER_VALIDATE_IP) ?? filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP);
        $record_date = date("Y-m-d H:i:s");
        if ($mysqli->query("INSERT INTO `quickblaze_records` (`encrypted_contents`, `password`, `encryption_token`, `source_ip`, `record_date`) VALUES ('$encrypted_contents', '$password', '$encryption_token', '$source_ip', '$record_date');") === TRUE) {
            return true;
        } else {
            die($mysqli->error);
        }
        $mysqli->close();
    } elseif (strtolower($configuration["STORAGE_METHOD"]) == "filetree") {
        $baseStorageFolder = $_SERVER['DOCUMENT_ROOT'] . "/local-storage";
        $uniqueIdentifier = uniqid($encryption_token); // Assign ID to new storage folder
        if (!is_dir("$baseStorageFolder/encryptions/$uniqueIdentifier/")) mkdir("$baseStorageFolder/encryptions/$uniqueIdentifier/"); // Create temporary unique folder with ID
        if (!file_exists("$baseStorageFolder/encryptions/$uniqueIdentifier/data.json")) touch("$baseStorageFolder/encryptions/$uniqueIdentifier/data.json"); // Create encryption data file
        $source_ip = filter_var($_SERVER['HTTP_CF_CONNECTING_IP'], FILTER_VALIDATE_IP) ?? filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP);
        $record_date = date("Y-m-d H:i:s");
        file_put_contents("$baseStorageFolder/encryptions/$uniqueIdentifier/data.json", '{"filestore_id": "' . $uniqueIdentifier . '", "encrypted_contents": "' . $encrypted_contents . '", "password": "' . $password . '", "encryption_token": "' . $encryption_token . '", "source_ip": "' . $source_ip . '", "record_date": "' . $record_date . '"}'); // Set data file encryption data
    } else {
        $_GET["errorCode"] = "ServerConfiguration"; // set error code
        require $_SERVER['DOCUMENT_ROOT'] . "/public/error.php"; // throw error page if invalid configuration
        die();
    }
}
function destroyRecord($token)
{
    $configuration = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/.config", true), true);
    $json = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/modules/Database.env", true), true);
    if (strtolower($configuration["STORAGE_METHOD"]) == "mysql") {
        $mysqli = new mysqli($json["HOSTNAME"], $json["USERNAME"], $json["PASSWORD"], $json["DATABASE"]);
        if ($mysqli->connect_errno) {
            $_GET["errorCode"] = "DatabaseCredentials"; // set error code
            require $_SERVER['DOCUMENT_ROOT'] . "/public/error.php"; // throw error page if invalid configuration
            die();
        }
        $token = filter_var($token, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ($mysqli->query("DELETE FROM `quickblaze_records` WHERE `encryption_token` = '$token';") === TRUE) {
            return true;
        } else {
            die($mysqli->error);
        }
        $mysqli->close();
    } elseif (strtolower($configuration["STORAGE_METHOD"]) == "filetree") {
        $baseStorageFolder = $_SERVER['DOCUMENT_ROOT'] . "/local-storage";
        $dir = new DirectoryIterator("$baseStorageFolder/encryptions/");
        foreach ($dir as $fileinfo) {
            if ($fileinfo->isDir() && !$fileinfo->isDot()) { // $fileinfo->getFilename()
                $theFile = json_decode(file_get_contents("$baseStorageFolder/encryptions/" . $fileinfo->getFilename() . "/data.json", true), true);
                if ($theFile["encryption_token"] == $token) {
                    function rmdir_recursive($dir)
                    {
                        foreach (scandir($dir) as $file) {
                            if ('.' === $file || '..' === $file) continue;
                            if (is_dir("$dir/$file")) rmdir_recursive("$dir/$file");
                            else unlink("$dir/$file");
                        }
                        rmdir($dir);
                    }
                    rmdir_recursive("$baseStorageFolder/encryptions/" . $fileinfo->getFilename());
                }
            }
        }
    } else { // Server storage method not set
        $_GET["errorCode"] = "ServerConfiguration"; // set error code
        require $_SERVER['DOCUMENT_ROOT'] . "/public/error.php"; // throw error page if invalid configuration
        die();
    }
}
function getRecord($dataToFetch, $encryption_token)
{
    $configuration = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/.config", true), true);
    $json = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/modules/Database.env", true), true);
    if (strtolower($configuration["STORAGE_METHOD"]) == "mysql") {
        $mysqli = new mysqli($json["HOSTNAME"], $json["USERNAME"], $json["PASSWORD"], $json["DATABASE"]);
        if ($mysqli->connect_errno) {
            $_GET["errorCode"] = "DatabaseCredentials"; // set error code
            require $_SERVER['DOCUMENT_ROOT'] . "/public/error.php"; // throw error page if invalid configuration
            die();
        }
        $encryption_token = filter_var($encryption_token, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $result = $mysqli->query("SELECT `$dataToFetch` FROM `quickblaze_records` WHERE `encryption_token` = '$encryption_token'");
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                return $row[$dataToFetch];
            }
        } else {
            return false;
        }
        $mysqli->close();
    } elseif (strtolower($configuration["STORAGE_METHOD"]) == "filetree") {
        $baseStorageFolder = $_SERVER['DOCUMENT_ROOT'] . "/local-storage";
        $dir = new DirectoryIterator("$baseStorageFolder/encryptions/");
        foreach ($dir as $fileinfo) {
            if ($fileinfo->isDir() && !$fileinfo->isDot()) { // $fileinfo->getFilename()
                $theFile = json_decode(file_get_contents("$baseStorageFolder/encryptions/" . $fileinfo->getFilename() . "/data.json", true), true);
                if ($theFile["encryption_token"] == $encryption_token) {
                    return $theFile[$dataToFetch];
                }
            }
        }
    } else { // Server storage method not set
        $_GET["errorCode"] = "ServerConfiguration"; // set error code
        require $_SERVER['DOCUMENT_ROOT'] . "/public/error.php"; // throw error page if invalid configuration
        die();
    }
}


/* Other Functions */
function translate($q)
{
    $translateFrom = "en"; // Default language
    $configuration = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/.config", true), true);
    if ($configuration["LANGUAGE"] == "auto") {
        $translateTo = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
    } else if ($configuration["LANGUAGE"] != "") {
        $translateTo = $configuration["LANGUAGE"];
    } else {
        $translateTo = "en";
    }
    $res = file_get_contents("https://translate.googleapis.com/translate_a/single?client=gtx&ie=UTF-8&oe=UTF-8&dt=bd&dt=ex&dt=ld&dt=md&dt=qca&dt=rw&dt=rm&dt=ss&dt=t&dt=at&sl=" . $translateFrom . "&tl=" . $translateTo . "&hl=hl&q=" . urlencode($q), $_SERVER['DOCUMENT_ROOT'] . "/transes.html");
    $res = json_decode($res);
    return $res[0][0][0]; // Escape response
}
