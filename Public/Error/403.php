<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="<?= getInstallationPath() ?>/Public/assets/img/favicon.png">
    <meta name="description" content="<?= translate("An extremely simple, one-time view encryption message system. Send anybody passwords, or secret messages on a one-time view basis.") ?>">
    <title>QuickBlaze</title>

    <!-- Custom styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</head>

<style>
    @import url("https://fonts.googleapis.com/css?family=Lato");

    * {
        position: relative;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: "Lato", sans-serif;
    }

    body {
        height: 100vh;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    h1 {
        margin: 40px 0 20px;
    }

    .lock {
        border-radius: 5px;
        width: 55px;
        height: 45px;
        background-color: #333;
        animation: dip 1s;
        animation-delay: 1.5s;
    }

    .lock::before,
    .lock::after {
        content: "";
        position: absolute;
        border-left: 5px solid #333;
        height: 20px;
        width: 15px;
        left: calc(50% - 12.5px);
    }

    .lock::before {
        top: -30px;
        border: 5px solid #333;
        border-bottom-color: transparent;
        border-radius: 15px 15px 0 0;
        height: 30px;
        animation: lock 2s, spin 2s;
    }

    .lock::after {
        top: -10px;
        border-right: 5px solid transparent;
        animation: spin 2s;
    }

    @keyframes lock {
        0% {
            top: -45px;
        }

        65% {
            top: -45px;
        }

        100% {
            top: -30px;
        }
    }

    @keyframes spin {
        0% {
            transform: scaleX(-1);
            left: calc(50% - 30px);
        }

        65% {
            transform: scaleX(1);
            left: calc(50% - 12.5px);
        }
    }

    @keyframes dip {
        0% {
            transform: translateY(0px);
        }

        50% {
            transform: translateY(10px);
        }

        100% {
            transform: translateY(0px);
        }
    }
</style>


<body>
    <div class="lock"></div>
    <div class="message">
        <h1><?= translate("Access to this page is restricted") ?></h1>
        <p style="text-align: center"><?= translate("Please check with the site admin if you believe this is a mistake.") ?></p>
    </div>
</body>

</html>