<?php
/**
 * @author      Alexander Stepanenko <alex.stepanenko@gmail.com>
 * @license     http://mit-license.org/
 */

namespace indigerd\migrationaware\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use indigerd\migrationaware\MigrationAwareInterface;

class MigrateController extends \yii\console\controllers\MigrateController
{
    /**
     * @var array
     */
    public $migrationLookup = [];

    /**
     * @var array
     */
    private $_migrationFiles;

    /**
     * @var array
     */
    public $configFiles = [
        '@backend/config/web.php',
        '@frontend/config/web.php',
    ];

    protected function populateMigrationLookup()
    {
        $config = [];
        foreach ($this->configFiles as $file) {
            $config = \yii\helpers\ArrayHelper::merge(
                $config,
                require(Yii::getAlias($file))
            );
        }
        $modules = isset($config['modules']) ? $config['modules'] : [];
        foreach ($modules as $moduleDefinition) {
            $module = new $moduleDefinition['class']($moduleDefinition);
            if ($module instanceof MigrationAwareInterface) {
                $this->migrationLookup[] = $module->getMigrationPath();
            }
        }
    }

    protected function getMigrationFiles()
    {
        if ($this->_migrationFiles === null) {
            $this->_migrationFiles = [];
            $this->populateMigrationLookup();
            $directories = array_merge($this->migrationLookup, [$this->migrationPath]);
            $extraPath = ArrayHelper::getValue(Yii::$app->params, 'yii.migrations');
            if (!empty($extraPath)) {
                $directories = array_merge((array) $extraPath, $directories);
            }

            foreach (array_unique($directories) as $dir) {
                $dir = Yii::getAlias($dir, false);
                if ($dir && is_dir($dir)) {
                    $handle = opendir($dir);
                    while (($file = readdir($handle)) !== false) {
                        if ($file === '.' || $file === '..') {
                            continue;
                        }
                        $path = $dir . DIRECTORY_SEPARATOR . $file;
                        if (preg_match('/^(m(\d{6}_\d{6})_.*?)\.php$/', $file, $matches) && is_file($path)) {
                            $this->_migrationFiles[$matches[1]] = $path;
                        }
                    }
                    closedir($handle);
                }
            }

            ksort($this->_migrationFiles);
        }

        return $this->_migrationFiles;
    }

    protected function createMigration($class)
    {
        $file = $this->getMigrationFiles()[$class];
        require_once($file);

        return new $class(['db' => $this->db]);
    }

    protected function getNewMigrations()
    {
        $applied = [];
        foreach ($this->getMigrationHistory(null) as $version => $time) {
            $applied[substr($version, 1, 13)] = true;
        }

        $migrations = [];
        foreach ($this->getMigrationFiles() as $version => $path) {
            if (!isset($applied[substr($version, 1, 13)])) {
                $migrations[] = $version;
            }
        }

        return $migrations;
    }
}