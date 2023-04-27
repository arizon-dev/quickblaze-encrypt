function updateFormDisplay() {
    const formvalue = document.getElementById('input_text_box').value; // Assign variable to the current value of the textbox element
    $('#form_input').fadeOut('fast'); // fade out previous content
    log(`No longer showing 'form_input' element`);
    function fetchData() {
        return fetch(`dataProcessing?action=submit&data=${formvalue}`)
            .then((response) => response.json())
            .then((responseData) => {
                return responseData;
            }).catch(error => log(error, 'warn'));
    }
    fetchData().then(data => {
        // Element Updates
        document.getElementById('submission_text_box').value = `${window.location}view?key=${data.response}`; // Set text box to view message URL
        document.getElementById('submission_text_box').innerHTML = `${window.location}view?key=${data.response}`; // Set text box to view message URL
        document.getElementById('submission_password').value = `${window.location}view?key=${data.response}`; // Set text box to view message URL
        document.getElementById('submission_password').innerHTML = `${window.location}view?key=${data.response}`; // Set text box to view message URL
        // Debug Logging
        log(`Server responsed with '${data.response}'`);
        log(`Updated 'submission_text_box.value'`);
        log(`Updated 'submission_text_box.innerHTML'`);
        log(`Server responsed with '${data.response}'`);
        log(`Updated 'submission_password.value'`);
        log(`Updated 'submission_password.innerHTML'`);
    });
    setTimeout(() => {
        $('#form_submission').fadeIn('fast'); // fade in new content
        log(`Now showing 'form_submission' element`);
    }, 200);
}

function updateViewDisplay() {
    $('#form_confirmation').fadeOut('fast'); // fade out previous content
    log(`No longer showing 'form_confirmation' element`);

    let key = new URL(window.location).searchParams.get('key'); // Get key variable from URL; replacing PHP usage
    log(`Got key variable from url -> ${key}`);

    fetch(`dataProcessing?action=decrypt&key=${key}`).then(response => response.json()).then(data => {
        if (!data.response) {
            showSnackBar('snackbarError');
            $('#form_error').fadeIn('fast'); // fade in new content
            log(`Now showing 'form_error' element`);
            log(`Encryption not found; redirecting in 2s`);
            setTimeout(() => {
                window.location.replace('./'); // Redirect to home page
            }, 2000);
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