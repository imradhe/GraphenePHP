<?php

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
