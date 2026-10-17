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
            <h1><?= write("Share a secret.") ?><br><span><?= write("Then it disappears.") ?></span></h1>
            <p class="intro-copy"><?= write("Create an encrypted link that can only be opened once. Keep the link private, anyone with it can read your message!") ?></p>
        </section>

        <form id="message-form">
            <label class="field-label" for="inputtextbot"><?= write("Your message") ?></label>
            <textarea id="inputtextbot" name="data" placeholder="<?= write("Write or paste your secret here...") ?>" required maxlength="10000" aria-describedby="message-help"></textarea>
            <div class="field-meta">
                <span id="message-help"><?= write("Messages are limited to 10 KB.") ?></span>
                <output id="byte-count" for="inputtextbot" aria-live="polite">0 / 10,000 <?= write("bytes") ?></output>
            </div>
            <button class="button button-primary button-full" id="encrypt-button" type="submit"><?= write("Encrypt message") ?> <span aria-hidden="true">→</span></button>
        </form>

        <section id="form_submission" class="result-panel" hidden aria-live="polite">
            <p class="result-title"><?= write("Your one-time link is ready") ?></p>
            <p class="result-copy"><?= write("Anyone with this link can view the message once. After it is opened, the message is deleted.") ?></p>
            <label class="field-label" for="submissiontextbox"><?= write("Share link") ?></label>
            <input id="submissiontextbox" type="text" readonly>
            <div class="button-row">
                <button class="button button-primary" id="copy-link" type="button"><?= write("Copy link") ?></button>
                <a class="button button-secondary" href="./"><?= write("Create another") ?></a>
            </div>
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
