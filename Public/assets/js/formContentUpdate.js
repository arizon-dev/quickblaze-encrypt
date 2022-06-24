function updateFormDisplay() {
    const formvalue = document.getElementById("inputtextbot").value; // Assign variable to the current value of the textbox
    $('#form_input').fadeOut('fast'); log(`No longer showing 'form_input' element`); // fade out previous content
    fetch(`dataProcessing?data=${formvalue}`).then(response => response.json()).then(data => {
        log(`Server responsed with '${data.response}'`);
        document.getElementById("submissiontextbox").value = `${window.location}view?key=${data.response}`; log(`Updated 'submissiontextbox.value'`); // Set text box to view message URL
        document.getElementById("submissiontextbox").innerHTML = `${window.location}view?key=${data.response}`; log(`Updated 'submissiontextbox.innerHTML'`); // Set text box to view message URL
    });
    setTimeout(function () {
        $('#form_submission').fadeIn('fast'); log(`Now showing 'form_submission' element`); // fade in new content
    }, 200);
}

function updateViewDisplay() {
    $('#form_confirmation').fadeOut('fast'); log(`No longer showing 'form_confirmation' element`); // fade out previous content
    var key = new URL(window.location).searchParams.get("key"); log(`Got key variable from url -> ${key}`); // Get key variable from URL; replacing PHP usage
    fetch(`dataProcessing?action=decrypt&key=${key}`).then(response => response.json()).then(data => {
        if (data.response == "") {
            showSnackBar('snackbarError');
            $('#form_error').fadeIn('fast'); log(`Now showing 'form_error' element`); // fade in new content
            log(`Encryption not found; redirecting in 2s`)
            setTimeout(function () {
                window.location.replace("./"); // Redirect to home page
            }, 2000);
        } else {
            log(`Server responded with '${data.response}'`);
            document.getElementById("valuetextbox").value = data.response; log(`Updated 'valuetextbox.value'`); // Set text box to decrypted message
            document.getElementById("valuetextbox").innerHTML = data.response; log(`Updated 'valuetextbox.innerHTML'`); // Set text box to decrypted message
            setTimeout(function () {
                $('#form_content').fadeIn('fast'); log(`Now showing 'form_content' element`); // fade in new content
            }, 200);
        }
    });
}