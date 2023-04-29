function updateFormDisplay() {
    const messageData = document.getElementById('input_text_box').value; // Assign variable to the current value of the textbox element
    const password_attempt = document.getElementById('input_password').value; // Assign variable to the current value of the textbox element
    $('#form_input').fadeOut('fast'); // fade out previous content
    log(`No longer showing 'form_input' element`);
    function fetchData() {
        return fetch(`dataProcessing?action=submit&data=${messageData}&password=${password_attempt}`)
            .then((response) => response.json())
            .then((responseData) => {
                return responseData;
            }).catch(error => log(error, 'warn'));
    }
    fetchData().then(data => {
        // Element Updates
        document.getElementById('submission_text_box').value = `${window.location}view?key=${data.response}`; // Set text box to view message URL
        document.getElementById('submission_password').value = document.getElementById('input_password').value; // Set text box to view message URL
        // Debug Logging
        log(`Server responded with '${data.response}'`);
        log(`Updated 'submission_text_box.value'`);
        log(`Server responded with '${data.response}'`);
        log(`Updated 'submission_password.value'`);
    });
    setTimeout(() => {
        $('#form_submission').fadeIn('fast'); // fade in new content
        log(`Now showing 'form_submission' element`);
    }, 200);
}

function formValidateDisplay() {
    if (window.location.pathname === "/view") {
        // 
    } else {
        if (document.getElementById('input_text_box').value === "" || document.getElementById('input_password').value === "") {
            showSnackBar('snackbar_empty_fields', 'error');
            log("Form validation failed; One or more fields are empty.");
        } else {
            updateFormDisplay(); // Render new form
            log("Form validation passed; All fields are filled.");
        }
    }
}

function updateFormDisplay() {
    $('#form_confirmation').fadeOut('fast'); // fade out previous content
    log(`No longer showing 'form_confirmation' element`);

    let key = new URL(window.location).searchParams.get('key'); // fetch key from url variable
    log(`Fetched key variable from url (${key})`);

    fetch(`dataProcessing?action=decrypt&key=${key}`).then(response => response.json()).then(data => {
        if (!data.response) {
            showSnackBar('snackbar_error', 'error');
            $('#form_error').fadeIn('fast'); // fade in new content
            log(`Now showing 'form_error' element`);
            log(`Encryption data response not found!`);
            if (window.location.pathname === "/view") {
                setTimeout(() => {
                    window.location.replace('./'); // Redirect to home page
                }, 2000);
            }
        } else {
            document.getElementById('valuetextbox').value = data.response; // Set text box to decrypted message
            log(`Updated 'valuetextbox.value'`);
            document.getElementById('valuetextbox').innerHTML = data.response; // Set text box to decrypted message
            log(`Updated 'valuetextbox.innerHTML'`);
            setTimeout(() => {
                $('#form_content').fadeIn('fast'); // fade in new content
                log(`Now showing 'form_content' element`);
            }, 200);
            log(`Server responded with '${data.response}'`);
        };
    });
}