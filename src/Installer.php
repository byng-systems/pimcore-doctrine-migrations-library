<?php

/**
 * This file is part of the pimcore-doctrine-migrations-library package.
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the LICENSE is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Byng\Pimcore\DoctrineMigrations;

use Composer\Script\Event;

/**
 * Doctrine Migrations Installer
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
final class Installer
{
    /**
     * Copy Pimcore to the 'document-root-path', or a sensible default
     *
     * @param Event $event
     *
     * @return void
     */
    public static function install(Event $event)
    {
        $installPath = self::prepareBaseDirectorie($event);
        $migrationsDir = $installPath . "/migrations";

        $configFile = $installPath . "/migrations.yml";
        $dbConfigFile = $installPath . "/migrations-db.php";

        if (!file_exists($migrationsDir)) {
            mkdir($migrationsDir);
        }

        if (file_exists($configFile)) {
            unlink($configFile);
        }

        if (file_exists($dbConfigFile)) {
            unlink($dbConfigFile);
        }

        file_put_contents($configFile, self::createConfigFile());
        file_put_contents($dbConfigFile, self::createDbConfigFile());
    }

    /**
     * Create configuration file
     *
     * @return string
     */
    private static function createConfigFile()
    {
        return <<<EOF
name: Pimcore Migrations
migrations_namespace: PimcoreMigrations
table_name: doctrine_migration_versions
migrations_directory: ./migrations

EOF;
    }

    /**
     * Create database configuration file
     *
     * @return string
     */
    private static function createDbConfigFile()
    {
        return <<<EOF
<?php

/**
 * Doctrine Migrations Pimcore Bootstrap
 *
 * @author Asim Liaquat <asim@byng.co>
 */

require_once __DIR__ . "/web/pimcore/cli/startup.php";

\$config = Pimcore\Config::getSystemConfig()->database;
\$driver = strtolower(\$config->adapter);
\$params = \$config->params;

return [
    "driver"   => \$driver,
    "host"     => \$params->host,
    "dbname"   => \$params->dbname,
    "user"     => \$params->username,
    "password" => \$params->password,
    "port"     => \$params->port,
];

EOF;
    }

    /**
     * Prepare base directories for copying and installation
     *
     * @param Event $event
     * @return array
     */
    private static function prepareBaseDirectorie(Event $event)
    {
        $config = $event->getComposer()->getConfig();
        $cwd = getcwd();
        $installPath = realpath($cwd . DIRECTORY_SEPARATOR . ($config->get("project-root-path") ?: "./"));

        if (!$installPath) {
            throw new \RuntimeException(
                "Invalid install path, or vendor path. Note: the directories must exist. " .
                "Install path was '" . $installPath . "'. Aborting Pimcore installation."
            );
        }

        return $installPath;
    }
}
