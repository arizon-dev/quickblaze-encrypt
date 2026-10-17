<?php
header("Content-Type: application/json; charset=utf-8");
header("Cache-Control: no-store, private");
header("X-Content-Type-Options: nosniff");
header("Referrer-Policy: no-referrer");

function respond($status, $payload)
{
    http_response_code($status);
    echo json_encode($payload);
    exit;
}

$action = isset($_GET["action"]) ? $_GET["action"] : "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_SERVER["HTTP_ORIGIN"])) {
    $origin = parse_url($_SERVER["HTTP_ORIGIN"]);
    $originHost = is_array($origin) && isset($origin["host"]) ? $origin["host"] : "";
    $originPort = is_array($origin) && isset($origin["port"]) ? ":" . $origin["port"] : "";
    if (strpos($originHost, ":") !== false) {
        $originHost = "[" . $originHost . "]";
    }
    $originAuthority = strtolower($originHost . $originPort);
    $requestHost = isset($_SERVER["HTTP_HOST"]) ? strtolower($_SERVER["HTTP_HOST"]) : "";
    if (!is_array($origin) || !isset($origin["scheme"]) || !in_array(strtolower($origin["scheme"]), array("http", "https"), true) || $originAuthority !== $requestHost) {
        respond(403, array("error" => "Cross-origin requests are not allowed."));
    }
}

if ($action === "decrypt" && $_SERVER["REQUEST_METHOD"] === "POST") {
    $key = isset($_POST["key"]) ? $_POST["key"] : "";
    if (!is_string($key) || !preg_match('/\A[a-f0-9]{32}\z/i', $key)) {
        respond(400, array("response" => false));
    }

    $key = strtolower($key);
    $message = decryptData($key);
    if ($message === false) {
        respond(404, array("response" => false));
    }
    if (!destroyRecord($key)) {
        debug_log("OneView: unable to delete viewed message.");
        respond(500, array("response" => false));
    }
    respond(200, array("response" => $message));
}

if ($action === "submit" && $_SERVER["REQUEST_METHOD"] === "POST") {
    $message = isset($_POST["data"]) ? $_POST["data"] : null;
    if (!is_string($message) || $message === "") {
        respond(400, array("error" => "Enter a message before encrypting it."));
    }
    if (strlen($message) > 10000) {
        respond(413, array("error" => "Messages must be 10 KB or less."));
    }

    try {
        respond(200, array("response" => processData($message)));
    } catch (Throwable $error) {
        debug_log("OneView: message encryption or storage failed: " . $error->getMessage());
        respond(500, array("error" => "The message could not be encrypted. Please try again."));
    }
}

if ($action === "checkConfig" && $_SERVER["REQUEST_METHOD"] === "GET") {
    $config = file_exists("./config.json") ? json_decode(file_get_contents("./config.json"), true) : null;
    $valid = is_array($config)
        && !empty($config["LANGUAGE"])
        && isset($config["STORAGE_METHOD"])
        && in_array(strtolower($config["STORAGE_METHOD"]), array("mysql", "json"), true);
    respond(200, array("response" => $valid ? "true" : "false"));
}

if ($action === "isDebugMode" && $_SERVER["REQUEST_METHOD"] === "GET") {
    $config = file_exists("./config.json") ? json_decode(file_get_contents("./config.json"), true) : null;
    $debugMode = is_array($config) && isset($config["DEBUG_MODE"]) ? $config["DEBUG_MODE"] : "false";
    respond(200, array("response" => $debugMode === "true" ? "true" : "false"));
}

// catchall
respond(405, array("error" => "Unsupported request."));
