<?php
/**
 * GraphenePHP Migrator Class
 *
 * This class provides functionality to migrate database tables.
 * It connects to the database, checks if tables already exist,
 * reads SQL files, and executes the SQL queries to create tables.
 *
 * @package GraphenePHP
 * @version 2.0.0
 */
class Migrator
{   
    /**
     * Migrate tables
     *
     * This method migrates the provided tables by executing SQL queries.
     *
     * @param array $tables An associative array of tables and their corresponding SQL files.
     * @return array An array containing the migration output, error status, and error messages.
     */
    public static function migrate($tables)
    {
        $errors = [];
        $error = false;
        $errorMsgs = [];
        $output = [];

        // Connect to the database
        DB::connect();

        foreach ($tables as $table => $sqlFile) {
            array_push($output, "Migrating table: $table");

            // Check if table already exists
            $tableExists = DB::query("SHOW TABLES LIKE '$table'");

            if (count($tableExists) > 0) {
                $errors[$table] = true;
                $errorMsgs[$table] = "Table already exists";
            } else {
                // Read the SQL file content
                $sql = file_get_contents("models/$sqlFile");

                // Execute the SQL query
                DB::execute($sql);
                array_push($output, "Migrating table: $table");
            }
        }

        // Close the database connection
        DB::close();

        foreach ($errors as $count) {
            $error += $count;
        }

        return [
            'output' => $output,
            'error' => ($error) ? true : false,
            'errorMsgs' => $errorMsgs
        ];
    }
}
