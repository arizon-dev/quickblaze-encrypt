<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="<?=getInstallationPath()?>/Public/assets/img/favicon.png">
    <meta name="description" content="<?= translate("An extremely simple, one-time view encrypted message system. Send anybody passwords, or secret messages on a one-time view basis.") ?>">
    <title>QuickBlaze</title>

    <!-- Site CSS -->
    <link href="<?=getInstallationPath()?>/Public/assets/css/bootstrap.css" rel="stylesheet">
    <link href="<?=getInstallationPath()?>/Public/assets/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v6.0.0-beta1/css/all.css">
</head>

<body class="text-center">

    <main class="form-submit">
        <form onsubmit="return false;">
            <h1>QuickBlaze</h1>
            <h5 class="text-muted">One time view encrypted message sharing system</h5>
            <br><br>

            <!-- Main Form Content -->
            <div id="form_input">
                <textarea type="text" class="form-control" id="inputtextbot" placeholder="<?= translate("Enter your secret message!") ?>" <?= ifTextBoxDisabled(); ?> requireds></textarea>
                <br>
                <button type="button" class="btn btn-primary submit-button darkmode-ignore" onclick="updateFormDisplay();">
                    <?= translate("Generate Link"); ?>
                </button>
            </div>

            <div id="form_submission" style="display:none">
                <textarea type="text" class="form-control" id="submissiontextbox" disabled></textarea>
                <br>
                <p class="text-muted">
                    <?= translate("Share this link anywhere on the internet. The message will be automatically destroyed once viewed.") ?>
                </p>
                <button type="button" class="btn btn-primary submit-button darkmode-ignore" onclick="copyToClipboard('#submissiontextbox')">
                    <?= translate("Copy Link") ?>
                </button>
                <a class="btn btn-secondary submit-button darkmode-ignore" href="./">
                    <?= translate("Create New") ?>
                </a>
            </div>

            <p class="mt-5 mb-3 text-muted">
                <a href="https://github.com/axtonprice/quickblaze-encrypt" class="text-muted no-decoration">GitHub</a> •
                <a href="https://discord.gg/dP3MuBATGc" class="text-muted no-decoration">Discord</a> •
                <a href="https://github.com/axtonprice/quickblaze-encrypt/releases" class="text-muted no-decoration"><?= determineSystemVersion(); ?></a>
            </p>

        </form>
    </main>

    <!-- Snackbar Notification -->
    <div id="snackbar"><?= translate("✅ URL has been copied to clipboard!") ?></div>

    <!-- Dark Mode Widget -->
    <script src="<?=getInstallationPath()?>/Public/assets/js/globalFunctions.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/darkmode-js@1.5.7/lib/darkmode-js.min.js"></script>
    <!-- Copy Button -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="<?=getInstallationPath()?>/Public/assets/js/buttonCopyURL.js"></script>
    <!-- Form Scripts -->
    <script src="<?=getInstallationPath()?>/Public/assets/js/formContentUpdate.js"></script>

</body>

</html>