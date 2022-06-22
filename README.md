<h1 align="center">QuickBlaze Encryption 👋</h1>

<p align="center">
  <img alt="GitHub release (latest by date)" src="https://img.shields.io/github/v/release/axtonprice-dev/quickblaze-encrypt?label=Version">
  <a href="https://github.com/axtonprice-dev/quickblaze-encrypt/blob/main/LICENSE" target="_blank">
    <img alt="License: MIT" src="https://img.shields.io/badge/License-MIT-yellow.svg" />
  </a>
  <a href="https://www.codacy.com/gh/axtonprice-dev/quickblaze-encrypt/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=axtonprice-dev/quickblaze-encrypt&amp;utm_campaign=Badge_Grade"><img src="https://app.codacy.com/project/badge/Grade/3d4571a7a1a34c548bce562c16ba1221"/></a>
  <a href="https://github.com/axtonprice-dev/quickblaze-encrypt/actions/workflows/codacy.yml"><img src="https://github.com/axtonprice-dev/quickblaze-encrypt/actions/workflows/codacy.yml/badge.svg"/></a>
  <a href="https://axtonprice.com?discord" target="_blank">
    <img alt="Discord: axtonprice" src="https://discord.com/api/guilds/826239258590969897/widget.png?style=shield" />
  </a>
</p>

> An extremely simple, one-time view encryption system. Send links anywhere on the internet, and the encrypted message will automatically be destroyed after being viewed once!

### ✨ <a href="https://quickblaze.axtonprice.com" target="_blank">Click to view Demo</a>

## Requirements

- Accessible webserver with PHP support.
- PHP v7 or higher.
- PHP [MBSTRING](http://php.net/manual/en/book.mbstring.php) module for full UTF-8 support.
- PHP [JSON](http://php.net/manual/en/book.json.php) module for JSON manipulation

## Installation

1. Download the latest version from the <a href="https://github.com/axtonprice-dev/quickblaze-encrypt/releases">releases page</a>. 
2. Upload and extract the contents to your web server. You can also pull the repo with `git pull`.
3. Visit your domain installation directory or subdomain https://example.com/quickblaze-encrypt/

#### Extra: *If using MYSQL as storage method:*
<ul>
  <li>Update the database information in <code>/modules/Database_example.env</code>.</li>
  <li>Rename the configuration file to <code>Database.env</code>. <a href="#system-configurations">View example configuration</a>.</li>
</ul>

⚠️ *Don't delete the `.version`, `.config`, or `.cache` files once the installation has completed! They contains necessary version and configuration data, and removing them **will** cause issues!*

## System Configurations
Example configuration layout of `Modules/Database.env`:
```json
{
    "HOSTNAME": "mysql.example.com",
    "USERNAME": "admin",
    "PASSWORD": "admin123",
    "DATABASE": "quickblaze_db"
}
```
Example configuration of `.config`:
```json
{ 
  "STORAGE_METHOD": "mysql",
  "LANGUAGE": "en",
  "INSTALLATION_PATH": "https://your-site.dev/quickblaze-encrypt"
}
```
⚠️ *Do not include a trailing slash for the installation path!*

## How it Works

The user enters the message they would like to encrypt. The system then securely encrypts the message and generates and returns an encryption key integrated into a shareable URL. *The key can be used to decrypt the encrypted message.* The system then creates a new record via the chosen storage method, containing the encrypted data and the encryption key. As soon as the decryption function is called upon, the encryption record will automatically be deleted. This means the encrypted data is now permanently lost and cannot be viewed or accessed. 
<br><br>
⚠️ *Keep your URL safe, it contains the encryption key! Exposing the URL means anybody will be able to view the encrypted message!*

## Screenshots *(Light/Dark Mode)*

<p align="center">
  <!-- Light Mode -->
  <img height="150" src="https://user-images.githubusercontent.com/37771600/163854079-ae8ea359-fce3-4157-8cff-114da799ff89.png">
  <img height="150" src="https://user-images.githubusercontent.com/37771600/163854117-bba6e982-0a1b-4a16-b785-78a093cdb09b.png">
  <img height="150" src="https://user-images.githubusercontent.com/37771600/163854146-746635c3-fce3-4725-a733-bf7646f4618f.png">
  <!-- Dark Mode -->
  <img height="150" src="https://user-images.githubusercontent.com/37771600/163853630-c5fe544d-9976-499f-859c-05efdc990947.png">
  <img height="150" src="https://user-images.githubusercontent.com/37771600/163853684-3ff0c1b5-039d-465c-abfb-dfc9af00c338.png">
  <img height="150" src="https://user-images.githubusercontent.com/37771600/163853762-ee6d721b-a0bd-482c-9fb9-4020bcf7653c.png">
</p>
  
## Authors and Contributors

👤 **axtonprice** - Main Author

* Discord: https://discord.gg/dP3MuBATGc
* Twitter: [@axtonprice](https://twitter.com/axtonprice)
* Github: [@axtonprice](https://github.com/axtonprice)

## Show your support

If you like this project, give a ⭐️ to support us!

## 📝 License

Copyright © 2022 [axtonprice](https://github.com/axtonprice).<br />
This project is [MIT](https://github.com/axtonprice-dev/quickblaze-encrypt/blob/main/LICENSE) licensed.

<hr>

<a href="https://discord.gg/dP3MuBATGc"><img src="https://discord.com/api/guilds/826239258590969897/widget.png?style=banner3"/></a>
