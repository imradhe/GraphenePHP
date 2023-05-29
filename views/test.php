<?php

function validate($fields)
{
    $errors = [];
    $errorMsgs = [];

    foreach ($fields as $fieldName => $fieldData) {
        $value = $fieldData['value'];
        $rules = $fieldData['rules'];
        $customRules = isset($fieldData['custom_rules']) ? $fieldData['custom_rules'] : [];
        $errorMessages = $fieldData['error_messages'];

        foreach ($rules as $rule) {
            switch ($rule) {
                case 'required':
                    if (empty($value)) {
                        $errors[$fieldName] = true;
                        $errorMsgs[$fieldName] = $errorMessages['required'];
                    }
                    break;

                case 'email':
                    if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        $errors[$fieldName] = true;
                        $errorMsgs[$fieldName] = $errorMessages['email'];
                    }
                    break;

                case 'phone':
                    $phonePattern = "/^([6-9][0-9]{9})$/";
                    if (!empty($value) && !preg_match($phonePattern, $value)) {
                        $errors[$fieldName] = true;
                        $errorMsgs[$fieldName] = $errorMessages['phone'];
                    }
                    break;

                // Add more validation rules as needed

                default:
                    // Handle custom validation rules
                    if (isset($customRules[$rule])) {
                        $customRule = $customRules[$rule];
                        if (is_callable($customRule['rule']) && !$customRule['rule']($value)) {
                            $errors[$fieldName] = true;
                            $errorMsgs[$fieldName] = $customRule['message'];
                        }
                    }
                    break;
            }
        }
    }

    // Calculating error count
    $errorCount = count($errors);

    return ['error' => $errorCount > 0, 'errorMsgs' => $errorMsgs];
}

$name = "Radhe Shyam Salopanthula";
$email = "contact imradhe.com";
$fields = [
    'name' => [
        'value' => $name,
        'rules' => ['required', 'min_length:6'],
        'error_messages' => [
            'required' => "Name can't be empty",
            'min_length' => "Name can't be less than 6 characters",
        ],
    ],
    'email' => [
        'value' => $email,
        'rules' => ['required', 'email'],
        'error_messages' => [
            'required' => "Email can't be empty",
            'email' => "Email is invalid",
        ],
    ]
];


echo json_encode(validate($fields));