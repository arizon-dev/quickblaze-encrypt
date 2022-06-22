<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="<?= getInstallationPath() ?>/Public/assets/img/favicon.png">
    <meta name="description" content="<?= translate("An extremely simple, one-time view encryption message system. Send anybody passwords, or secret messages on a one-time view basis.") ?>">
    <title>QuickBlaze</title>

    <!-- Site CSS -->
    <link href="<?= getInstallationPath() ?>/Public/assets/css/error.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v6.0.0-beta1/css/all.css">
</head>

<body>
    <div class="lock"></div>
    <div class="message">
        <h1><?= translate("Access to this page is restricted") ?></h1>
        <p style="text-align: center"><?= translate("Please check with the site admin if you believe this is a mistake.") ?></p>
    </div>

    <!-- Site Javascript -->
    <script src="<?= getInstallationPath() ?>/Public/assets/js/globalFunctions.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/darkmode-js@1.5.7/lib/darkmode-js.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

</body>

</html>