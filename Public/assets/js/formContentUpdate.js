function updateFormDisplay() {
    const formvalue = document.getElementById("inputtextbot").value; // Assign formvalue variable to the current value of the textbox
    $('#form_input').fadeOut('fast'); // fade out previous content
    fetch(`processForm?data=${formvalue}`).then(response => response.json()).then(data => {
        document.getElementById("submissiontextbox").value = `${window.location}view?key=${data.response}`; // Set text box to view message URL
        document.getElementById("submissiontextbox").innerHTML = `${window.location}view?key=${data.response}`; // Set text box to view message URL
    });
    setTimeout(function () {
        $('#form_submission').fadeIn('fast'); // fade in new content
    }, 200);
}

function updateViewDisplay() {
    $('#form_confirmation').fadeOut('fast'); // fade out previous content
    var url = new URL(window.location); // Get page URL, including variables
    var key = url.searchParams.get("key") // Get key variable;; replacing PHP usage
    fetch(`processForm?action=decrypt&key=${key}`).then(response => response.json()).then(data => {
        if (data.response == "") {
            const errorMessage = `This message has already been viewed and is no longer available!`;
            document.getElementById("valuetextbox").value = errorMessage; // Set text box to error message
            document.getElementById("valuetextbox").innerHTML = errorMessage; // Set text box to error message
            window.location.replace("./"); // Redirect to home page
        }
        console.log(data.response);
        document.getElementById("valuetextbox").value = data.response; // Set text box to decrypted message
        document.getElementById("valuetextbox").innerHTML = data.response; // Set text box to decrypted message
    });
    setTimeout(function () {
        $('#form_content').fadeIn('fast'); // fade in new content
    }, 200);
}