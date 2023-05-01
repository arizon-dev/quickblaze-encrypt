<?php
if (empty($_GET['code'])) header("Location: ./");
$errorCode = $_GET['code']; // Get error page code from URL

class errorGenerator
{
    // Properties
    public $errorCode;

    // Methods
    function generateDetails($errorCode)
    {
        $errorPages = array("DatabaseConfig", "DatabaseCredentials", "ServerConfiguration", "404", "500", "403");
        if (in_array($errorCode, $errorPages) == false) header("Location: ./"); // Error page not recognised
        $errorDetails =
            array(
                "DatabaseConfig" => array(
                    "title" => "Database Error",
                    "subtext" => "You have not configured the database correctly! <br>Please refer to the GitHub repository!"
                ),
                "DatabaseCredentials" => array(
                    "title" => "Database Error",
                    "subtext" => "Failed to connect to the database using the connection credentials you have provided!"
                ),
                "ServerConfiguration" => array(
                    "title" => "Server Error",
                    "subtext" => "The system configuration is not present or has been misconfigured. <br>Please refer to the GitHub repository!"
                ),
                "404" => array(
                    "title" => "Page Not Found",
                    "subtext" => "The page you are looking for could not be found! <br>It may have been deleted or moved elsewhere!"
                ),
                "500" => array(
                    "title" => "Internal Server Error",
                    "subtext" => "An internal server error has occured!"
                ),
                "403" => array(
                    "title" => "Forbidden",
                    "subtext" => "You do not have permission to access this page!"
                )
            );
        return $errorDetails[$errorCode];
    }
}
$pageDetails = new errorGenerator($errorCode);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="<?= getInstallationPath(); ?>/Public/assets/img/favicon-100x100.png">
    <meta name="description" content="<?= translate("An extremely simple, one-time view encrypted message system. Send anybody passwords, or secret messages on a one-time view basis.") ?>">
    <title>Quickblaze Encrypt</title>

    <!-- Site CSS -->
    <link href="<?= getInstallationPath(); ?>/Public/assets/css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v6.0.0-beta1/css/all.css">

    <!-- Site Fonts -->
    <!-- Site Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
    <div class="global-container">
        <div class="main-form">
            <div class="page-title-container">
                <a href="<?= getInstallationPath(); ?>">
                    <img class="form-icon fa-fade" id="form-icon" draggable="false" alt="Quickblaze Encrypt" aria-label="Quickblaze Encrypt" title="Quickblaze Encrypt" src="<?= getInstallationPath(); ?>/Public/assets/img/favicon-100x100.png">
                </a>
                <h2><?= translate($pageDetails->generateDetails($errorCode)['title']); ?></h2>
            </div>
            <p class="error-details-container"><?= translate($pageDetails->generateDetails($errorCode)['subtext']); ?></p>
            <a class="btn btn-primary submit-button" href="./"><?= translate("Return Home") ?></a>
            <p class="mt-5 mb-3 text-muted">
                <a href="https://github.com/arizon-dev/quickblaze-encrypt" class="text-muted no-decoration">GitHub</a> •
                <a href="https://discord.gg/dP3MuBATGc" class="text-muted no-decoration">Discord</a> •
                <a href="https://github.com/arizon-dev/quickblaze-encrypt/releases" class="text-muted no-decoration"><?= determineSystemVersion(); ?></a>
            </p>
        </div>
    </div>

    <!-- Darkmode Widget -->
    <div class="darkmode-widget">
        <button class="darkmode-widget-button" id="darkSwitch">🌙</button>
    </div>

    <!-- Site Javascript -->
    <script src="<?= getInstallationPath(); ?>/Public/assets/js/globalFunctions.js"></script>
    <script src="<?= getInstallationPath(); ?>/Public/assets/js/buttonSnackbar.js"></script>
    <script src="<?= getInstallationPath(); ?>/Public/assets/js/formContentUpdate.js"></script>
    <script src="<?= getInstallationPath(); ?>/Public/assets/js/darkModeWidget.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://momentjs.com/downloads/moment.js"></script>
</body>

</html>