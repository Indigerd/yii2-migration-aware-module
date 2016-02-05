# yii2-migration-aware-module
Yii2 extension that allows modules to store migrations in their own folders and make them available for yii/migrate command

##Installation

The preferred way to install this extension is through composer.

Either run

php composer.phar require "indigerd/yii2-migration-aware-module" "*"

or add

"indigerd/yii2-migration-aware-module" : "*"

to the require section of your application's composer.json file.


##Usage

In your console config file in your migration section replace  with class property and add configFiles array property.
configFiles is array of configs where component will scan for your migration aware modules.
By default configFiles contains backend and frontend configs from advanced application template.
For example:

```php
        'migrate'=>[
            'class' => 'indigerd\migrationaware\controllers\MigrateController',
            'configFiles' => [
                '@backend/config/web.php',
                '@frontend/config/web.php',
                '@someAnotherAliasHere/config/web.php',
            ],
            
            'migrationPath'=>'@common/migrations/db', //leave as it was before
            'migrationTable'=>'{{%system_db_migration}}' //leave as it was before
        ],
```

In your modules that have migrations your need either to implement indigerd\migrationaware\MigrationAwareInterface or to extend from indigerd\migrationaware\MigrationAwareModule.
If you will implement interface you should implement method getMigrationPath and return in it folder which contains your module migrations.
For example:

```php
    public function getMigrationPath()
    {
        return __DIR__.'/migrations';
    }
```


##License

yii2-migration-aware-module is released under the MIT License. See the bundled LICENSE file for details.

