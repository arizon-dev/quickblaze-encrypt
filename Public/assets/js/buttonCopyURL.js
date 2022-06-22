function copyToClipboard(element) {
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val($(element).text()).select();
    document.execCommand("copy");
    log(`Copied text to clipboard`);
    $temp.remove();
    snackBar(); // show snackbar notification
}

function snackBar() {
    var x = document.getElementById("snackbar");
    x.className = "show";
    var time = 3000;
    log(`Displaying snackbar for ${time}ms`);
    setTimeout(function () {
        x.className = x.className.replace("show", "");
    }, time);
}