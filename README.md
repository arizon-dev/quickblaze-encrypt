<h1 align="center">QuickBlaze Encryption 👋</h1>

<p align="center">
  <img alt="Version" src="https://img.shields.io/badge/version-v1.0.6_Dev-red.svg?cacheSeconds=2592000" />
  <img alt="Hits" src="https://hits.seeyoufarm.com/api/count/incr/badge.svg?url=https%3A%2F%2Fgithub.com%2Faxtonprice-dev%2Fquickblaze-encrypt%2F&count_bg=%2379C83D&title_bg=%23555555&icon=&icon_color=%23E7E7E7&title=Project+Hits&edge_flat=false)](https://hits.seeyoufarm.com" />
  <a href="https://github.com/axtonprice/quickblaze-encrypt/blob/main/LICENSE" target="_blank">
    <img alt="License: MIT" src="https://img.shields.io/badge/License-MIT-yellow.svg" />
  </a>
  <a href="https://axtonprice.com?discord" target="_blank">
    <img alt="Discord: axtonprice" src="https://discord.com/api/guilds/826239258590969897/widget.png?style=shield" />
  </a>
</p>

> An extremely simple, one-time view encryption system. Send links anywhere on the internet, and the encrypted message will automatically be destroyed after being viewed once!

### ✨ <a href="https://quickblaze.axtonprice.com" target="_blank">Click to view Demo</a>

## Requirements

- Accessible webserver with PHP support.
- PHP v7 or higher version.
- PHP [MBSTRING](http://php.net/manual/en/book.mbstring.php) module for full UTF-8 support.
- PHP [JSON](http://php.net/manual/en/book.json.php) module for JSON manipulation

## Installation

1. Download the latest version from the <a href="https://github.com/axtonprice/quickblaze-encrypt/releases">releases page</a>. 
2. Upload and extract the file to your web server or hosting subdomain. 
3. Update the database information in `/modules/Database_example.env`, then ensure you rename the file to `Database.env`. [(See below for layout format)](#configuration)
4. Visit your domain https://quickblaze.example.com/
5. Enjoy!

⚠️ *Don't delete the `.version` file! It contains necessary version data, and modifying it may cause issues!*

## Configuration
Example configuration layout of `Database.env`:
```json
{
    "HOSTNAME": "mysql.example.com",
    "USERNAME": "admin",
    "PASSWORD": "admin123",
    "DATABASE": "quickblaze_db"
}
```

## How it Works

The user enters the message they would like to encrypt. The system then securely encrypts the message, and generates an encryption key. *The key can be used to decrypt the encrypted message.* The system then creates a new record in the database, containing the encrypted data and the encryption key. Once the decryption function is executed (indicating the user has viewed the message) the database record is deleted along with the encryption data and key. This means the data is now permanently lost and cannot be viewed, accessed or recovered. <br><br>Keep your URL safe, it contains the encryption key! Exposing the URL means anybody will be able to view the encrypted message!

## Screenshots *(Light/Dark Mode)*

<p align="center">
  <!-- Light Mode -->
  <img height="150" src="https://user-images.githubusercontent.com/37771600/163694900-13dbb832-05f2-4a55-856d-9d0d9e315621.png">
  <img height="150" src="https://user-images.githubusercontent.com/37771600/163694908-a332341b-6230-440c-a3e6-477c54193f71.png">
  <img height="150" src="https://user-images.githubusercontent.com/37771600/163694918-84fe78cc-286b-4d8d-8eef-0a172b517c0e.png">
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
This project is [MIT](https://github.com/axtonprice/quickblaze-encrypt/blob/main/LICENSE) licensed.

<hr>

<a href="https://discord.gg/dP3MuBATGc"><img src="https://discord.com/api/guilds/826239258590969897/widget.png?style=banner3"/></a>
