# prestashop Console

Prestashop cli tools based on Symphony2 Console
You can read more about it : http://www.h-hennes.fr/blog/2016/05/19/console-prestashop/ (FR)

#Requires
Composer
Git

#How to install it
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