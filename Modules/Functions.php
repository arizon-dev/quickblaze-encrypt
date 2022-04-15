<?php
/* Prevent XSS input */
function sanitizeXSS()
{
    $_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
    $_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    $_SERVER  = filter_input_array(INPUT_SERVER, FILTER_SANITIZE_STRING);
    $_REQUEST = (array)$_POST + (array)$_GET + (array)$_REQUEST;
}

/* Internal Script Functions */
function processData($data)
{
    sanitizeXSS(); // Sanitize Script
    $encryptionKey = generateKey(64); // Create new key
    $encryptedData = encryptData($data, $encryptionKey); // Encrypt data
    insertRecord($encryptedData, $encryptionKey); // Insert new database record
    return $encryptionKey;
}
function ifTextBoxDisabled()
{
    sanitizeXSS(); // Sanitize Script
    if ($_GET["submitted"]) {
        echo "disabled";
    }
}
function viewMessageContent()
{
    sanitizeXSS(); // Sanitize Script
    if (getRecord("encrypted_contents", $_GET["key"]) == null) {
        header("Location: 404");
    } else {
        if (!isset($_GET["confirm"])) {
            echo '<h6>Decrypt & View Message?</h6><a class="btn btn-primary submit-button" href="?confirm&key=' . htmlspecialchars($_GET["key"]) . '">View Message</a>';
        } else {
            echo '<h6>This message has been destroyed!</h6><textarea disabled type="text" class="form-control" id="floatingInput" placeholder="Secret message" required name="data">' . htmlspecialchars(decryptData(htmlspecialchars($_GET["key"]))) . '</textarea><br><a class="btn btn-primary submit-button" href="./">Return Home</a>';
            destroyRecord($_GET["key"]); // destroy record
        }
    }
}
function getSubmittedKey()
{
    sanitizeXSS(); // Sanitize Script 
    if (isset($_GET['submitted'])) {
        $fullUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]" . str_replace("?submitted=", "view?key=", htmlspecialchars($_SERVER['REQUEST_URI']));
        echo htmlspecialchars($fullUrl, ENT_QUOTES, 'UTF-8');
    }
}
function determineSubmissionFooter()
{
    sanitizeXSS(); // Sanitize Script
    if (isset($_GET["submitted"])) {
        echo '<br><p class="text-muted">Share this link anywhere on the internet. The message will be automatically destroyed once viewed.</p><a class="btn btn-primary submit-button" href="./">Create New</a>';
    } else {
        echo '<br><button class="btn btn-primary submit-button" type="submit">Create One-Time Link</button>';
    }
}

/* Database Interaction Functions */
function generateKey($length)
{
    sanitizeXSS(); // Sanitize Script
    $length = 16;
    $bytes = openssl_random_pseudo_bytes($length);
    $hex = bin2hex($bytes);
    return $hex;
}

/* Data Conversion Functions */
function encryptData($data, $encryption_key)
{
    sanitizeXSS(); // Sanitize Script
    $encryption_iv = hex2bin($encryption_key);
    return openssl_encrypt($data, "AES-128-CTR", $encryption_key, 0, $encryption_iv);
}

function decryptData($encryption_key) // getRecord("encrypted_contents", $dataKey)
{
    sanitizeXSS(); // Sanitize Script
    $encryption_iv = hex2bin($encryption_key);
    return openssl_decrypt(getRecord("encrypted_contents", $encryption_key), "AES-128-CTR", $encryption_key, 0, $encryption_iv);
}

/* Database Interaction Functions */
function setupDatabase()
{
    sanitizeXSS(); // Sanitize Script
    $json = json_decode(file_get_contents("./Modules/InstallationStatus.json", true), true);
    if ($json["INSTALLED"] == "false") {
        $json = json_decode(file_get_contents("./Modules/Database.env", true), true);
        $mysqli = new mysqli($json["HOSTNAME"], $json["USERNAME"], $json["PASSWORD"], $json["DATABASE"]);
        if ($mysqli->connect_errno) {
            return $mysqli->connect_errno;
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
                    die($mysqli->error);
                }
            } else {
                die($mysqli->error);
            }
        } else {
            die($mysqli->error);
        }

        $mysqli->close();
    }
}

function insertRecord($encrypted_contents, $encryption_token)
{
    sanitizeXSS(); // Sanitize Script
    $json = json_decode(file_get_contents("./Modules/Database.env", true), true);
    $mysqli = new mysqli($json["HOSTNAME"], $json["USERNAME"], $json["PASSWORD"], $json["DATABASE"]);
    if ($mysqli->connect_errno) {
        return $mysqli->connect_errno;
    }
    $_SERVER['HTTP_CF_CONNECTING_IP'] = htmlspecialchars($_SERVER['HTTP_CF_CONNECTING_IP']);
    $_SERVER['REMOTE_ADDR'] = htmlspecialchars($_SERVER['REMOTE_ADDR']);
    $source_ip = htmlspecialchars($_SERVER['HTTP_CF_CONNECTING_IP']) ?? htmlspecialchars($_SERVER['REMOTE_ADDR']);
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
    sanitizeXSS(); // Sanitize Script
    $json = json_decode(file_get_contents("./Modules/Database.env", true), true);
    $mysqli = new mysqli($json["HOSTNAME"], $json["USERNAME"], $json["PASSWORD"], $json["DATABASE"]);
    if ($mysqli->connect_errno) {
        return $mysqli->connect_errno;
    }
    if ($mysqli->query("DELETE FROM `quickblaze_records` WHERE `encryption_token` = '$token';") === TRUE) {
        return true;
    } else {
        die($mysqli->error);
    }
    $mysqli->close();
}

function getRecord($dataToFetch, $encryption_token)
{
    sanitizeXSS(); // Sanitize Script
    $json = json_decode(file_get_contents("./Modules/Database.env", true), true);
    $mysqli = new mysqli($json["HOSTNAME"], $json["USERNAME"], $json["PASSWORD"], $json["DATABASE"]);
    if ($mysqli->connect_errno) {
        return $mysqli->connect_errno;
    }
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
