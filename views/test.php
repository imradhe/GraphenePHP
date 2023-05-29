<?php
require('models/validator.php');
$name = "Radhe Shyam";
$email = "arun@abc.com";
$fields = [
    'name' => [
        'value' => $name,
        'rules' => [
            [
                'type' => 'required',
                'message' => "Name can't be empty",
            ],
            [
                'type' => 'minLength',
                'message' => "Name can't be less than 6 characters",
                'minLength' => 6,
            ]
        ]
    ],
    'email' => [
        'value' => $email,
        'rules' => [
            [
                'type' => 'required',
                'message' => "Email can't be empty",
            ],
            [
                'type' => 'email',
                'message' => 'Email is invalid',
            ],
        ]
    ]
];

// Call the validateFields function
$result = Validator::validate($fields);

// Access the validation result
echo json_encode($result);