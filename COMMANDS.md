# Prestashop Console Commands List

## Cache :
###( STILL IN BETA )

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
 set : Set configuration value
 ```bash
 php console.php configuration:set [configurationName] [configurationValue]
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
 php console.php module:list
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
