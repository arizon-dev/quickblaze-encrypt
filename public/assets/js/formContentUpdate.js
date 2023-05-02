function updateFormDisplay() {
    const messageData = document.getElementById('input_text_box').value; // Assign variable to the current value of the textbox element
    const password_attempt = document.getElementById('input_password').value; // Assign variable to the current value of the textbox element
    $('#form_input').fadeOut('fast'); // fade out previous content
    log(`No longer showing 'form_input' element.`);
    fetch(`dataProcessing?action=submit&data=${messageData}&password=${password_attempt}`)
        .then((data) => data.json())
        .then((data) => {
            // Element Updates
            document.getElementById('submission_text_box').value = `${window.location}view?key=${data.response}`; // Set text box to view message URL
            document.getElementById('submission_password').value = document.getElementById('input_password').value; // Set text box to view message URL
            // Debug Logging
            log(`[ref/action:submit] Server responded with '${data.response}'.`);
            log(`Updated 'submission_text_box.value'.`);
            log(`Updated 'submission_password.value'.`);
        }).catch(error => log(error, 'warn'));
    setTimeout(() => {
        $('#form_submission').fadeIn('fast'); // fade in new content
        log(`Now showing 'form_submission' element in page content.`);
    }, 200);
}

function formValidateDisplay() {
    if (window.location.pathname === "/view") {
        if (document.getElementById('input_password_attempt').value === "" || document.getElementById('input_password_attempt').value === " ") {
            showSnackBar('snackbar_empty_fields', 'danger');
            log("Form validation failed; Required field is empty.");
        } else {
            decryptFormSubmit(); // Render new form
            log("Form validation passed; Required field is not empty.");
        }
    } else {
        if (document.getElementById('input_text_box').value === "" || document.getElementById('input_password').value === "") {
            showSnackBar('snackbar_empty_fields', 'danger');
            log("Form validation failed; One or more fields are empty.");
        } else {
            updateFormDisplay(); // Render new form
            log("Form validation passed; All fields are filled.");
        }
    }
}

function decryptFormSubmit() {
    let key = new URL(window.location).searchParams.get('key'); // fetch key from url variable
    log(`Fetched key variable from url '${key}'.`);
    fetch(`dataProcessing?action=validatePassword&password=${document.getElementById("input_password_attempt").value}&key=${key}`).then(response => response.json()).then(validation => {
        log(`[ref/action:validatePassword] Server responded with '${validation.response}'.`);
        if (validation.response === true) {
            fetch(`dataProcessing?action=decrypt&key=${key}&password=${document.getElementById("input_password_attempt").value}`).then(response => response.json()).then(decryption => {
                log(`[ref/action:decrypt] Server responded with '${decryption.response}'.`);
                if (decryption.response === null) {
                    $('#form_confirmation').fadeOut('fast'); // fade out previous content
                    log(`No longer showing 'form_confirmation' element.`);
                    showSnackBar('snackbar_message_nonexist', 'danger', true);
                    log(`Updated 'valuetextbox.value'`);
                    log(`Encryption data response not found!`);
                    // Change page contents
                    setTimeout(() => {
                        $('#form_error').fadeIn('fast'); // fade in new content
                        log(`Now showing 'form_error' element.`);
                    }, 200);
                    // Redirect to home page
                    setTimeout(() => {
                        // window.location.replace('./'); // Redirect to home page
                    }, 2000);
                } else {
                    $('#form_confirmation').fadeOut('fast'); // fade out previous content 
                    log(`No longer showing 'form_confirmation' element.`);
                    document.getElementById('valuetextbox').value = decryption.response; // Set text box to decrypted message
                    log(`Updated 'valuetextbox.value'`);
                    setTimeout(() => {
                        window.location.replace('./'); // Redirect to home page
                    }, 120000); // 2 minutes
                    setTimeout(() => {
                        $('#form_content').fadeIn('fast'); // fade in new content
                        log(`Now showing 'form_content' element`);
                    }, 200);
                };
            });
        } else {
            showSnackBar('snackbar_incorrect_password', 'danger');
        }
    }).catch(error => log(error, 'warn'));
}

function checkEncryptionStatus() {
    let key = new URL(window.location).searchParams.get('key'); // fetch key from url variable
    fetch(`dataProcessing?action=doesMessageExist&key=${key}`).then(response => response.json()).then(data => {
        log(`[ref/action:doesMessageExist] Server responded with '${data.response}'.`)
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
                window.location.replace('./'); // Redirect to home page
            }, 5000);
        } else{ // Message exists
            showSnackBar("snackbar_info", "info", true);
        }
    });
}