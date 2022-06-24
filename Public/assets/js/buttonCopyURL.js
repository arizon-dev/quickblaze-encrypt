function copyToClipboard(element) {
    let $temp = $('<input>');
    $('body').append($temp);
    $temp.val($(element).text()).select();
    document.execCommand('copy');
    showSnackBar('snackbar'); // show snackbar notification
    log(`Copied text to clipboard`);
    $temp.remove();
}

function showSnackBar(snackbarId) {
    var element = document.getElementById(`${snackbarId}`);
    element.className = element.className.replace('', 'show');
    log(`Displaying snackbar for ${4000}ms`);
    setTimeout(function () { element.className = element.className.replace("show", ""); }, 4000);
}