<?php

return [

    'common' => [
        'nf_cb_selected' => 'Data not found.',
        'multiple_delete' => ':total data can`t be restore.'
    ],
    'ajax' => [
        'confirm' => [
            'title' => 'Are you sure?',
            'text' => "The data you enter will be saved.",
        ],
        'delete' => [
            'title' => 'Are you sure?',
            'text' => 'Data can`t be restore.',
            'success' => [
                'title' => "Terhapus!",
                'text' => "Data berhasil dihapus."
            ]
        ],
        'success' => [
            'title' => 'Saved',
            'text' => "The data you entered has been successfully saved.",
        ],
        'restore' => [
            'title' => 'Restore Data',
            'text'  => 'Are you sure data will be restore?',
            'success' => [
                'title' => 'Restore',
                'text'  => 'The data successfully restore'
            ]
        ],
        'prevent_close' => 'Are you sure? Your process will be canceled.'
    ],
    'katalogrisiko' => [
        'import'=> [
            'confirm'=> [
                'text'  => 'The selected data will be imported into the application',
            ],
        ]
    ]

];
