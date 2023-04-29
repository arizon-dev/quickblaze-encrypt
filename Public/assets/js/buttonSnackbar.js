function copyToClipboard(element, snackbarId) {
    var copyText = document.getElementById(element);
    copyText.select();
    copyText.setSelectionRange(0, 99999); // For mobile devices
    navigator.clipboard.writeText(copyText.value);
    showSnackBar(snackbarId);
    log(`Copied text to clipboard from '${element}'.`);
}

function showSnackBar(snackbarId) {
    var element = document.getElementById(snackbarId);
    element.className = element.className.replace('', 'show');
    log(`Displaying snackbar '${snackbarId}' for ${3000}ms`);
    setTimeout(function () { element.className = element.className.replace("show", ""); }, 3000);
}