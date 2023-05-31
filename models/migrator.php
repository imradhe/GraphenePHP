<?php

class Migrator
{
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
                array_push($output, "Migratind table: $table");
            }
        }

        // Close the database connection
        DB::close();

        foreach($errors as $count)
        $error += $count;
        
        return ['output' => $output, 'error' => ($error)? true : false, 'errorMsgs' => $errorMsgs];
    }
}