<?php

return [
    'paypal' => [
        'mirrors' => [
            'new_debt' => [
                'url'=>env('URL_TESTER'),
                'method'=>'POST'
            ]
        ]
    ],
    'rapipago' => [
        'mirrors' => [
            'new_debt' => null
        ]
    ],
    'pagofacil' => [
        'mirrors' => [
            'new_debt' => null
        ]
    ],
];
