function updateFormDisplay() {
    const formvalue = document.getElementById('inputtextbot').value; // Assign variable to the current value of the textbox
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
        log(`Server responsed with '${data.response}'`);
        document.getElementById('submissiontextbox').value = `${window.location}view?key=${data.response}`; // Set text box to view message URL
        log(`Updated 'submissiontextbox.value'`);
        document.getElementById('submissiontextbox').innerHTML = `${window.location}view?key=${data.response}`; // Set text box to view message URL
        log(`Updated 'submissiontextbox.innerHTML'`);
    });
    setTimeout(function () {
        $('#form_submission').fadeIn('fast'); log(`Now showing 'form_submission' element`); // fade in new content
    }, 200);
}

function updateViewDisplay() {
    $('#form_confirmation').fadeOut('fast'); // fade out previous content
    log(`No longer showing 'form_confirmation' element`);

    let key = new URL(window.location).searchParams.get('key'); // Get key variable from URL; replacing PHP usage
    log(`Got key variable from url -> ${key}`);

function updateViewDisplay() {
    $('#form_confirmation').fadeOut('fast'); log(`No longer showing 'form_confirmation' element`); // fade out previous content
    var key = new URL(window.location).searchParams.get("key"); log(`Got key variable from url -> ${key}`); // Get key variable from URL; replacing PHP usage
    fetch(`dataProcessing?action=decrypt&key=${key}`).then(response => response.json()).then(data => {
        if (data.response == "") {
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
            document.getElementById("valuetextbox").value = data.response; log(`Updated 'valuetextbox.value'`); // Set text box to decrypted message
            document.getElementById("valuetextbox").innerHTML = data.response; log(`Updated 'valuetextbox.innerHTML'`); // Set text box to decrypted message
            setTimeout(function () {
                $('#form_content').fadeIn('fast'); log(`Now showing 'form_content' element`); // fade in new content
            }, 200);
        }
    });
}