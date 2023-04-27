<?php
error_reporting(0); // disable error reporting
header("Access-Control-Allow-Origin: *"); // "*" could also be a site such as http://www.example.com


/* Internal Script Functions */
function get_string_between($string, $start, $end)
{
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}
function processData($data)
{
    $encryptionKey = generateKey(64); // Create new key
    $encryptedData = encryptData($data, $encryptionKey); // Encrypt data
    insertRecord($encryptedData, $encryptionKey); // Insert new database record
    return $encryptionKey;
}
function ifTextBoxDisabled()
{
    if (isset($_GET["submitted"])) {
        echo "disabled";
    }
}
function getInstallationPath()
{
    $config = json_decode(file_get_contents("./.config", true), true);
    echo $config["INSTALLATION_PATH"];
}
function determineSystemVersion()
{
    if (!file_exists("./.version")) {
        touch("./.version");
        $latestVersion = json_decode(file_get_contents("https://raw.githubusercontent.com/axtonprice-dev/quickblaze-encrypt/main/.version?cacheUpdate=" . rand(0, 100), true), true);
        file_put_contents("./.version", json_encode(array("BRANCH" => $latestVersion["BRANCH"], "VERSION" => $latestVersion["VERSION"], "LANGUAGE" => "auto")));
    }
    $thisVersion = json_decode(file_get_contents("./.version", true), true);
    $latestVersion = json_decode(file_get_contents("https://raw.githubusercontent.com/axtonprice-dev/quickblaze-encrypt/" . filter_var(htmlspecialchars($thisVersion["BRANCH"]), FILTER_SANITIZE_FULL_SPECIAL_CHARS) . "/.version?cacheUpdate=" . rand(0, 100), true), true);
    if ($thisVersion["BRANCH"] == "dev" && $thisVersion["VERSION"] != $latestVersion["VERSION"]) {
        return '<x style="color:orange">v' . $thisVersion["VERSION"] . ' (' . translate("Unreleased") . '!)</x>';
    } else {
        if ($thisVersion["BRANCH"] == "main" && $thisVersion["VERSION"] != $latestVersion["VERSION"]) {
            return '<x style="color:red">v' . $thisVersion["VERSION"] . ' (' . translate("Outdated") . '!)</x>';
        } else {
            return 'v' . $thisVersion["VERSION"] . '';
        }
    }
}
function generateKey($length)
{
    $length = 16;
    $bytes = openssl_random_pseudo_bytes($length);
    $hex = bin2hex($bytes);
    return $hex;
}


/* Data Conversion Functions */
function encryptData($data, $encryption_key)
{
    $encryption_iv = hex2bin($encryption_key);
    return openssl_encrypt($data, "AES-128-CTR", $encryption_key, 0, $encryption_iv);
}
function decryptData($encryption_key)
{
    $encryption_iv = hex2bin($encryption_key);
    return openssl_decrypt(getRecord("encrypted_contents", $encryption_key), "AES-128-CTR", $encryption_key, 0, $encryption_iv);
}


/* System Setup & Checking Functions */
function initialiseSystem()
{
    function createStorageMethodEndpoints()
    {
        if (!is_dir("./local-storage/")) mkdir("./local-storage/"); // Create storage folder if not present
        if (!file_exists("./local-storage/.cache")) touch("./local-storage/.cache"); // Create cache file if not present
        if (!file_exists("./.config")) touch("./.config"); // Create config file if not present
    }
    function checkConfigValues()
    {
        $configuration = json_decode(file_get_contents("./.config", true), true);

        /* Config File Variables */
        if ($configuration["STORAGE_METHOD"] == "") {
            $TEMP_STORAGE_METHOD = "mysql"; // Reset configuration to default value
        } else {
            $TEMP_STORAGE_METHOD = $configuration["STORAGE_METHOD"];
        }
        if ($_SERVER["SERVER_PORT"] == "80" || $_SERVER["SERVER_PORT"] == "443") {
            $TEMP_PATH = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
            $TEMP_PATH .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
            $TEMP_PATH = rtrim($TEMP_PATH, '/'); // Remove last slash from the new URL
        } else { // Webserver is using a custom port!
            $TEMP_PATH = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
            $TEMP_PATH .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
            $TEMP_PATH = rtrim($TEMP_PATH, '/'); // Remove last slash from the new URL
        }
        if ($configuration["LANGUAGE"] == "") {
            $TEMP_LANGUAGE = "auto"; // Reset configuration to default value
        } else {
            $TEMP_LANGUAGE = $configuration["LANGUAGE"];
        }
        if ($configuration["DEBUG_MODE"] == "") {
            $TEMP_DEBUGMODE = "false"; // Reset configuration to default value
        } else {
            $TEMP_DEBUGMODE = $configuration["DEBUG_MODE"];
        }

        /* Config File If Empty Validation*/
        if ($configuration["STORAGE_METHOD"] == "") {
            file_put_contents("./.config", json_encode(array("STORAGE_METHOD" => "$TEMP_STORAGE_METHOD", "LANGUAGE" => "$TEMP_LANGUAGE", "INSTALLATION_PATH" => "$TEMP_PATH", "DEBUG_MODE" => "$TEMP_DEBUGMODE"))); // Set contents of new config file
        }
        if ($configuration["INSTALLATION_PATH"] == "") {
            file_put_contents("./.config", json_encode(array("STORAGE_METHOD" => "$TEMP_STORAGE_METHOD", "LANGUAGE" => "$TEMP_LANGUAGE", "INSTALLATION_PATH" => "$TEMP_PATH", "DEBUG_MODE" => "$TEMP_DEBUGMODE"))); // Set contents of new config file
        }
        if ($configuration["LANGUAGE"] == "") {
            file_put_contents("./.config", json_encode(array("STORAGE_METHOD" => "$TEMP_STORAGE_METHOD", "LANGUAGE" => "$TEMP_LANGUAGE", "INSTALLATION_PATH" => "$TEMP_PATH", "DEBUG_MODE" => "$TEMP_DEBUGMODE"))); // Set contents of new config file
        }
        if ($configuration["DEBUG_MODE"] == "") {
            file_put_contents("./.config", json_encode(array("STORAGE_METHOD" => "$TEMP_STORAGE_METHOD", "LANGUAGE" => "$TEMP_LANGUAGE", "INSTALLATION_PATH" => "$TEMP_PATH", "DEBUG_MODE" => "$TEMP_DEBUGMODE"))); // Set contents of new config file
        }
    }
    function setupStorageMethod()
    {
        $cache = json_decode(file_get_contents("./local-storage/.cache", true), true);
        $configuration = json_decode(file_get_contents("./.config", true), true);

        if (strtolower($configuration["STORAGE_METHOD"]) == "mysql") {
            if (!file_exists("./Modules/Database.env")) {
                touch("./Modules/Database.env"); // Create database configuration file
                require "./Public/error_docs/DatabaseConfig.php";
                die();
            } else {
                $json = json_decode(file_get_contents("./Modules/Database.env", true), true);
                if ($json["DATABASE"] == "" || $json["HOSTNAME"] == "") {
                    require "./Public/error_docs/DatabaseConfig.php";
                    die();
                } else { // Test database connection
                    $conn = new mysqli($json["HOSTNAME"], $json["USERNAME"], $json["PASSWORD"], $json["DATABASE"]);
                    if ($conn->connect_error) {
                        require "./Public/error_docs/DatabaseCredentials.php"; // throw error page if invalid credentials
                        die();
                    } else {
                        $cache = json_decode(file_get_contents("./local-storage/.cache"), true);
                        if ($cache["DO-NOT-TOUCH:database_installation_status"] == "false") {
                            $tableCreateSQL = "CREATE TABLE IF NOT EXISTS `quickblaze_records` (`record_id` int(11) NOT NULL, `encrypted_contents` longtext NOT NULL, `encryption_token` varchar(128) NOT NULL, `source_ip` varchar(100) NOT NULL, `record_date` timestamp(5) NOT NULL DEFAULT current_timestamp(5)) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
                            $addPrimaryKeySQL = "ALTER TABLE `quickblaze_records` ADD PRIMARY KEY (`record_id`);";
                            if ($conn->query($tableCreateSQL)) {
                                if ($conn->query($addPrimaryKeySQL)) {
                                    file_put_contents("./local-storage/.cache", '{"DO-NOT-TOUCH:database_installation_status": "true"}');
                                }
                            } else {
                                require "./Public/error_docs/DatabaseCredentials.php"; // throw error page if invalid credentials
                                die();
                            }
                        }
                        // Always reset auto-increment
                        if (!$conn->query("ALTER TABLE `quickblaze_records` MODIFY `record_id` int(11) NOT NULL AUTO_INCREMENT;")) {
                            require "./Public/error_docs/DatabaseConfig.php"; // throw error page if invalid credentials
                            die();
                        }
                    }
                    $conn->close();
                }
            }
        } else if (strtolower($configuration["STORAGE_METHOD"]) == "filetree") {
            $baseStorageFolder = "./local-storage";
            if (!is_dir("$baseStorageFolder/")) mkdir("$baseStorageFolder/");
            if (!is_dir("$baseStorageFolder/encryptions/")) mkdir("$baseStorageFolder/encryptions/");
        } else { // Server storage method not set
            require "./Public/error_docs/ServerConfiguration.php"; // throw error page if invalid configuration
            die();
        }
    }

    /* Call Functions */
    createStorageMethodEndpoints(); // Setup files and folders the system will store data.
    checkConfigValues(); // Validate if configuration values are correct & present.
    setupStorageMethod(); // Setup how the system will store the data via the configured method.
    /* End Functions */
}

/* Database Interaction Functions */
function insertRecord($encrypted_contents, $encryption_token)
{
    $configuration = json_decode(file_get_contents("./.config", true), true);
    $json = json_decode(file_get_contents("./Modules/Database.env", true), true);
    if ($_SERVER['HTTP_CF_CONNECTING_IP'] == "" || !isset($_SERVER['HTTP_CF_CONNECTING_IP'])) $_SERVER['HTTP_CF_CONNECTING_IP'] = $_SERVER["REMOTE_ADDR"];
    if (strtolower($configuration["STORAGE_METHOD"]) == "mysql") {
        $mysqli = new mysqli($json["HOSTNAME"], $json["USERNAME"], $json["PASSWORD"], $json["DATABASE"]);
        if ($mysqli->connect_errno) {
            require "./Public/error_docs/DatabaseCredentials.php";
            die();
        }
        $source_ip = filter_var($_SERVER['HTTP_CF_CONNECTING_IP'], FILTER_VALIDATE_IP) ?? filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP);
        $record_date = date("Y-m-d H:i:s");
        if ($mysqli->query("INSERT INTO `quickblaze_records` (`encrypted_contents`, `encryption_token`, `source_ip`, `record_date`) VALUES ('$encrypted_contents', '$encryption_token', '$source_ip', '$record_date');") === TRUE) {
            return true;
        } else {
            die($mysqli->error);
        }
        $mysqli->close();
    } elseif (strtolower($configuration["STORAGE_METHOD"]) == "filetree") {
        $baseStorageFolder = "./local-storage";
        $uniqueIdentifier = uniqid($encryption_token); // Assign ID to new storage folder
        if (!is_dir("$baseStorageFolder/encryptions/$uniqueIdentifier/")) mkdir("$baseStorageFolder/encryptions/$uniqueIdentifier/"); // Create temporary unique folder with ID
        if (!file_exists("$baseStorageFolder/encryptions/$uniqueIdentifier/data.json")) touch("$baseStorageFolder/encryptions/$uniqueIdentifier/data.json"); // Create encryption data file
        $source_ip = filter_var($_SERVER['HTTP_CF_CONNECTING_IP'], FILTER_VALIDATE_IP) ?? filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP);
        $record_date = date("Y-m-d H:i:s");
        file_put_contents("$baseStorageFolder/encryptions/$uniqueIdentifier/data.json", '{"filestore_id": "' . $uniqueIdentifier . '", "encrypted_contents": "' . $encrypted_contents . '", "encryption_token": "' . $encryption_token . '", "source_ip": "' . $source_ip . '", "record_date": "' . $record_date . '"}'); // Set data file encryption data
    } else {
        require "./Public/error_docs/ServerConfiguration.php"; // throw error page if invalid configuration
        die();
    }
}
function destroyRecord($token)
{
    $configuration = json_decode(file_get_contents("./.config", true), true);
    $json = json_decode(file_get_contents("./Modules/Database.env", true), true);
    if (strtolower($configuration["STORAGE_METHOD"]) == "mysql") {
        $mysqli = new mysqli($json["HOSTNAME"], $json["USERNAME"], $json["PASSWORD"], $json["DATABASE"]);
        if ($mysqli->connect_errno) {
            require "./Public/error_docs/DatabaseCredentials.php";
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
        $baseStorageFolder = "./local-storage";
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
        require "./Public/error_docs/ServerConfiguration.php"; // throw error page if invalid configuration
        die();
    }
}
function getRecord($dataToFetch, $encryption_token)
{
    $configuration = json_decode(file_get_contents("./.config", true), true);
    $json = json_decode(file_get_contents("./Modules/Database.env", true), true);
    if (strtolower($configuration["STORAGE_METHOD"]) == "mysql") {
        $mysqli = new mysqli($json["HOSTNAME"], $json["USERNAME"], $json["PASSWORD"], $json["DATABASE"]);
        if ($mysqli->connect_errno) {
            require "./Public/error_docs/DatabaseCredentials.php";
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
        $baseStorageFolder = "./local-storage";
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
        require "./Public/error_docs/ServerConfiguration.php"; // throw error page if invalid configuration
        die();
    }
}

/* Translation Feature */
function translate($q)
{
    $lang = "en"; // Default language
    $configuration = json_decode(file_get_contents("./.config", true), true);
    if ($configuration["LANGUAGE"] == "auto") {
        $tl = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
    } else if ($configuration["LANGUAGE"] != "") {
        $tl = $configuration["LANGUAGE"];
    } else {
        $tl = "en";
    }
    $res = file_get_contents("https://translate.googleapis.com/translate_a/single?client=gtx&ie=UTF-8&oe=UTF-8&dt=bd&dt=ex&dt=ld&dt=md&dt=qca&dt=rw&dt=rm&dt=ss&dt=t&dt=at&sl=" . $lang . "&tl=" . $tl . "&hl=hl&q=" . urlencode($q), $_SERVER['DOCUMENT_ROOT'] . "/transes.html");
    $res = json_decode($res);
    return $res[0][0][0];
}