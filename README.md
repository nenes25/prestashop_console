# prestashop Console

[![GitHub stars](https://img.shields.io/github/stars/nenes25/prestashop_console)](https://github.com/nenes25/eicaptcha/stargazers) 
[![GitHub forks](https://img.shields.io/github/forks/nenes25/prestashop_console)](https://github.com/nenes25/eicaptcha/network) 
[![Github All Releases](https://img.shields.io/github/downloads/nenes25/prestashop_console/total.svg)]()
[![PHP tests](https://github.com/nenes25/prestashop_console/actions/workflows/php.yml/badge.svg)](https://github.com/nenes25/prestashop_console/actions/workflows/php.yml)

Prestashop cli tools based on Symfony Console component   
You can read more about it : http://www.h-hennes.fr/blog/2016/05/19/console-prestashop/ (FR)

![PrestashopConsole](console.png?raw=true "Prestashop console")

# Releases
You can download all the versions of the console (since 1.5 ) from the release page https://github.com/nenes25/prestashop_console/releases  

# Compatibility

| Prestashop Version | Compatible |
| ------------------ | -----------|
| 1.5.x | :x: |
| 1.6.x | :x: |
| 1.6.1.x | :x: |
| 1.7.0.x | :heavy_check_mark: |
| 1.7.1.x | :heavy_check_mark: |
| 1.7.2.x | :heavy_check_mark: |
| 1.7.3.x | :heavy_check_mark: |
| 1.7.4.x | :heavy_check_mark: |
| 1.7.5.x | :heavy_check_mark: |
| 1.7.6.x | :heavy_check_mark: |
| 1.7.7.x | :heavy_check_mark: |
| 1.7.8.x | :interrobang: Not yet tested |

| Php Version | Compatible |
| ------ | -----------|
| 5.6 | :x: No more compatible see v 1.x|
| 7.0 | :x: No more compatible see v 1.x |
| 7.1 | :x: No more compatible see v 1.x |
| 7.2 | :heavy_check_mark: |
| 7.3 | :heavy_check_mark: |
| 7.4 | :heavy_check_mark: |
| 8.0 | :interrobang: Not yet tested |

# How to use it

download the file from github in your prestashop root directory ( or from the release page )   
 ```bash
wget https://github.com/nenes25/prestashop_console/releases/latest/download/prestashopConsole.phar
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

You can check the list of commands here : [commands](COMMANDS.md)  

If you want to contribute please see : [contribute](CONTRIBUTE.md)
