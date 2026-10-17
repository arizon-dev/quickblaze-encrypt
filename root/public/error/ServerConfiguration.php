<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="referrer" content="no-referrer">
    <meta name="description" content="<?= write("An extremely simple, one-time view encrypted message system. Send anybody passwords, or secret messages on a one-time view basis.") ?>">
    <title>OneView - <?= write("Secret Messages") ?></title>
    <link rel="icon" type="image/x-icon" href="/public/assets/img/favicon-64x64.png">
    <link href="/public/assets/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.1/css/all.min.css">
</head>

<body>
    <main class="app-card">
        <header class="topbar">
            <a class="brand" href="./" aria-label="OneView home">
                <img class="brand-icon" draggable="false" alt="" src="/public/assets/img/favicon-128x128.png">
                <span>OneView</span>
            </a>
            <button class="theme-toggle" id="theme-toggle" type="button" aria-label="Switch color theme">◐</button>
        </header>

        <section class="intro">
            <h1><i class="fa-solid fa-triangle-exclamation"></i> <span><?= write("Configuration Error") ?></span></h1>
            <p class="intro-copy"><?= write("An error occurred while configuring the application.") ?></p>
            <br>
            <a class="button button-secondary" href="../"><?= write("Return to home") ?></a>
        </section>

        <footer class="site-footer">
            <nav aria-label="About OneView">
                <a href="https://github.com/arizon-dev/oneview">GitHub</a>
                <span aria-hidden="true">·</span>
                <a href="https://github.com/arizon-dev/oneview/releases"><?= determineSystemVersion(); ?></a>
            </nav>
        </footer>
    </main>

    <script src="/public/assets/js/buttonCopyURL.js"></script>
    <script src="/public/assets/js/formHandler.js"></script>
</body>

</html>
