document.addEventListener('DOMContentLoaded', () => {
    log(`${moment()}`, `Initialisation/DEBUG`);
    log(`Successfully loaded all assets`, `Initialisation/DEBUG`);
}, false);

const addDarkmodeWidget = () => {
    let darkmode = new Darkmode(options),
        options = {
            time: '0.0s', // default: '0.3s'
            saveInCookies: true, // default: true,
            label: ':first_quarter_moon_with_face:', // default: ''
        };
    darkmode.showWidget();
};

document.addEventListener('DOMContentLoaded', () => {
    addDarkmodeWidget();
    log(`Initialized darkmode widget`, `Initialisation/DEBUG`);
});

const log = (content, type = null) => {
    if (!type) {
        console.log(`[${moment().format('hh:mm:ss')}] [Site Debug/INFO] ${content}`);
    } else {
        console.log(`[${moment().format('hh:mm:ss')}] [${type}] ${content}`);
    };
};