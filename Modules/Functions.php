<?php
/* Prevent XSS input */
function sanitizeXSS()
{
    $_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $_SERVER  = filter_input_array(INPUT_SERVER, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $_REQUEST = (array)$_POST + (array)$_GET + (array)$_REQUEST;
}

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
function determineMessageContent()
{
    if (getRecord("encrypted_contents", htmlspecialchars($_GET["key"]), ENT_QUOTES, 'UTF-8') == null) {
        header("Location: 404");
    } else {
        if (!isset($_GET["confirm"])) {
            echo '
            <h6>
                ' . translate("Decrypt & View Message?", "en") . '
            </h6>
            <a class="btn btn-primary submit-button darkmode-ignore" href="?confirm&key=' . htmlspecialchars($_GET["key"]) . '">
            ' . translate("View Message", "en") . '
            </a>';
        } else {
            echo '
            <h6>
                ' . translate("This message has been destroyed!", "en") . '
            </h6>
            <textarea disabled type="text" class="form-control" id="linkbox" name="data">' . htmlspecialchars(decryptData(htmlspecialchars($_GET["key"]))) . '</textarea>
            <br>
            <button type="button" class="btn btn-primary submit-button darkmode-ignore" onclick="copyToClipboard(\'#linkbox\')">
                ' . translate("Copy Message", "en") . '
            </button>
            <a class="btn btn-secondary submit-button darkmode-ignore" href="./">
                ' . translate("Return Home", "en") . '
            </a>';
            destroyRecord(htmlspecialchars($_GET["key"], ENT_QUOTES, 'UTF-8')); // destroy record
        }
    }
}
function getSubmittedKey()
{
    error_reporting(0); // disable error reporting
    if (isset($_GET["submitted"]) && $_GET["submitted"] != "") {
        $fullUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]" . str_replace("?submitted=", "view?key=", htmlspecialchars($_SERVER['REQUEST_URI']));
        echo htmlspecialchars($fullUrl, ENT_QUOTES, 'UTF-8');
    } else {
        if (isset($_GET["submitted"])) {
            header("Location: ./");
        }
    }
    error_reporting(E_ALL); // enable error reporting
}
function determineSubmissionFooter()
{
    if (isset($_GET["submitted"])) {
        echo '
        <br>
        <p class="text-muted">
            ' . translate("Share this link anywhere on the internet. The message will be automatically destroyed once viewed.", "en") . '
        </p>

        <button type="button" class="btn btn-primary submit-button darkmode-ignore" onclick="copyToClipboard(\'#linkbox\')">
            ' . translate("Copy Link", "en") . '
        </button>
    
        <a class="btn btn-secondary submit-button darkmode-ignore" href="./">
            ' . translate("Create New", "en") . '
        </a>';
    } else {
        echo '
        <br>
        <button class="btn btn-primary submit-button darkmode-ignore" type="submit">
            ' . translate("Generate Link", "en") . '
        </button>';
    }
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
    if ($thisVersion["VERSION"] != $latestVersion["VERSION"]) {
        return '<x style="color:red">v' . $thisVersion["VERSION"] . ' (Outdated!)</x>';
    } else {
        return 'v' . $thisVersion["VERSION"] . '';
    }
}

/* Database Interaction Functions */
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

function decryptData($encryption_key) // getRecord("encrypted_contents", $dataKey)
{
    $encryption_iv = hex2bin($encryption_key);
    return openssl_decrypt(getRecord("encrypted_contents", $encryption_key), "AES-128-CTR", $encryption_key, 0, $encryption_iv);
}

/* Database Interaction Functions */
function setupDatabase()
{
    error_reporting(0); // disable error reporting
    if (!file_exists("./Modules/InstallationStatus.json")) {
        touch("./Modules/InstallationStatus.json");
        file_put_contents("./Modules/InstallationStatus.json", json_encode(array("INSTALLED" => "false")));
    }
    $json = json_decode(file_get_contents("./Modules/InstallationStatus.json", true), true);
    if ($json["INSTALLED"] == "false" || $json["INSTALLED"] == "") {
        $json = json_decode(file_get_contents("./Modules/Database.env", true), true);
        try { // attempt database connection
            $mysqli = new mysqli($json["HOSTNAME"], $json["USERNAME"], $json["PASSWORD"], $json["DATABASE"]);
        } catch (mysqli_sql_exception $e) {
            require "./Public/Error/DatabaseCredentials.php"; // throw error page if invalid credentials
            die();
        }
        $tableCreateSQL = "CREATE TABLE IF NOT EXISTS `quickblaze_records` (`record_id` int(11) NOT NULL,`encrypted_contents` longtext NOT NULL,`encryption_token` varchar(128) NOT NULL,`source_ip` varchar(100) NOT NULL, `record_date` timestamp(5) NOT NULL DEFAULT current_timestamp(5)) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        $addPrimaryKeySQL = "ALTER TABLE `quickblaze_records` ADD PRIMARY KEY (`record_id`);";
        $autoIncrementSQL = "ALTER TABLE `quickblaze_records` MODIFY `record_id` int(11) NOT NULL AUTO_INCREMENT;";
        if ($mysqli->query($tableCreateSQL) === TRUE) {
            if ($mysqli->query($addPrimaryKeySQL) === TRUE) {
                if ($mysqli->query($autoIncrementSQL) === TRUE) {
                    file_put_contents("./Modules/InstallationStatus.json", json_encode(array("INSTALLED" => "true")));
                    return true;
                } else {
                    require "./Public/Error/DatabaseCredentials.php"; // throw error page if invalid credentials
                    die();
                }
            } else {
                require "./Public/Error/DatabaseCredentials.php"; // throw error page if invalid credentials
                die();
            }
        } else {
            require "./Public/Error/DatabaseCredentials.php"; // throw error page if invalid credentials
            die();
        }

        $mysqli->close();
    }
    error_reporting(E_ALL); // enable error reporting
}
function checkDatabase()
{
    error_reporting(0); // disable error reporting
    if (!file_exists("./Modules/Database.env")) {
        touch("./Modules/Database.env");
        require "./Public/Error/DatabaseConfig.php";
        die();
    } else {
        $json = json_decode(file_get_contents("./Modules/Database.env", true), true);
        if ($json["DATABASE"] == "" || $json["HOSTNAME"] == "") {
            require "./Public/Error/DatabaseConfig.php";
            die();
        }
    }
    $status = json_decode(file_get_contents("./Modules/InstallationStatus.json", true), true);
    if ($status["INSTALLED"] == "false") {
        setupDatabase();
    }
    error_reporting(E_ALL); // enable error reporting
}

function insertRecord($encrypted_contents, $encryption_token)
{
    $json = json_decode(file_get_contents("./Modules/Database.env", true), true);
    $mysqli = new mysqli($json["HOSTNAME"], $json["USERNAME"], $json["PASSWORD"], $json["DATABASE"]);
    if ($mysqli->connect_errno) {
        require "./Public/Error/DatabaseCredentials.php";
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
}

function destroyRecord($token)
{
    $json = json_decode(file_get_contents("./Modules/Database.env", true), true);
    $mysqli = new mysqli($json["HOSTNAME"], $json["USERNAME"], $json["PASSWORD"], $json["DATABASE"]);
    if ($mysqli->connect_errno) {
        require "./Public/Error/DatabaseCredentials.php";
        die();
    }
    $token = filter_var($token, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    if ($mysqli->query("DELETE FROM `quickblaze_records` WHERE `encryption_token` = '$token';") === TRUE) {
        return true;
    } else {
        die($mysqli->error);
    }
    $mysqli->close();
}

function getRecord($dataToFetch, $encryption_token)
{
    $json = json_decode(file_get_contents("./Modules/Database.env", true), true);
    $mysqli = new mysqli($json["HOSTNAME"], $json["USERNAME"], $json["PASSWORD"], $json["DATABASE"]);
    if ($mysqli->connect_errno) {
        require "./Public/Error/DatabaseCredentials.php";
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
}

/* Translation Feature */
function translate($q, $sl)
{
    $config = json_decode(file_get_contents("./.version", true), true);
    if ($config["LANGUAGE"] == "auto") {
        $tl = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
    } else {
        if ($config["LANGUAGE"] != "") {
            $tl = $config["LANGUAGE"];
        } else {
            $tl = "en";
        }
    }
    $res = file_get_contents("https://translate.googleapis.com/translate_a/single?client=gtx&ie=UTF-8&oe=UTF-8&dt=bd&dt=ex&dt=ld&dt=md&dt=qca&dt=rw&dt=rm&dt=ss&dt=t&dt=at&sl=" . $sl . "&tl=" . $tl . "&hl=hl&q=" . urlencode($q), $_SERVER['DOCUMENT_ROOT'] . "/transes.html");
    $res = json_decode($res);
    return $res[0][0][0];
}
