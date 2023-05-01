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