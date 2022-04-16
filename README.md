<h1 align="center">QuickBlaze Encryption 👋</h1>
<p>
  <img alt="Version" src="https://img.shields.io/badge/version-v1.0.5_Dev-red.svg?cacheSeconds=2592000" />
  <a href="https://github.com/axtonprice/quickblaze-encrypt/blob/main/LICENSE" target="_blank">
    <img alt="License: MIT" src="https://img.shields.io/badge/License-MIT-yellow.svg" />
  </a>
  <a href="https://axtonprice.com?discord" target="_blank">
    <img alt="Discord: axtonprice" src="https://img.shields.io/discord/826239258590969897" />
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

⚠️ *Don't delete the `.version` file! It contains necessary version data, and modifying it may cause issues!.*

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

The user enters the message they would like to encrypt. The system then securely encrypts the message, and generates an encryption key. *The key can be used to decrypt the encrypted message.* The system then creates a new record in the database, containing the encrypted data and the encryption key. Once the decryption function is executed (indicating the user has viewed the message) the database record is deleted along with the encryption data and key. This means the data is now permanently lost and cannot be viewed, accessed or recovered. <br><br>Keep your URL safe, it contains the encryption key! Without it, the data cannot be decrypted!

## Screenshots

<p align="center">
  <img height="150" src="https://user-images.githubusercontent.com/37771600/162692669-273df86d-2e53-49c9-bd08-637c90f155be.png">
  <img height="150" src="https://user-images.githubusercontent.com/37771600/162692731-b3c6d03f-41a1-42f7-b4dc-8ed8661f121f.png">
  <img height="150" src="https://user-images.githubusercontent.com/37771600/162692774-2116a65b-d9e0-4841-a58b-ff89c55d5f63.png">
  <img height="150" src="https://user-images.githubusercontent.com/37771600/162692802-b3d91607-99e1-4b48-98a6-2fdf70dfe5de.png">
  <img height="150" src="https://user-images.githubusercontent.com/37771600/163634259-b66127ef-0e7b-4e48-a1f7-113a51ea2d22.png">
  <img height="150" src="https://user-images.githubusercontent.com/37771600/163634350-13dd2d42-f4b2-4978-9afe-4974d89a459c.png">
</p>
  
## Authors, Credits, and Contributors

👤 **axtonprice** - Main Author

* Discord: https://discord.gg/dP3MuBATGc
* Twitter: [@axtonprice](https://twitter.com/axtonprice)
* Github: [@axtonprice](https://github.com/axtonprice)

## Show your support

If you like this project, give a ⭐️ to support us!

## 📝 License

Copyright © 2022 [axtonprice](https://github.com/axtonprice).<br />
This project is [MIT](https://github.com/axtonprice/quickblaze-encrypt/blob/main/LICENSE) licensed.
