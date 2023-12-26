<?php

/**
 * An example controller template
 *
 * GraphenePHP Example Controller
 *
 * This class provides validation functionalities for form fields.
 * It allows defining validation rules and callbacks for each field,
 * and returns error messages for invalid fields.
 *
 * @package GraphenePHP
 * @version 2.0.0
 */
class ExampleController
{
    // Create operation
    public function create($data)
    {
        // Code to create a new record using the provided data
        // ...

        return ['success' => true, 'message' => 'Record created successfully'];
    }

    // Read operation
    public function read($id)
    {
        // Code to fetch a record based on the provided ID
        // ...
        $record = "";
        if ($record) {
            return ['success' => true, 'data' => $record];
        } else {
            return ['success' => false, 'message' => 'Record not found'];
        }
    }

    // Update operation
    public function update($id, $data)
    {
        // Code to update the record with the provided ID using the new data
        // ...
        $updated = "";

        if ($updated) {
            return ['success' => true, 'message' => 'Record updated successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to update record'];
        }
    }

    // Delete operation
    public function delete($id)
    {
        // Code to delete the record with the provided ID
        // ...

        $deleted = "";
        
        if ($deleted) {
            return ['success' => true, 'message' => 'Record deleted successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to delete record'];
        }
    }
}
