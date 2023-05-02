/*!
 * Dark Mode Switch v1.0.1 (https://github.com/coliff/dark-mode-switch)
 * Copyright 2021 C.Oliff
 * Licensed under MIT (https://github.com/coliff/dark-mode-switch/blob/main/LICENSE)
*/
initTheme();
darkSwitch.addEventListener("click", function () {
  resetTheme();
});

setInterval(function () {
  // resetTheme();
}, 0.0000001);

/**
 * Summary: function that adds or removes the attribute 'data-theme' depending if
 * the switch is 'on' or 'off'.
 *
 * Description: initTheme is a function that uses localStorage from JavaScript DOM,
 * to store the value of the HTML switch. If the switch was already switched to
 * 'on' it will set an HTML attribute to the body named: 'data-theme' to a 'dark'
 * value. If it is the first time opening the page, or if the switch was off the
 * 'data-theme' attribute will not be set.
 * @return {void}
 */
function initTheme() {
  var darkThemeSelected = localStorage.getItem("darkSwitch") !== null && localStorage.getItem("darkSwitch") === "dark"; // get darkTheme status from localStorage
  darkThemeSelected ? document.body.setAttribute("data-theme", "dark") : document.body.removeAttribute("data-theme");
  if (localStorage.getItem("darkSwitch") !== null && localStorage.getItem("darkSwitch") === "dark") {
    document.getElementById("darkSwitch").innerText = '💡';
  } else {
    document.getElementById("darkSwitch").innerText = '🌙';
  }
}
/**
 * Summary: resetTheme checks if the switch is 'on' or 'off' and if it is toggled
 * on it will set the HTML attribute 'data-theme' to dark so the dark-theme CSS is
 * applied.
 * @return {void}
 */
function resetTheme() {
  if (localStorage.getItem("darkSwitch") !== null && localStorage.getItem("darkSwitch") === "dark") {
    document.body.removeAttribute("data-theme");
    localStorage.removeItem("darkSwitch");
    document.getElementById("darkSwitch").innerText = '🌙';
  } else {
    document.body.setAttribute("data-theme", "dark");
    localStorage.setItem("darkSwitch", "dark");
    document.getElementById("darkSwitch").innerText = '💡';
  }
}
