<?php

return [

    'common' => [
        'nf_cb_selected' => 'Data yang akan dihapus tidak ditemukan.',
        'multiple_delete' => ':total data yang dihapus tidak dapat dikembalikan.'
    ],
    'ajax' => [
        'confirm' => [
            'title' => 'Apakah anda yakin?',
            'text' => "Data yang anda inputkan akan disimpan.",
        ],
        'delete' => [
            'title' => 'Apakah anda yakin?',
            'text' => 'Data yang dihapus tidak dapat dikembalikan.',
            'success' => [
                'title' => "Terhapus!",
                'text' => "Data berhasil dihapus."
            ]
        ],
        'success' => [
            'title' => 'Tersimpan!',
            'text' => "Data yang anda inputkan berhasil disimpan.",
        ],
        'restore' => [
            'title' => 'Restore Data',
            'text'  => 'Apakah data ini akan dikembalikan?',
            'success' => [
                'title' => 'Restore',
                'text'  => 'Data berhasil dikembalikan'
            ]
        ],
        'prevent_close' => 'Are you sure? Your process will be canceled.'
    ],
    'katalogrisiko' => [
        'import'=> [
            'confirm'=> [
                'text'  => 'Data yang dipilih akan dimport ke dalam aplikasi',
            ],
        ]
    ]

];
