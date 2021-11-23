<?php

namespace Mist\Migrations;

use Mist\Core\Database;

class Migration
{
    /**
     * Database instance
     *
     * @var Database
     */
    protected $db;

    /**
     * Folder containing migration scripts
     *
     * @var string
     */
    protected $folder = __DIR__;

    /**
     * Latest version of a migration
     *
     * @var string
     */
    protected $version;

    public function __construct(Database $db)
    {
        $this->db = $db;
        $this->_createMigrationsTable();
        $this->version = $this->_getLatestVersion();
    }

    /**
     * Run migration scripts
     *
     * @return void
     */
    public function migrate()
    {
        $latest = explode('.', $this->version);
        $files = array_filter(
            scandir($this->folder),
            fn ($file) => strpos($file, '.sql')
        );

        $migrations = [];

        foreach ($files as $file) {
            $migrations = array_merge(
                $migrations,
                [explode('-', $file)[0] => $file]
            );
        }

        foreach ($migrations as $version => $filename) {
            $versionArr = explode('.', $version);
            if ($versionArr !== $latest) {
                $res = array_every(
                    fn ($value, $key) => $value >= $latest[$key],
                    explode('.', $version)
                );

                if ($res) {
                    $content = $this->_getMigrationFileContent($filename);
                    $this->db->exec($content);
                    $this->_addMigration($filename, $version);
                }
            }
        }
    }

    /**
     * Get latest version of migration script from database
     *
     * @return string
     */
    private function _getLatestVersion()
    {
        $query = "SELECT version FROM `migrations` ORDER BY id DESC LIMIT 1";
        $stmt = $this->db->query($query);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC)['version'] ?? '0.0.0';
    }

    /**
     * Create table for managing migration scripts
     *
     * @return int|false
     */
    private function _createMigrationsTable()
    {
        $query = "CREATE TABLE IF NOT EXISTS `migrations` (
            `id` INT NOT NULL AUTO_INCREMENT,
            `name` VARCHAR(255) NOT NULL,
            `version` VARCHAR(32) NOT NULL,
            `created_at` DATETIME NOT NULL,
            PRIMARY KEY (`id`)
        )";
        return $this->db->exec($query);
    }

    /**
     * Get content of migration script file
     *
     * @param string $file file name
     *
     * @return string
     */
    private function _getMigrationFileContent($file)
    {
        return file_get_contents($this->folder . DIRECTORY_SEPARATOR . $file);
    }

    /**
     * Add entry to migrations table
     *
     * @param string $filename file name
     * @param string $version version
     *
     * @return int|false
     */
    private function _addMigration($filename, $version)
    {
        $migrationName = basename(explode('-', $filename)[1], '.sql');
        $query = "INSERT INTO `migrations` (`name`, `version`, `created_at`) VALUES ('$migrationName', '$version', now())";
        return $this->db->exec($query);
    }
}
