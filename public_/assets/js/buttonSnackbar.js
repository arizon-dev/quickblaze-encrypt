function copyToClipboard(element, snackbarId) {
    var copyText = document.getElementById(element);
    copyText.select();
    copyText.setSelectionRange(0, 99999); // For mobile devices
    navigator.clipboard.writeText(copyText.value);
    showSnackBar(snackbarId, 'success');
    log(`Copied text to clipboard from '${element}'.`);
}

function showSnackBar(snackbarId, messageType, permanent = false) {
    var snackbarContent = document.getElementById(snackbarId).innerHTML;
    var snackbarContainer = document.getElementById('snackbar-container');
    var snackbarArea = document.getElementById('snackbar');
    snackbarContainer.style.visibility = "visible";
    snackbarContainer.classList.add('mt-3'); // set element margin
    snackbarContainer.classList.add(`alert-${messageType}`);
    snackbarArea.innerHTML = snackbarContent; // set snackbar content
    if (permanent) {
        log(`Displaying snackbar '${snackbarId}' permanently.`);
        setTimeout(function () {
            window.location = "./"; // Redirect to home page
        }, 4000);
    } else {
        var showTime = 6000; // set snackbard show time
        log(`Displaying snackbar '${snackbarId}' for ${showTime}ms`);
        setTimeout(function () {
            snackbarContainer.style.visibility = "hidden";
            snackbarContainer.classList.remove('mt-3'); // remove element margin
            snackbarContainer.classList.remove(`alert-${messageType}`);
            snackbarArea.innerHTML = ""; // empty snackbar content
        }, showTime);
    }
}