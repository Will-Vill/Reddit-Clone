<?php
return [
    'required' => 'Questo campo non può essere vuoto.',
    'email'    => 'Devi inserire un indirizzo email valido.',
    'confirmed'=> 'La conferma della password non corrisponde.',

    'min' => [
        'string' => 'La password deve contenere almeno :min caratteri.',
    ],

    'max' => [
        'string' => 'Questo campo non può contenere più di :max caratteri.',
    ],

    'password' => [
        'min'       => 'La password deve contenere almeno :min caratteri.',
        'mixed'     => 'La password deve contenere almeno una lettera maiuscola e una minuscola.',
        'numbers'   => 'La password deve contenere almeno un numero.',
        'symbols'   => 'La password deve contenere almeno un simbolo.',
    ],

    'attributes' => [
        'username' => 'nome utente',
        'password' => 'password',
        'terms'    => 'termini e condizioni',
    ],
];


?>