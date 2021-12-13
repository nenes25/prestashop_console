# PrestaShop Console

[![GitHub stars](https://img.shields.io/github/stars/nenes25/prestashop_console)](https://github.com/nenes25/eicaptcha/stargazers)
[![GitHub forks](https://img.shields.io/github/forks/nenes25/prestashop_console)](https://github.com/nenes25/eicaptcha/network)
[![Github All Releases](https://img.shields.io/github/downloads/nenes25/prestashop_console/total.svg)]()

PrestaShop cli tools based on Symfony Console component
You can read more about it : http://www.h-hennes.fr/blog/2016/05/19/console-prestashop/ (FR)

![PrestaShop console](console.png?raw=true "PrestaShop console")

Releases
---

You can download all the versions of the console (since 1.5) from the release page https://github.com/nenes25/prestashop_console/releases

Compatibility
---

| PrestaShop Version | Compatible |
| ------------------ | -----------|
| 1.5.x | :x: |
| 1.6.x | :x: |
| 1.6.1.x | :heavy_check_mark: (some commands are not available)|
| 1.7.0 to 1.7.8.x | :heavy_check_mark: |

| Php Version | Compatible |
| ------ | -----------|
| 5.6 | :heavy_check_mark:|
| 7.0 | :heavy_check_mark: |
| 7.1 | :heavy_check_mark: |
| 7.2 | :heavy_check_mark: |
| 7.3| :heavy_check_mark: |
| 7.4 | :heavy_check_mark: |
| 8.0 | :interrobang: Not yet tested |

How to use it
---

Download the file from github in your prestashop root directory (or from the release page):

```bash
wget https://github.com/nenes25/prestashop_console/releases/latest/download/prestashopConsole.phar
```

Add execution mode:

```bash
chmod +x prestashopConsole.phar
```

Run the console:

```bash
./prestashopConsole.phar
```

You can also add the phar globaly by adding it in your /usr/local/bin directory:

```bash
sudo mv prestashopConsole.phar /user/local/bin/prestashopConsole
```

Then run it with (only work in PrestaShop root directories):

```bash
prestashopConsole
```

You can check the list of commands here: [commands](COMMANDS.md).

If you want to contribute please see: [contribute](CONTRIBUTE.md).

Browser version
---

If no cli is available on your hosting, and if the php **exec** and **shell_exec** functions are enabled.  
You can use and download the file prestashopConsoleWrapper.php as a wrapper to run some commands directly from the browser.  
This wrapper is limited and cannot interact with the console.
All parameters should be passed through the url  

Here are some examples :
```
Show help of the command admin:user:list
prestashopConsoleWrapper.php?command=admin:user:list&options[]=help
List only active modules
prestashopConsoleWrapper.php?command=module:list&options[]=active
List only active modules not from prestashop
prestashopConsoleWrapper.php?command=module:list&options[]=active&options[]=no-native
```
