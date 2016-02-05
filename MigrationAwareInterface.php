<?php
/**
 * @author      Alexander Stepanenko <alex.stepanenko@gmail.com>
 * @license     http://mit-license.org/
 */

namespace indigerd\migrationaware;

interface MigrationAwareInterface
{
    /**
     * @return string
     */
    public function getMigrationPath();
}
