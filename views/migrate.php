<?php
// Define the SQL table names and their corresponding SQL files

// Make sure you define tables in the right order for foreign key constraints

        $tables = [
            'users' => 'auth/users.sql',
            'logs' => 'auth/logs.sql',
            'visitors' => 'auth/visitors.sql',
            // Add more tables as needed
        ];
        
        // Migrate tables
        $migrate = Migrator::migrate($tables);
        
        echo json_encode($migrate);