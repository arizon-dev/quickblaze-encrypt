<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="<?= getInstallationPath() ?>/Public/assets/img/favicon-100x100.png">
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
            <a href="<?= getInstallationPath() ?>">
                <img class="form-icon fa-fade" id="form-icon" draggable="false" alt="QuickBlaze Encrypt" aria-label="QuickBlaze Encrypt" title="QuickBlaze Encrypt" src="<?= getInstallationPath() ?>/Public/assets/img/favicon-100x100.png">
            </a>
            <h1>QuickBlaze</h1>
            <h5 class="text-muted"><?= translate("One time view encrypted message sharing system") ?></h5>

            <!-- Snackbar -->
            <div class="snackbar-container darkmode-ignore" id="snackbar-container">
                <div id="snackbar"></div>
            </div>

            <!-- Main Form Content -->
            <div id="form_confirmation">
                <h6>
                    <?= translate("Decrypt & View Message?") ?>
                </h6>
                <label for="input_password_attempt">Decryption Password</label>
                <input class="form-control form-input-item size-single" type="password" id="input_password_attempt" placeholder="<?= translate("Enter decryption password") ?>" required></input>
                <button class="btn btn-primary submit-button darkmode-ignore" onclick="formValidateDisplay();">
                    <?= translate("Decrypt Message") ?>
                </button>
            </div>

            <div id="form_content" style="display:none">
                <h6>
                    <?= translate("This message has now been destroyed!") ?>
                </h6>
                <textarea disabled type="text" class="form-control" id="valuetextbox" name="data"></textarea>
                <br>
                <button type="button" class="btn btn-primary submit-button darkmode-ignore" onclick="copyToClipboard('#valuetextbox')">
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
                <a href="https://github.com/arizon-dev/quickblaze-encrypt" class="text-muted no-decoration">GitHub</a> •
                <a href="https://discord.gg/dP3MuBATGc" class="text-muted no-decoration">Discord</a> •
                <a href="https://github.com/arizon-dev/quickblaze-encrypt/releases" class="text-muted no-decoration"><?= determineSystemVersion(); ?></a>
            </p>

        </form>
    </main>

    <!-- Snackbar Notifications -->
    <div class="snackbar-messages">
        <div id="snackbar_link">
            <span class="snackbar-text" id="snackbar-text">
                <?= translate("✅ Link has been copied to clipboard!") ?>
            </span>
        </div>
        <div id="snackbar_password">
            <span class="snackbar-text" id="snackbar-text">
                <?= translate("✅ Password has been copied to clipboard!") ?>
            </span>
        </div>
        <div id="snackbar_empty_fields">
            <span class="snackbar-text" id="snackbar-text">
                <?= translate("❌ <b>Error!</b> One or more fields are empty!") ?>
            </span>
        </div>
        <div id="snackbar_incorrect_password">
            <span class="snackbar-text" id="snackbar-text">
                <?= translate("❌ <b>Error!</b> The password you entered is incorrect!") ?>
            </span>
        </div>
        <div id="snackbar_error">
            <span class="snackbar-text" id="snackbar-text">
                <?= translate("❌ <b>Error!</b> An error occurred processing your message!") ?>
            </span>
        </div>
    </div>

    <!-- Site Javascript -->
    <script src="<?= getInstallationPath() ?>/Public/assets/js/globalFunctions.js"></script>
    <script src="<?= getInstallationPath() ?>/Public/assets/js/buttonSnackbar.js"></script>
    <script src="<?= getInstallationPath() ?>/Public/assets/js/formContentUpdate.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/darkmode-js@1.5.7/lib/darkmode-js.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://momentjs.com/downloads/moment.js"></script>

</body>

</html>