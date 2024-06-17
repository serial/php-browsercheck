# Description
## Browsercheck
A simple tool to check the client browser version and operating system with some more details.
The Browser User-Agent is detected and displayed in a nice way with some extra information see [features](#features).
The user can take a screenshot of the information or send it directly via E-Mail (PHPMailer or PHP mail function) to a given address.

# Requirements
* Webserver or localhost with PHP 7.4 or higher
* Mailserver so send vie SMTP (Optional)
* Composer to install dependencies (Optional) 

# Screenshot
Desktop and Mobile view
![Screenshot](https://github.com/serial/php-browsercheck/blob/master/screenshot.png)  
![Screenshot Mobile](https://github.com/serial/php-browsercheck/blob/master/screenshot-mobile.png)

# Installation
1. Clone this repository
2. Run `composer install` to install dependencies
3. Copy `config.php.sample` to `config.php` and edit the settings
4. Choose if you want to use the PHPMailer or the integrated PHP mail function
5. Open the index.php file in your browser


# Alternative installation
1. Upload the files to your webserver
2. Install composer on your server and run `composer install --no-dev --optimize-autoloader` inside the project folder
3. Copy `config.php.sample` to `config.php` and set the settings
4. Open the index.php file in your browser


# Configuration / troubleshooting
* Settings need to be set in the `config.php` file
* A static testmail script is included to test a smtp connection via PHPMailer
* If you want to use SMTP with Google, Apple, Microsoft or Azure, please refer to the documentation of PHPMailer for further modules
* In production make sure to set the `DEBUG` setting to `false` to hide error messages, otherwise the ajax requests message will not show in the frontend

# Features
* Detects
  * the browser and engine version
  * the operating system
  * the user's IP address
  * the user's browser resolution
  * the user's device type
* Screenshot / Capture
  * A button to take a screenshot / capture the information in a canvas
  * The canvas is shown in a nice fancybox
  * The fancybox provides a download button to save the image
* Send E-Mail
  * The data can be sent directly to an E-Mail address
  * You can choose to use a SMTP server or the PHP mail function
    * The SMTP server settings can be configured in the config.php file
  * Shows a message depending on the success or failure of the mail function
  * Send email button is disabled after sending the mail
* Styled messages for success and error
* Shows a unique token which is also sent via mail and on the screenshot
* Optimized for mobile devices


# Dev Notes
- For updating the browser and device detection, you can use the composer package manager.
  - You can easily update the WhichBrowser library by running the command `composer update whichbrowser/parser` in the project folder.
- Styling is done with LESS and compiled to CSS



# References
This project uses the following libraries

* **PHP**
  * [WhichBrowser](https://github.com/WhichBrowser/Parser-PHP) (To interpret the browsers User-Agent string and identify latest browsers and devices)
  * [PHPMailer](https://github.com/PHPMailer/PHPMailer) (Optional)
* **JS**
  * jQuery (3.5.1)
  * html2canvas (1.4.1)
  * FancyBox (3.5.7)
* **CSS / Fonts**
  * Fonts
    * Open Sans (locally integrated Google Font)
    * Roboto Mono (locally integrated Google Font)
  * Icon Library
    * Font Awesome Pro (6.5.2)

