<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="./Public/assets/img/favicon.png">
    <meta name="description" content="An extremely simple, one-time view encryption message system. Send anybody passwords, or secret messages on a one-time view basis.">
    <title>QuickBlaze</title>

    <!-- Bootstrap core CSS -->
    <link href="./Public/assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles -->
    <link href="./Public/assets/css/style.css" rel="stylesheet">
</head>

<body class="text-center">

    <main class="form-submit">
        <form action="processForm" method="post">
            <h1>QuickBlaze</h1>
            <h5 class="text-muted">One time view encrypted message sharing system</h5>
            <br><br>

            <textarea type="text" class="form-control" id="floatingInput" name="data" placeholder="Enter your secret message!" <?= ifTextBoxDisabled(); ?> required><?= getSubmittedKey() ?></textarea>

            <?= determineSubmissionFooter() ?>

            <p class="mt-5 mb-3 text-muted">
                <a href="https://github.com/axtonprice/quickblaze-encrypt" class="text-muted no-decoration">GitHub</a> •
                <a href="https://discord.gg/dP3MuBATGc" class="text-muted no-decoration">Discord</a> •
                <a href="https://github.com/axtonprice/quickblaze-encrypt/releases" class="text-muted no-decoration"><?= determineSystemVersion(); ?></a>
            </p>

        </form>
    </main>

    <!-- Dark Mode Widget -->
    <script src="https://cdn.jsdelivr.net/npm/darkmode-js@1.5.7/lib/darkmode-js.min.js"></script>
    <script>
        function addDarkmodeWidget() {
            const options = {
                time: '0.3s', // default: '0.3s'
                saveInCookies: true, // default: true,
                label: '🌓', // default: ''
            }

            const darkmode = new Darkmode(options);
            darkmode.showWidget();
        }
        window.addEventListener('load', addDarkmodeWidget);
    </script>
        <!-- Copy Button -->
        <script>
        document.querySelector("button").onclick = function() {
            document.querySelector("textarea").select();
            document.execCommand('copy');
        }
    </script>

</body>

</html>