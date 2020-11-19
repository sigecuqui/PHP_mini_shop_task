<?php

require '../bootloader.php';

if (is_logged_in()) {
    header('Location: login.php');
    exit();
}

$form = [
    'attr' => [
        'method' => 'POST',
    ],
    'fields' => [
        'email' => [
            'label' => 'EMAIL',
            'type' => 'text',
            'validators' => [
                'validate_field_not_empty',
                'validate_email',
                'validate_user_unique',
            ],
            'extra' => [
                'attr' => [
                    'placeholder' => 'Enter email',
                    'class' => 'input-field',
                ]
            ]
        ],
        'password' => [
            'label' => 'PASSWORD',
            'type' => 'text',
            'validators' => [
                'validate_field_not_empty',
            ],
            'extra' => [
                'attr' => [
                    'placeholder' => 'Enter password',
                    'class' => 'input-field',
                ]
            ]
        ],
        'password_repeat' => [
            'label' => 'PASSWORD REPEAT',
            'type' => 'text',
            'validators' => [
                'validate_field_not_empty',
            ],
            'extra' => [
                'attr' => [
                    'placeholder' => 'Repeat password',
                    'class' => 'input-field',
                ]
            ]
        ],
    ],
    'buttons' => [
        'send' => [
            'title' => 'REGISTER',
            'type' => 'submit',
            'extra' => [
                'attr' => [
                    'class' => 'btn',
                ]
            ]
        ]
    ],
    'validators' => [
        'validate_fields_match' => [
            'password',
            'password_repeat'
        ]
    ]
];

$clean_inputs = get_clean_input($form);

if ($clean_inputs) {
    $is_valid = validate_form($form, $clean_inputs);

    if ($is_valid) {
        unset($clean_inputs['password_repeat']);

        // Get data from file
        $input_from_json = file_to_array(DB_FILE);
        // Append new data from form
        $input_from_json['users'][] = $clean_inputs;
        // Save old data together with appended data back to file
        array_to_file($input_from_json, DB_FILE);

        $text = 'Registration successful';
        header('Location: login.php');
    } else {
        $text = 'Registration failed';
    }
}

?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Registration</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<?php include(ROOT . '/core/templates/nav.php'); ?>
<main>
    <h2>REGISTRATION</h2>
    <?php require ROOT . '/core/templates/form.tpl.php'; ?>
    <p><?php if (isset($text)) print $text; ?></p>
</main>
</body>
</html>

