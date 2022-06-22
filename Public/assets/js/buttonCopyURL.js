const copyToClipboard = (element) => {
    let $temp = $('<input>');
    $('body').append($temp);
    $temp.val($(element).text()).select();
    document.execCommand('copy');
    log(`Copied text to clipboard`);
    $temp.remove();
    showSnackBar('snackbar'); // show snackbar notification
};

const showSnackBar = (snackbarId) => {
    let x = document.getElementById(snackbarId);
    x.className = 'show';
    log(`Displaying snackbar for ${3000}ms`);
    setTimeout(x.className = x.className.replace('show', ''), time);
};