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
            <h1><?= write("A private message") ?><br><span><?= write("is waiting for you.") ?></span></h1>
            <p class="intro-copy"><?= write("Opening this message will permanently delete it. Only continue if you are ready to view it.") ?></p>
        </section>

        <section id="form_confirmation">
            <button class="button button-primary button-full" id="view-message" type="button"><?= write("View and destroy message") ?> <span aria-hidden="true">→</span></button>
        </section>

        <section id="form_content" class="result-panel" hidden aria-live="polite">
            <p class="result-title"><?= write("Message revealed and deleted") ?></p>
            <label class="field-label" for="valuetextbox"><?= write("Your message") ?></label>
            <textarea id="valuetextbox" readonly rows="6"></textarea>
            <div class="button-row">
                <button class="button button-primary" id="copy-message" type="button"><?= write("Copy message") ?></button>
                <a class="button button-secondary" href="./"><?= write("Return home") ?></a>
            </div>
        </section>
        <section id="form_error" class="notice notice-error" hidden role="alert">
            <p class="result-title"><?= write("This message is unavailable") ?></p>
            <p><?= write("It may have already been viewed, deleted, or the link may be invalid.") ?></p>
            <a class="button button-secondary" href="./"><?= write("Create a new message") ?></a>
        </section>
        <p id="status-message" class="status-message" role="status" aria-live="polite"></p>

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
