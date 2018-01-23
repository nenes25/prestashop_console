# Prestashop Console Commands List

## Cache :

clean : clean cache key (default * )
 ```bash
 cache:clean [key]
 ```
flush : flush cache
 ```bash
cache:flush
 ```
media : clear media cache
 ```bash
 cache:media
 ```
smarty clear : clear Smarty cache
 ```bash
 cache:smarty:clear
 ```
smarty configure : Configure smarty cache
 ```bash
 cache:smarty:configure configuration value
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
 configuration:delete [configurationName]
 ```
 get : get configuration value
 ```bash
 configuration:get [configurationName]
 ```
 getAll : get All configuration values
 ```bash
 configuration:getAll [|grep configurationName ]
 ```
 set : Set configuration value
 ```bash
 configuration:set [configurationName] [configurationValue]
 ```

 mass : (updateValue | deleteByName | updateGlobalValue | set) operation configuration values with yml file ([example](examples/configuration.mass.yml))
 ```bash
 configuration:mass [configFile]
 ```

## Modules
 disable : disable a specific module
 ```bash
 module:disable [moduleName]
 ```
 enable : enable a specific module
 ```bash
 module:enable [moduleName]
 ```
 install : install specific module
 ```bash
module:install [moduleName]
 ```
 list : get module list
 ```bash
 module:list [active]
 ```
 reset : reset module
 ```bash
 module:reset [moduleName]
 ```
 uninstall : uninstall module
 ```bash
 module:reset [moduleName]
 ```

### Hook
 list : list module hook(s)
 ```bash
module:hook:list [moduleName]
 ```
add : add module to hook(s)
 ```bash
module:hook:add [moduleName] [hookName1] [hookName2] [hookNameN] ..
 ```
remove : remove module from hook(s)
 ```bash
 module:hook:remove [moduleName] [hookName1] [hookName2] [hookNameN] ..
 ```

## Preferences
### cmscategory
Enable or disable Cms Category
```bash
 preferences:cmscategory [cmsCategoryId] [enable|disable(default)]
 ```
### cmspage
Enable or disable Cms Page
```bash
preferences:cmspage [cmsPageId] [enable|disable(default)]
 ```
### search
Add missing products to the index or re-build the entire index
```bash
preferences:search [add|rebuild(default)]
 ```
### maintenance
Enable or disable Shop
```bash
preferences:maintenance [enable|disable(default)]
 ```
##### urlrewrite
Enable or disable Url Rewrite
```bash
preferences:urlrewrite [enable|disable(default)]
 ```
##### Override
Enable or disable classes override
```bash
preferences:override [enable|disable(default)]
 ```

## Dev

List overrides of classes and controllers in the project
```bash
dev:list-overrides
 ```
Add missing index.php files in the specified directory
```bash
dev:add-index-files [directory]
 ```
List overrides of classes and controllers in the project
```bash
dev:list-overrides
 ```
### Cron  
List cron tasks configured with the module cronjobs
```bash
dev:cron:list
 ```
Run cron tasks configured with the module cronjobs
```bash
dev:cron:run [cronjob_id]
 ```

### Clean  
Clean existing websites datas ( catalog / sales ...)
```bash
dev:clean [all|catalog|sales]

## Admin
### User
Create new admin user
```bash
 admin:user:create
 ```

### Password
Change admin password
```bash
admin:user:create [--email=] [--password=] [--firstname=] [--lastname=]
 ```

##### Install
Allow to you install a fresh prestashop version
```bash
 install:install [--psVersion=] [--domainName=] [--dbname=] [--dbuser=] [--dbpassword=][--contactEmail=] [--adminpassword=] [--directory=]
 ```
Parameters are optionnals and will be asked during the process if not given