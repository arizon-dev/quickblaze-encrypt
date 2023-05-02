document.addEventListener('DOMContentLoaded', function () {
    log(`${moment()}`, `Initialisation/DEBUG`);
    fetch(`dataProcessing?action=isDebugMode`).then(response => response.json()).then(data => {
        if (data.response == "false") {
            console.log(
                `[${moment().format('hh:mm:ss')}] [Initialisation/DEBUG] Debug mode is disabled!`
            );
        }
    });
    log(`Successfully loaded all assets`, `Initialisation/DEBUG`);
}, false);

function log(content, type = null) {
    if (!type) { 
        typeParameter = "Site Debug/INFO"; 
    } else if(type === "warn") {
        typeParameter = "Site Debug/WARN"; 
    } else { 
        typeParameter = type;
    }
        fetch(`dataProcessing?action=isDebugMode`).then(isDebugModeResp => isDebugModeResp.json()).then(isDebugModeResp => {
            fetch(`dataProcessing?action=debugLog&data=${btoa(content)}&type=${btoa(typeParameter)}`).then((debugLogResp) => debugLogResp.json()).then((debugLogResp) => {
                if (isDebugModeResp.response == "true") {
                    if (debugLogResp.response !== 200) {
                        console.warn(
                            `[${moment().format('hh:mm:ss')}] [Site Debug/WARN] Failed to log content to server!`
                        );
                    }
                    if (!type) {
                        console.log(
                            `[${moment().format('hh:mm:ss')}] [Site Debug/INFO] ${content}`
                        );
                    } else if (type == "warn") {
                        console.warn(
                            `[${moment().format('hh:mm:ss')}] [Site Debug/WARN] ${content}`
                        );
                    } else {
                        console.log(
                            `[${moment().format('hh:mm:ss')}] [${type}] ${content}`
                        );
                    }
                }

            }).catch(error => console.warn(`[${moment().format('hh:mm:ss')}] [Site Debug/WARN] ` + error));
        }).catch(error => console.warn(`[${moment().format('hh:mm:ss')}] [Site Debug/WARN] ` + error));
    }