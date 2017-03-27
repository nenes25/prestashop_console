# Prestashop Console Commands List

## Cache :

clean : clean cache key (default * )
 ```bash
 php console.php cache:clean [key]
 ```
flush : flush cache
 ```bash
 php console.php cache:flush
 ```
media : clear media cache
 ```bash
 php console.php cache:media
 ```
smarty clear : clear Smarty cache
 ```bash
 php console.php cache:smarty:clear
 ```
smarty configure : Configure smarty cache
 ```bash
 php console.php cache:smarty:configure configuration value
 ```
Allowed values (configuration => value ):
 PS_SMARTY_FORCE_COMPILE => 0 | 1 | 2
 PS_SMARTY_CACHE => 0 | 1
 PS_SMARTY_CACHING_TYPE => filesystem | mysql
 PS_SMARTY_CLEAR_CACHE => never | everytime

## Configuration

 ConfigurationName is the "name" of the configuration you want to change in the table ps_configuration

 delete : delete configuration value
 ```bash
 php console.php configuration:delete [configurationName]
 ```
 get : get configuration value
 ```bash
 php console.php configuration:get [configurationName]
 ```
 getAll : get All configuration values
 ```bash
 php console.php configuration:getAll [|grep configurationName ]
 ```
 set : Set configuration value
 ```bash
 php console.php configuration:set [configurationName] [configurationValue]
 ```

 mass : (updateValue | deleteByName | updateGlobalValue | set) operation configuration values with yml file ([example](examples/configuration.mass.yml))
 ```bash
 php console.php configuration:mass [configFile] 
 ```

## Modules
 disable : disable a specific module
 ```bash
 php console.php module:disable [moduleName]
 ```
 enable : enable a specific module
 ```bash
 php console.php module:enable [moduleName]
 ```
 install : install specific module
 ```bash
 php console.php module:install [moduleName]
 ```
 list : get module list
 ```bash
 php console.php module:list [active]
 ```
 reset : reset module
 ```bash
 php console.php module:reset [moduleName]
 ```
 uninstall : uninstall module
 ```bash
 php console.php module:reset [moduleName]
 ```

## Preferences
### cmscategory
Enable or disable Cms Category
```bash
 php console.php preferences:cmscategory [cmsCategoryId] [enable|disable(default)]
 ```
### cmspage
Enable or disable Cms Page
```bash
 php console.php preferences:cmspage [cmsPageId] [enable|disable(default)]
 ```
### maintenance
Enable or disable Shop
```bash
 php console.php preferences:maintenance [enable|disable(default)]
 ```
##### urlrewrite
Enable or disable Url Rewrite
```bash
 php console.php preferences:maintenance [enable|disable(default)]
 ```
##### Override
Enable or disable classes override
```bash
 php console.php preferences:override [enable|disable(default)]
 ```

##### Dev
List overrides of classes and controllers in the project
```bash
 php console.php dev:list-overrides
 ```
### Add missing index.php files in directory
Add missing index.php files in the specified directory
```bash
 php console.php dev:add-index-files [directory]
 ```

## Admin
### User
Create new admin user
```bash
 php console.php admin:user:create
 ```

### Password
Change admin password
```bash
 php console.php admin:user:create [--email=] [--password=] [--firstname=] [--lastname=]
 ```

##### Install
Allow to you install a fresh prestashop version
```bash
 php console.php install:install [--psVersion=] [--domainName=] [--dbname=] [--dbuser=] [--dbpassword=][--contactEmail=] [--adminpassword=] [--directory=]
 ```
Parameters are optionnals and will be asked during the process if not given