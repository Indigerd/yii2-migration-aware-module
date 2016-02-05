<?php
/**
 * @author      Alexander Stepanenko <alex.stepanenko@gmail.com>
 * @license     http://mit-license.org/
 */

namespace indigerd\migrationaware;

class MigrationAwareModule extends \yii\base\Module implements MigrationAwareInterface
{
    /**
     * @return string
     */
    public function getMigrationPath()
    {
        return __DIR__.'/migrations';
    }
}
