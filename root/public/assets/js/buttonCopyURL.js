async function copyToClipboard(elementId) {
    const input = document.getElementById(elementId);
    const status = document.getElementById("status-message");
    try {
        await navigator.clipboard.writeText(input.value);
        status.textContent = "Copied to clipboard.";
        status.classList.remove("status-error");
    } catch (error) {
        input.focus();
        input.select();
        const copied = document.execCommand("copy");
        status.textContent = copied
            ? "Copied to clipboard."
            : "Copy failed. Select and copy the text manually.";
        status.classList.toggle("status-error", !copied);
    }
}

const copyLinkButton = document.getElementById("copy-link");
if (copyLinkButton) {
    copyLinkButton.addEventListener("click", () => copyToClipboard("submissiontextbox"));
}

const copyMessageButton = document.getElementById("copy-message");
if (copyMessageButton) {
    copyMessageButton.addEventListener("click", () => copyToClipboard("valuetextbox"));
}
