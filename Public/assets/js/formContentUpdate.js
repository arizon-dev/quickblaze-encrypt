function updateFormDisplay() {
    const messageData = document.getElementById('input_text_box').value; // Assign variable to the current value of the textbox element
    const password_attempt = document.getElementById('input_password').value; // Assign variable to the current value of the textbox element
    $('#form_input').fadeOut('fast'); // fade out previous content
    log(`No longer showing 'form_input' element`);
    fetch(`dataProcessing?action=submit&data=${messageData}&password=${password_attempt}`)
        .then((data) => data.json())
        .then((data) => {
            log(data)
            // Element Updates
            document.getElementById('submission_text_box').value = `${window.location}view?key=${data.response}`; // Set text box to view message URL
            document.getElementById('submission_password').value = document.getElementById('input_password').value; // Set text box to view message URL
            // Debug Logging
            log(`Server responded with '${data.response}'`);
            log(`Updated 'submission_text_box.value'`);
            log(`Server responded with '${data.response}'`);
            log(`Updated 'submission_password.value'`);
        }).catch(error => log(error, 'warn'));
    setTimeout(() => {
        $('#form_submission').fadeIn('fast'); // fade in new content
        log(`Now showing 'form_submission' element in page content.`);
    }, 200);
}

function formValidateDisplay() {
    if (window.location.pathname === "/view") {
        if (document.getElementById('input_password_attempt').value === "" || document.getElementById('input_password_attempt').value === " ") {
            showSnackBar('snackbar_empty_fields', 'error');
            log("Form validation failed; Password field is empty.");
        } else {
            decryptFormSubmit(); // Render new form
            log("Form validation passed; Password field is filled.");
        }
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

function decryptFormSubmit() {
    let key = new URL(window.location).searchParams.get('key'); // fetch key from url variable
    log(`Fetched key variable from url (${key})`);
    fetch(`dataProcessing?action=decrypt&key=${key}`).then(response => response.json()).then(data => {

        if (data.response === null) {
            $('#form_confirmation').fadeOut('fast'); // fade out previous content 
            log(`No longer showing 'form_confirmation' element.`);
            setTimeout(() => {
                $('#form_content').fadeIn('fast'); // fade in new content
                log(`Now showing 'form_content' element`);
            }, 200);
            $('#form_content').fadeOut('fast'); // fade in new content
            log(`No longer showing 'form_content' element.`);
            setTimeout(() => {
                $('#form_error').fadeIn('fast'); // fade in new content
                log(`Now showing 'form_error' element.`);
            }, 200);
            log(`Encryption data response not found!`);
            setTimeout(() => {
                // window.location.replace('./'); // Redirect to home page
            }, 2000);
        } else {
            if (document.getElementById("input_password_attempt").value === data.response) {
                $('#form_confirmation').fadeOut('fast'); // fade out previous content 
                log(`No longer showing 'form_confirmation' element.`);
                setTimeout(() => {
                    $('#form_content').fadeIn('fast'); // fade in new content
                    log(`Now showing 'form_content' element`);
                }, 200);
                document.getElementById('valuetextbox').value = data.response; // Set text box to decrypted message
                log(`Updated 'valuetextbox.value'`);
                document.getElementById('valuetextbox').innerHTML = data.response; // Set text box to decrypted message
                log(`Updated 'valuetextbox.innerHTML'`);
                setTimeout(() => {
                    $('#form_content').fadeIn('fast'); // fade in new content
                    log(`Now showing 'form_content' element`);
                }, 200);
                log(`Server responded with '${data.response}'`);
            } else {
                showSnackBar('snackbar_incorrect_password', 'error');
            }
        };
    });
}