# prestashop Console

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/f72aeabcce684a8ca888cd53a954212e)](https://app.codacy.com/app/nenes25/prestashop_console?utm_source=github.com&utm_medium=referral&utm_content=nenes25/prestashop_console&utm_campaign=Badge_Grade_Dashboard)

Prestashop cli tools based on Symfony Console component   
You can read more about it : http://www.h-hennes.fr/blog/2016/05/19/console-prestashop/ (FR)

# Releases
You can download all the versions of the console (since 1.5 ) from the release page https://github.com/nenes25/prestashop_console/releases  


# Phar version

download the file from github in your prestashop root directory ( or from the release page )   
 ```bash
wget https://github.com/nenes25/prestashop_console/raw/master/bin/prestashopConsole.phar
 ```

Add execution mode  
  ```bash
chmod +x prestashopConsole.phar
 ```
 

Run the console  
 ```bash
./prestashopConsole.phar
 ```
 
 You can also add the phar globaly by adding it in your /usr/local/bin directory
  ```bash
sudo mv prestashopConsole.phar /user/local/bin/prestashopConsole
 ```
 
 Then run it with ( Only work in Prestashop root directories )
  ```bash
prestashopConsole
 ```

# Php Version

## Requires
Composer
Git

## How to install it
Login to your hosting with ssh and go the root directory of your prestashop

Clone the github repository in the directory console
 ```bash
git clone https://github.com/nenes25/prestashop_console.git console
 ```
Go into the directory and run composer install
 ```bash
cd console
composer install
 ```
Then everything is installed and you can run the console with
 ```bash
php console.php
 ```
To get all the command list, please see COMMANDS.md
If you want to contribute, please see how in CONTRIBUTE.md
