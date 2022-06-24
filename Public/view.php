<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="<?= getInstallationPath() ?>/Public/assets/img/favicon.png">
    <meta name="description" content="<?= translate("An extremely simple, one-time view encrypted message system. Send anybody passwords, or secret messages on a one-time view basis.") ?>">
    <title>QuickBlaze</title>

    <!-- Site CSS -->
    <link href="<?= getInstallationPath() ?>/Public/assets/css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v6.0.0-beta1/css/all.css">
</head>

<body class="text-center">

    <main class="main-form">
        <form onsubmit="return false;">
            <h1>QuickBlaze</h1>
            <h5 class="text-muted"><?= translate("One time view encrypted message sharing system") ?></h5>
            <br><br>

            <!-- Main Form Content -->
            <div id="form_confirmation">
                <h6>
                    <?= translate("Decrypt & View Message?") ?>
                </h6>
                <button class="btn btn-primary submit-button darkmode-ignore" onclick="javascript:updateViewDisplay();">
                    <?= translate("View Message") ?>
                </button>
            </div>

            <div id="form_content" style="display:none">
                <h6>
                    <?= translate("This message has been destroyed!") ?>
                </h6>
                <textarea disabled type="text" class="form-control" id="valuetextbox" name="data"></textarea>
                <br>
                <button type="button" class="btn btn-primary submit-button darkmode-ignore" onclick="javascript:copyToClipboard('#valuetextbox')">
                    <?= translate("Copy Message") ?>
                </button>
                <a class="btn btn-secondary submit-button darkmode-ignore" href="./">
                    <?= translate("Return Home") ?>
                </a>
            </div>

            <div id="form_error" style="display:none">
                <h6>
                    <?= translate("This message has already been destroyed!"); ?>
                </h6>
                <br>
                <p><?= translate("You will now be redirected.."); ?></p>
            </div>

            <p class="mt-5 mb-3 text-muted">
                <a href="https://github.com/axtonprice/quickblaze-encrypt" class="text-muted no-decoration">GitHub</a> •
                <a href="https://discord.gg/dP3MuBATGc" class="text-muted no-decoration">Discord</a> •
                <a href="https://github.com/axtonprice/quickblaze-encrypt/releases" class="text-muted no-decoration"><?= determineSystemVersion(); ?></a>
            </p>

        </form>
    </main>

    <!-- Snackbar Notifications -->
    <div id="snackbar">✅ <?= translate("Message has been copied to clipboard!") ?></div>
    <div id="snackbarError"><?= translate("❌ This message could not be found!") ?></div>

    <!-- Site Javascript -->
    <script src="<?= getInstallationPath() ?>/Public/assets/js/globalFunctions.js"></script>
    <script src="<?= getInstallationPath() ?>/Public/assets/js/buttonCopyURL.js"></script>
    <script src="<?= getInstallationPath() ?>/Public/assets/js/formContentUpdate.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/darkmode-js@1.5.7/lib/darkmode-js.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://momentjs.com/downloads/moment.js"></script>

</body>

</html>