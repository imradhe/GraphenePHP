<?php
/**
 * GraphenePHP Validator Class
 *
 * This class provides validation functionalities for form fields.
 * It allows defining validation rules and callbacks for each field,
 * and returns error messages for invalid fields.
 *
 * @package GraphenePHP
 * @version 2.0.0
 */

class Validator
{
    /**
     * Validate form fields
     *
     * This method validates the provided form fields based on the specified rules and callbacks.
     *
     * @param array $fields An associative array of form fields with their values, rules, and callbacks.
     * @return array An array containing the validation result, error messages, and fields.
     */
    public static function validate($fields)
    {
        $errors = [];
        $errorMsgs = [];

        foreach ($fields as $fieldName => $fieldData) {
            $value = $fieldData['value'];
            $rules = $fieldData['rules'];
            $callbacks = $fieldData['callbacks'];
            $errorMessages = [];

            // Apply rules validation
            foreach ($rules as $rule) {
                $ruleType = $rule['type'];
                $message = $rule['message'];

                switch ($ruleType) {
                    case 'required':
                        if (empty($value)) {
                            $errorMessages[] = $message;
                        }
                        break;

                    case 'minLength':
                        if (!empty($value) && strlen($value) < $rule['minLength']) {
                            $errorMessages[] = $message;
                        }
                        break;

                    case 'maxLength':
                        if (!empty($value) && strlen($value) > $rule['maxLength']) {
                            $errorMessages[] = $message;
                        }
                        break;

                    case 'email':
                        if (!empty($value) && !preg_match('/^[^\s@]+@[^\s@]+\.[^\s@]+$/', $value)) {
                            $errorMessages[] = $message;
                        }
                        break;

                    case 'phone':
                        if (!empty($value) && !preg_match('/^[6-9][0-9]{9}$/', $value)) {
                            $errorMessages[] = $message;
                        }
                        break;

                    case 'pan':
                        if (!empty($value) && !preg_match('/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/', $value)) {
                            $errorMessages[] = $message;
                        }
                        break;

                    case 'aadhaar':
                        if (!empty($value) && !preg_match('/^[2-9]{1}[0-9]{11}$/', $value)) {
                            $errorMessages[] = $message;
                        }
                        break;

                    case 'password':
                        if (!empty($value) && !preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $value)) {
                            $errorMessages[] = $message;
                        }
                        break;

                    case 'url':
                        if (!empty($value) && !preg_match("/^(https?:\/\/)[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}(\/\S*)?$/", $value)) {
                            $errorMessages[] = $message;
                        }
                        break;

                    case 'domain':
                        if (!empty($value) && !preg_match("/^[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $value)) {
                            $errorMessages[] = $message;
                        }
                        break;

                    // Add more rule types as needed
                    default:
                        // Custom rule type
                        $validateCallback = $rule['validate'];
                        if (!empty($value) && !$validateCallback($value)) {
                            $errorMessages[] = $message;
                        }
                        break;
                }
            }

            // Apply callback validation
            foreach ($callbacks as $callback) {
                $validateCallback = $callback['validate'];
                $message = $callback['message'];
                if (!$validateCallback($value)) {
                    $errorMessages[] = $message;
                }
            }

            $errors[$fieldName] = count($errorMessages) > 0;
            $errorMsgs[$fieldName] = $errorMessages;
        }

        $errorCount = array_reduce($errors, function ($acc, $error) {
            return $acc + ($error ? 1 : 0);
        }, 0);

        return [
            'error' => $errorCount > 0,
            'errorMsgs' => $errorMsgs,
            'fields' => array_map(fn($field) => $field['value'], $fields)
        ];

    }
}
