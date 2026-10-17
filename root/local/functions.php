<?php
function debug_log($message)
{
    $configuration = json_decode(file_get_contents("./config.json", true), true);
    if (isset($configuration["DEBUG_MODE"]) && strtolower($configuration["DEBUG_MODE"]) === "true") {
        $logDirectory = "./local/logs";
        if (!is_dir($logDirectory)) {
            mkdir($logDirectory, 0700, true); // create logs dir
        }
        $logFile = $logDirectory . "/debug.log";
        if (file_exists($logFile) && filesize($logFile) > 5 * 1024 * 1024) { // if larger than 5MB,,,
            rename($logFile, $logDirectory . "/debug.log.1"); // rotate log file
        }
        $timestamp = date("Y-m-d H:i:s");
        file_put_contents($logFile, "[$timestamp] $message\n", FILE_APPEND); // append to log file
    }
}

function processData($data)
{
    $encryptionKey = generateKey(16);
    $encryptedData = encryptData($data, $encryptionKey); // Encrypt data
    if ($encryptedData === false || !insertRecord($encryptedData, $encryptionKey)) {
        throw new RuntimeException("Unable to store encrypted message.");
    }
    return $encryptionKey;
}

function determineSystemVersion()
{
    if (!file_exists("./version.json")) {
        touch("./version.json");
        $latestVersion = json_decode(file_get_contents("https://raw.githubusercontent.com/arizon-dev/oneview/main/version.json?cacheUpdate=" . rand(0, 100), true), true);
        file_put_contents("./version.json", json_encode(array("BRANCH" => $latestVersion["BRANCH"], "VERSION" => $latestVersion["VERSION"], "LANGUAGE" => "auto")));
    }
    $thisVersion = json_decode(file_get_contents("./version.json", true), true);
    return 'v' . $thisVersion["VERSION"] . '';
}

function generateKey($length = 16)
{
    return bin2hex(random_bytes($length));
}

/* Data Conversion Functions */
function encryptData($data, $encryption_key)
{
    $key = hex2bin($encryption_key);
    $nonce = random_bytes(12);
    $tag = "";
    $ciphertext = openssl_encrypt($data, "aes-128-gcm", $key, OPENSSL_RAW_DATA, $nonce, $tag);
    if ($ciphertext === false) {
        return false;
    }
    return "v2:" . base64_encode($nonce . $tag . $ciphertext);
}

function decryptData($encryption_key)
{
    $encrypted_data = getRecord("encrypted_contents", $encryption_key);
    if ($encrypted_data === false) {
        return false;
    }
    return decryptEncryptedData($encrypted_data, $encryption_key);
}

function decryptEncryptedData($encrypted_data, $encryption_key)
{
    if (strpos($encrypted_data, "v2:") === 0) {
        $payload = base64_decode(substr($encrypted_data, 3), true);
        if ($payload === false || strlen($payload) < 28) {
            return false;
        }
        $dat = substr($payload, 0, 12);
        $tag = substr($payload, 12, 16);
        $ciphertext = substr($payload, 28);
        return openssl_decrypt($ciphertext, "aes-128-gcm", hex2bin($encryption_key), OPENSSL_RAW_DATA, $dat, $tag);
    }

    // Keep messages created before authenticated encryption readable.
    return openssl_decrypt($encrypted_data, "AES-128-CTR", $encryption_key, 0, hex2bin($encryption_key));
}

/* System Setup & Checking Functions */
function initialiseSystem()
{
    function createStorageLocations()
    {
        if (!is_dir("./local/"))
            mkdir("./local/"); // Create storage folder if not present
        if (!file_exists("./config.json"))
            touch("./config.json"); // Create config file if not present
    }
    function checkConfigValues()
    {
        $configuration = json_decode(file_get_contents("./config.json", true), true);
        $storageMethod = $configuration["STORAGE_METHOD"];

        /* Config File Variables */
        if ($storageMethod == "") {
            $TMP_STORAGE_METHOD = "json"; // Reset configuration to default value
        } else {
            $TMP_STORAGE_METHOD = $storageMethod;
        }

        if ($configuration["LANGUAGE"] == "") {
            $TMP_LANGUAGE = "auto"; // Reset configuration to default value
        } else {
            $TMP_LANGUAGE = $configuration["LANGUAGE"];
        }

        if ($configuration["DEBUG_MODE"] == "") {
            $TMP_DEBUGMODE = "false"; // Reset configuration to default value
        } else {
            $TMP_DEBUGMODE = $configuration["DEBUG_MODE"];
        }

        /* Populate config file if empty */
        if ($storageMethod == "" || $configuration["LANGUAGE"] == "" || $configuration["DEBUG_MODE"] == "") {
            file_put_contents("./config.json", json_encode(array("STORAGE_METHOD" => "$TMP_STORAGE_METHOD", "LANGUAGE" => "$TMP_LANGUAGE", "DEBUG_MODE" => "$TMP_DEBUGMODE"))); // Set contents of new config file
        }
    }
    function setupStorageMethod()
    {

        $configuration = json_decode(file_get_contents("./config.json", true), true);
        $storageMethod = $configuration["STORAGE_METHOD"];

        if (strtolower($storageMethod) == "mysql") {
            if (!file_exists("./local/database.json")) {
                touch("./local/database.json"); // Create database configuration file
                file_put_contents("./local/database.json", '{"HOSTNAME": "database.example.com", "PORT": "3306", "USERNAME": "oneviewuser", "PASSWORD": "password123", "DATABASE": "oneview"}');
                require "./public/error/DatabaseCredentials.php";
                die();
            } else {
                $json = json_decode(file_get_contents("./local/database.json", true), true);
                if ($json["DATABASE"] == "" || $json["HOSTNAME"] == "" || $json["USERNAME"] == "" || $json["PASSWORD"] == "" || $json["PORT"] == ""  || $json["HOSTNAME"] == "database.example.com") {
                    debug_log("OneView: invalid database configuration. Please check the database.json file.");
                    require "./public/error/DatabaseCredentials.php";
                    die();
                } else { // Test database connection
                    try {
                        $conn = new mysqli($json["HOSTNAME"], $json["USERNAME"], $json["PASSWORD"], $json["DATABASE"], $json["PORT"]);
                        if ($conn->connect_error) {
                            debug_log("OneView: unable to connect to database. Please check the database.json file.");
                            require "./public/error/DatabaseConnection.php"; // throw error page if invalid credentials
                            die();
                        } else {
                            $tableCreateSQL = "CREATE TABLE IF NOT EXISTS `oneview_records` (`record_id` int(11) NOT NULL AUTO_INCREMENT, `encrypted_contents` longtext NOT NULL, `encryption_token` varchar(128) NOT NULL, `source_ip` varchar(100) NOT NULL, `record_date` timestamp(5) NOT NULL DEFAULT current_timestamp(5), PRIMARY KEY (`record_id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
                            if (!$conn->query($tableCreateSQL)) {
                                debug_log("OneView: unable to create database table. Please check the database.json file.");
                                require "./public/error/DatabaseCredentials.php"; // throw error page if invalid credentials
                                die();
                            }
                        }
                        $conn->close();
                    } catch (Throwable $error) {
                        debug_log("OneView: unable to initialize database connection. Please check the database.json file.");
                        require "./public/error/DatabaseCredentials.php"; // throw error page if invalid credentials
                        die();
                    }
                }
            }
        } else if (strtolower($storageMethod) == "json") {
            $baseStorageFolder = "./local";
            if (!is_dir("$baseStorageFolder/"))
                mkdir("$baseStorageFolder/");
            if (!is_dir("$baseStorageFolder/encryptions/"))
                mkdir("$baseStorageFolder/encryptions/");
        } else { // Server storage method not set
            debug_log("OneView: invalid storage method configured. Must be either 'mysql' or 'json'.");
            require "./public/error/ServerConfiguration.php"; // throw error page if invalid configuration
            die();
        }
    }

    /* Call Functions */
    createStorageLocations(); // Setup files and folders the system will store data.
    checkConfigValues(); // Validate if configuration values are correct & present.
    setupStorageMethod(); // Setup how the system will store the data via the configured method.
}

/* Database Interaction Functions */
function insertRecord($encrypted_contents, $encryption_token)
{
    $configuration = json_decode(file_get_contents("./config.json", true), true);
    $storageMethod = $configuration["STORAGE_METHOD"];

    $json = strtolower($storageMethod) == "mysql"
        ? json_decode(file_get_contents("./local/database.json", true), true)
        : null;
    $client_ip = isset($_SERVER['HTTP_CF_CONNECTING_IP']) && $_SERVER['HTTP_CF_CONNECTING_IP'] !== ""
        ? $_SERVER['HTTP_CF_CONNECTING_IP']
        : (isset($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"] : "");
    if (strtolower($storageMethod) == "mysql") {
        $mysqli = new mysqli($json["HOSTNAME"], $json["USERNAME"], $json["PASSWORD"], $json["DATABASE"], $json["PORT"]);
        if ($mysqli->connect_errno) {
            require "./public/error/DatabaseCredentials.php";
            die();
        }
        $source_ip = filter_var($client_ip, FILTER_VALIDATE_IP) ?: "";
        $stmt = $mysqli->prepare("INSERT INTO `oneview_records` (`encrypted_contents`, `encryption_token`, `source_ip`) VALUES (?, ?, ?)");
        if ($stmt === false) {
            debug_log("OneView: unable to prepare message insert.");
            $mysqli->close();
            return false;
        }
        $stmt->bind_param("sss", $encrypted_contents, $encryption_token, $source_ip);
        $success = $stmt->execute();
        if (!$success) {
            debug_log("OneView: unable to store encrypted message.");
        } else {
            debug_log("OneView: encrypted message stored successfully.");
        }
        $stmt->close();
        $mysqli->close();
        return $success;
    } elseif (strtolower($storageMethod) == "json") {
        $baseStorageFolder = "./local";
        $uniqueIdentifier = uniqid($encryption_token); // Assign ID to new storage folder
        if (!is_dir("$baseStorageFolder/encryptions/$uniqueIdentifier/"))
            mkdir("$baseStorageFolder/encryptions/$uniqueIdentifier/"); // Create temporary unique folder with ID
        if (!file_exists("$baseStorageFolder/encryptions/$uniqueIdentifier/data.json"))
            touch("$baseStorageFolder/encryptions/$uniqueIdentifier/data.json"); // Create encryption data file
        $source_ip = filter_var($client_ip, FILTER_VALIDATE_IP) ?: "";
        $record_date = date("Y-m-d H:i:s");
        $record = array("filestore_id" => $uniqueIdentifier, "encrypted_contents" => $encrypted_contents, "encryption_token" => $encryption_token, "source_ip" => $source_ip, "record_date" => $record_date);
        return file_put_contents("$baseStorageFolder/encryptions/$uniqueIdentifier/data.json", json_encode($record)) !== false;
    } else {
        require "./public/error/ServerConfiguration.php"; // throw error page if invalid configuration
        die();
    }
}

function destroyRecord($token)
{
    $configuration = json_decode(file_get_contents("./config.json", true), true);
    $storageMethod = $configuration["STORAGE_METHOD"];

    $json = strtolower($storageMethod) == "mysql"
        ? json_decode(file_get_contents("./local/database.json", true), true)
        : null;
    if (strtolower($storageMethod) == "mysql") {
        $mysqli = new mysqli($json["HOSTNAME"], $json["USERNAME"], $json["PASSWORD"], $json["DATABASE"], $json["PORT"]);
        if ($mysqli->connect_errno) {
            require "./public/error/DatabaseCredentials.php";
            die();
        }
        $stmt = $mysqli->prepare("DELETE FROM `oneview_records` WHERE `encryption_token` = ?");
        if ($stmt === false) {
            debug_log("OneView: unable to prepare message deletion.");
            $mysqli->close();
            return false;
        }
        $stmt->bind_param("s", $token);
        $success = $stmt->execute();
        if (!$success) {
            debug_log("OneView: unable to delete viewed message.");
        }
        $stmt->close();
        $mysqli->close();
        return $success;
    } elseif (strtolower($storageMethod) == "json") {
        $baseStorageFolder = "./local";
        $dir = new DirectoryIterator("$baseStorageFolder/encryptions/");
        foreach ($dir as $fileinfo) {
            if ($fileinfo->isDir() && !$fileinfo->isDot()) {
                $recordDirectory = "$baseStorageFolder/encryptions/" . $fileinfo->getFilename();
                $recordPath = "$recordDirectory/data.json";
                if (!is_file($recordPath)) {
                    continue;
                }
                $theFile = json_decode(file_get_contents($recordPath), true);
                if (is_array($theFile) && isset($theFile["encryption_token"]) && is_string($theFile["encryption_token"]) && hash_equals($theFile["encryption_token"], $token)) {
                    return unlink($recordPath) && rmdir($recordDirectory);
                }
            }
        }
        return false;
    } else { // Server storage method not set
        require "./public/error/ServerConfiguration.php"; // throw error page if invalid configuration
        die();
    }
}

function getRecord($dataToFetch, $encryption_token)
{
    $configuration = json_decode(file_get_contents("./config.json", true), true);
    $storageMethod = $configuration["STORAGE_METHOD"];

    $json = strtolower($storageMethod) == "mysql"
        ? json_decode(file_get_contents("./local/database.json", true), true)
        : null;
    if (!in_array($dataToFetch, array("encrypted_contents"), true)) {
        return false;
    }
    if (strtolower($storageMethod) == "mysql") {
        $mysqli = new mysqli($json["HOSTNAME"], $json["USERNAME"], $json["PASSWORD"], $json["DATABASE"], $json["PORT"]);
        if ($mysqli->connect_errno) {
            require "./public/error/DatabaseCredentials.php";
            die();
        }
        $stmt = $mysqli->prepare("SELECT `encrypted_contents` FROM `oneview_records` WHERE `encryption_token` = ? LIMIT 1");
        if ($stmt === false) {
            debug_log("OneView: unable to prepare message lookup.");
            $mysqli->close();
            return false;
        }
        $stmt->bind_param("s", $encryption_token);
        $stmt->execute();
        $stmt->bind_result($encrypted_contents);
        $found = $stmt->fetch();
        $stmt->close();
        $mysqli->close();
        return $found ? $encrypted_contents : false;
    } elseif (strtolower($storageMethod) == "json") {
        $baseStorageFolder = "./local";
        $dir = new DirectoryIterator("$baseStorageFolder/encryptions/");
        foreach ($dir as $fileinfo) {
            if ($fileinfo->isDir() && !$fileinfo->isDot()) { // $fileinfo->getFilename()
                $recordPath = "$baseStorageFolder/encryptions/" . $fileinfo->getFilename() . "/data.json";
                if (!is_file($recordPath)) {
                    continue;
                }
                $theFile = json_decode(file_get_contents($recordPath), true);
                if (is_array($theFile) && isset($theFile["encryption_token"]) && is_string($theFile["encryption_token"]) && hash_equals($theFile["encryption_token"], $encryption_token)) {
                    return $theFile[$dataToFetch];
                }
            }
        }
    } else { // Server storage method not set
        require "./public/error/ServerConfiguration.php"; // throw error page if invalid configuration
        die();
    }
}

/* Translation Feature */
function write($q)
{
    if (!is_string($q) || $q === "") {
        return "";
    }

    $configuration = file_exists("./config.json") ? json_decode(file_get_contents("./config.json"), true) : null;
    $targetLanguage = is_array($configuration) && isset($configuration["LANGUAGE"])
        ? $configuration["LANGUAGE"]
        : "en";
    if ($targetLanguage === "auto") {
        $acceptLanguage = isset($_SERVER["HTTP_ACCEPT_LANGUAGE"]) ? $_SERVER["HTTP_ACCEPT_LANGUAGE"] : "en";
        $targetLanguage = trim(explode(",", $acceptLanguage)[0]);
        $targetLanguage = trim(explode(";", $targetLanguage)[0]);
    }
    if (!is_string($targetLanguage) || !preg_match('/\A[a-zA-Z]{2,3}(?:-[a-zA-Z0-9]{2,8})*\z/', $targetLanguage)) {
        debug_log("OneView: invalid translation language configured.");
        return $q;
    }

    $languageParts = explode("-", $targetLanguage);
    $targetLanguage = strtolower(array_shift($languageParts));
    foreach ($languageParts as $part) {
        $targetLanguage .= "-" . strtoupper($part);
    }
    if ($targetLanguage === "en") {
        return $q;
    }

    $cacheDirectory = __DIR__;
    if (!is_dir($cacheDirectory) && !mkdir($cacheDirectory, 0700, true) && !is_dir($cacheDirectory)) {
        debug_log("OneView: unable to access the translation cache directory.");
        return $q;
    }

    if (!is_dir($cacheDirectory . "/translations")) {
        if (!mkdir($cacheDirectory . "/translations", 0700, true) && !is_dir($cacheDirectory . "/translations")) {
            debug_log("OneView: unable to create the translation cache directory.");
            return $q;
        }
    }

    $cachePath = $cacheDirectory . "/translations/translations.json";
    $lockHandle = fopen($cacheDirectory . "/translations/translations.lock", "c");
    $cacheKey = hash("sha256", $q);
    if ($lockHandle === false) {
        debug_log("OneView: unable to open the translation cache lock.");
    } else {
        if (!flock($lockHandle, LOCK_EX)) {
            debug_log("OneView: unable to lock the translation cache.");
            fclose($lockHandle);
            $lockHandle = false;
        } else {
            $cache = file_exists($cachePath) ? json_decode(file_get_contents($cachePath), true) : array();
            if (!is_array($cache)) {
                $cache = array();
            }
            if (isset($cache[$targetLanguage][$cacheKey]) && is_string($cache[$targetLanguage][$cacheKey])) {
                $translation = $cache[$targetLanguage][$cacheKey];
                flock($lockHandle, LOCK_UN);
                fclose($lockHandle);
                return $translation;
            }
            flock($lockHandle, LOCK_UN);
        }
    }

    $query = http_build_query(
        array("client" => "dict-chrome-ex", "sl" => "en", "tl" => $targetLanguage, "q" => $q),
        "",
        "&",
        PHP_QUERY_RFC3986
    );
    $context = stream_context_create(array("http" => array("timeout" => 3)));
    $response = @file_get_contents("https://clients5.google.com/translate_a/t?" . $query, false, $context);
    $decodedResponse = $response === false ? null : json_decode($response, true);
    if (!is_array($decodedResponse) || !isset($decodedResponse[0]) || !is_string($decodedResponse[0]) || $decodedResponse[0] === "") {
        debug_log("OneView: Google translate returned no usable translation.");
        if (is_resource($lockHandle)) {
            fclose($lockHandle);
        }
        return $q;
    }
    $translation = $decodedResponse[0];

    if (is_resource($lockHandle)) {
        if (flock($lockHandle, LOCK_EX)) {
            $cache = file_exists($cachePath) ? json_decode(file_get_contents($cachePath), true) : array();
            if (!is_array($cache)) {
                $cache = array();
            }
            if (!isset($cache[$targetLanguage]) || !is_array($cache[$targetLanguage])) {
                $cache[$targetLanguage] = array();
            }
            if (isset($cache[$targetLanguage][$cacheKey]) && is_string($cache[$targetLanguage][$cacheKey])) {
                $translation = $cache[$targetLanguage][$cacheKey];
            } else {
                $cache[$targetLanguage][$cacheKey] = $translation;
                $encodedCache = json_encode($cache, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                if ($encodedCache === false || file_put_contents($cachePath, $encodedCache, LOCK_EX) === false) {
                    debug_log("OneView: unable to save the translation cache.");
                }
            }
            flock($lockHandle, LOCK_UN);
        } else {
            debug_log("OneView: unable to lock the translation cache for writing.");
        }
        fclose($lockHandle);
    }

    return $translation;
}