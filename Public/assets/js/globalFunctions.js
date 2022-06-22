function addDarkmodeWidget() {
    const options = {
        time: '0.0s', // default: '0.3s'
        saveInCookies: true, // default: true,
        label: '🌛', // default: ''
    }

    const darkmode = new Darkmode(options);
    darkmode.showWidget();
}
window.addEventListener('load', addDarkmodeWidget);