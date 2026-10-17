const messageForm = document.getElementById("message-form");
const messageInput = document.getElementById("inputtextbot");
const byteCount = document.getElementById("byte-count");
const statusMessage = document.getElementById("status-message");
const themeToggle = document.getElementById("theme-toggle");

function setStatus(message, isError = false) {
  if (!statusMessage) return;
  statusMessage.textContent = message;
  statusMessage.classList.toggle("status-error", isError);
}

function updateByteCount() {
  if (!messageInput || !byteCount) return;
  const bytes = new TextEncoder().encode(messageInput.value).length;
  byteCount.value = `${bytes.toLocaleString()} / 10,000 bytes`;
  byteCount.textContent = byteCount.value;
  byteCount.classList.toggle("limit-reached", bytes > 10000);
}

function setBusy(button, busy, label) {
  button.disabled = busy;
  button.textContent = busy ? label : button.dataset.label;
}

if (messageForm) {
  const encryptButton = document.getElementById("encrypt-button");
  encryptButton.dataset.label = encryptButton.textContent.trim();
  messageInput.addEventListener("input", updateByteCount);
  updateByteCount();

  messageForm.addEventListener("submit", async (event) => {
    event.preventDefault();
    const message = messageInput.value;
    const bytes = new TextEncoder().encode(message).length;
    if (!message.trim()) {
      setStatus("Enter a message before encrypting it.", true);
      messageInput.focus();
      return;
    }
    if (bytes > 10000) {
      setStatus("Messages must be 10 KB or less.", true);
      return;
    }

    setBusy(encryptButton, true, "Encrypting...");
    setStatus("");
    try {
      const body = new URLSearchParams({ data: message });
      const response = await fetch(
        new URL("../DataIngest?action=submit", window.location.href),
        {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded;charset=UTF-8",
          },
          body,
        },
      );
      const result = await response.json();
      if (!response.ok || typeof result.response !== "string") {
        throw new Error(
          result.error ||
            "The message could not be encrypted. Please try again.",
        );
      }

      const link = new URL("view", window.location.href);
      link.searchParams.set("key", result.response);
      document.getElementById("submissiontextbox").value = link.href;
      document.getElementById("form_submission").hidden = false;
      messageForm.hidden = true;
      setStatus("");
    } catch (error) {
      setStatus(
        error.message ||
          "The message could not be encrypted. Please try again.",
        true,
      );
      setBusy(encryptButton, false);
    }
  });
}

const viewButton = document.getElementById("view-message");
if (viewButton) {
  viewButton.dataset.label = viewButton.textContent.trim();
  viewButton.addEventListener("click", async () => {
    const key = new URL(window.location.href).searchParams.get("key");
    if (!key || !/^[a-f0-9]{32}$/i.test(key)) {
      document.getElementById("form_confirmation").hidden = true;
      document.getElementById("form_error").hidden = false;
      return;
    }

    setBusy(viewButton, true, "Opening message...");
    setStatus("");
    try {
      const url = new URL("DataIngest", window.location.href);
      url.search = new URLSearchParams({ action: "decrypt" }).toString();
      const response = await fetch(url, {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded;charset=UTF-8",
        },
        body: new URLSearchParams({ key }),
        cache: "no-store",
        credentials: "same-origin",
      });
      const result = await response.json();
      if (!response.ok || typeof result.response !== "string") {
        document.getElementById("form_confirmation").hidden = true;
        document.getElementById("form_error").hidden = false;
        return;
      }

      document.getElementById("valuetextbox").value = result.response;
      document.getElementById("form_confirmation").hidden = true;
      document.getElementById("form_content").hidden = false;
    } catch (error) {
      setStatus("Could not contact the server. Please try again.", true);
      setBusy(viewButton, false);
    }
  });
}

if (themeToggle) {
  const savedTheme = localStorage.getItem("oneview-theme");
  if (savedTheme === "dark" || savedTheme === "light") {
    document.documentElement.dataset.theme = savedTheme;
  }
  themeToggle.addEventListener("click", () => {
    const currentTheme =
      document.documentElement.dataset.theme ||
      (window.matchMedia("(prefers-color-scheme: dark)").matches
        ? "dark"
        : "light");
    const nextTheme = currentTheme === "dark" ? "light" : "dark";
    document.documentElement.dataset.theme = nextTheme;
    localStorage.setItem("oneview-theme", nextTheme);
  });
}
