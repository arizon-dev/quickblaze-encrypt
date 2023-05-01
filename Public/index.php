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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>

    <div class="global-container">
        <div class="main-form">
            <form onsubmit="return false;">
                <div class="page-title-container">
                    <a href="<?= getInstallationPath(); ?>">
                        <img class="form-icon fa-fade" id="form-icon" draggable="false" alt="Quickblaze Encrypt" aria-label="Quickblaze Encrypt" title="Quickblaze Encrypt" src="<?= getInstallationPath(); ?>/Public/assets/img/favicon-100x100.png">
                    </a>
                    <h2>Quickblaze Encrypt</h2>
                </div>
                <h5 class="text-muted"><?= translate("One-time view encrypted message sharing system") ?></h5>

                <!-- Snackbar -->
                <div class="alert snackbar-container" id="snackbar-container">
                    <div id="snackbar"></div>
                </div>

                <!-- Main Form Content -->
                <div id="form_input" class="form-area">
                    <label for="input_text_box"><?= translate("Secret Message") ?></label>
                    <textarea type="text" class="form-control form-input-item size-max" id="input_text_box" placeholder="<?= translate("Enter your secret message!") ?>" required></textarea>
                    <label for="input_password"><?= translate("Decryption Password") ?></label>
                    <input class="form-control form-input-item size-single" id="input_password" placeholder="<?= translate("Enter decryption password") ?>" required></input>
                    <button type="button" class="btn btn-primary submit-button" onclick="formValidateDisplay();">
                        <?= translate("Encrypt Message"); ?>
                    </button>
                </div>

                <div id="form_submission" class="form-area">
                    <label for="submission_text_box"><?= translate("Share Link") ?></label>
                    <textarea type="text" class="form-control form-input-item size-max" id="submission_text_box" disabled></textarea>
                    <label for="submission_password"><?= translate("Decryption Password") ?></label>
                    <input type="text" class="form-control form-input-item size-single" id="submission_password" disabled></input>
                    <br>
                    <p class="text-muted">
                        <?= translate("Share this link and decryption password anywhere on the internet. The message will be automatically destroyed once viewed.") ?>
                    </p>
                    <div class="buttons-inline">
                        <button type="button" class="btn btn-primary submit-button" onclick="copyToClipboard('submission_text_box', 'snackbar_link')">
                            <?= translate("Copy Link") ?>
                        </button>
                        <button type="button" class="btn btn-primary submit-button" onclick="copyToClipboard('submission_password', 'snackbar_password')">
                            <?= translate("Copy Password") ?>
                        </button>
                    </div>
                    <a class="btn btn-secondary submit-button" href="./">
                        <?= translate("Create New") ?>
                    </a>
                </div>

                <p class="mt-5 mb-3 text-muted">
                    <a href="https://github.com/arizon-dev/quickblaze-encrypt" class="text-muted no-decoration">GitHub</a> •
                    <a href="https://discord.gg/dP3MuBATGc" class="text-muted no-decoration">Discord</a> •
                    <a href="https://github.com/arizon-dev/quickblaze-encrypt/releases" class="text-muted no-decoration">
                        <?= determineSystemVersion(); ?>
                    </a>
                </p>

            </form>
        </div>
    </div>

    <!-- Snackbar Notifications -->
    <div class="snackbar-messages">
        <div id="snackbar_link">
            <span class="snackbar-text" id="snackbar-text">
                ✅ <?= translate("Link has been copied to clipboard!") ?>
            </span>
        </div>
        <div id="snackbar_empty_fields">
            <span class="snackbar-text" id="snackbar-text">
                ❌ <?= translate("Error! One or more fields are empty!") ?>
            </span>
        </div>
        <div id="snackbar_error">
            <span class="snackbar-text" id="snackbar-text">
                ❌ <?= translate("Error! An error occurred processing your message!") ?>
            </span>
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