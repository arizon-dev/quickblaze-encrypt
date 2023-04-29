// Onload Functions
document.addEventListener('DOMContentLoaded', function () {
    fetch(`dataProcessing?action=isDebugMode`).then(response => response.json()).then(data => {
        if (data.response == "false") {
            console.log(
                `[${moment().format('hh:mm:ss')}] [Initialisation/DEBUG] Debug mode is disabled!`
            );
        }
    });
    log(`${moment()}`, `Initialisation/DEBUG`);
    log(`Successfully loaded all assets`, `Initialisation/DEBUG`);
}, false);
document.addEventListener('DOMContentLoaded', function () {
    addDarkmodeWidget();
    log(`Initialized darkmode widget`, `Initialisation/DEBUG`);
})
function checkEncryptionStatus() {
    let key = new URL(window.location).searchParams.get('key'); // fetch key from url variable
    fetch(`dataProcessing?action=doesMessageExist&key=${key}`).then(response => response.json()).then(data => {
        if (data.response === false) {
            $('#form_confirmation').fadeOut('fast'); // fade out previous content 
            log(`No longer showing 'form_confirmation' element.`);
            showSnackBar('snackbar_message_nonexist', 'danger', true);
            log(`Updated 'valuetextbox.value'.`);
            log(`Encryption data response not found!`);
            // Change page contents
            setTimeout(() => {
                $('#form_error').fadeIn('fast'); // fade in new content
                log(`Now showing 'form_error' element.`);
            }, 200);
            setTimeout(() => {
                // window.location.replace('./'); // Redirect to home page
            }, 4000);
        }
    });
}

// Functions
function addDarkmodeWidget() {
    const options = {
        time: '0.0s', // default: '0.3s'
        saveInCookies: true, // default: true,
        label: '🌛', // default: ''
    }
    const darkmode = new Darkmode(options);
    darkmode.showWidget();
}
function log(content, type = null) {
    fetch(`dataProcessing?action=isDebugMode`).then(response => response.json()).then(data => {
        if (data.response == "true") {
            if (!type) {
                console.log(
                    `[${moment().format('hh:mm:ss')}] [Site Debug/INFO] ${content}`
                );
            } else if (type == "warn") {
                console.warn(
                    `[${moment().format('hh:mm:ss')}] ${content}`
                );
            } else {
                console.log(
                    `[${moment().format('hh:mm:ss')}] [${type}] ${content}`
                );
            }
        }
    });
}
