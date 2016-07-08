<?php
/**
 * Created by PhpStorm.
 * User: Damir Garifullin
 * Date: 08.07.16
 * Time: 13:36
 */

namespace indigerd\migrationaware;

trait MigrationAwareTrait
{
    /**
     * Returns migrations path of the module
     *
     * @return string
     */
    public function getMigrationPath()
    {
        $reflector = new \ReflectionClass(get_class($this));
        return dirname($reflector->getFileName()) . "/migrations";
    }
}
