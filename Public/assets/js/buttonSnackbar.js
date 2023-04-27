function copyToClipboard(element, snackbarId) {
    let $temp = $('<input>');
    $('body').append($temp);
    $temp.val($(element).text()).select();
    document.execCommand('copy');
    showSnackBar(snackbarId); // show snackbar notification
    log(`Copied text to clipboard`);
    $temp.remove();
}

function showSnackBar(snackbarId) {
    var element = document.getElementById(`${snackbarId}`);
    element.className = element.className.replace('', 'show');
    log(`Displaying snackbar '${snackbarId}' for ${3000}ms`);
    setTimeout(function () { element.className = element.className.replace("show", ""); }, 3000);
}