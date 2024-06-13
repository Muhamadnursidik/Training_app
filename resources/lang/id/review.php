<?php

return [
    'send_msg_reviewer' => 'Terdapat kejadian risiko :parameter yang harus anda review/setujui',
    'send_msg_tindaklanjut_approval' => 'Terdapat action plan kejadian risiko :parameter yang harus anda review/setujui',
    'send_msg_tindaklanjut_approval_kri' => 'Terdapat action plan KRI :parameter yang harus anda review/setujui',
    'send_msg_reject_user' => 'Terdapat kejadian risiko :parameter yang anda ajukan telah direview dengan status :status oleh :reviewer',
    'send_msg_approved_user' => 'Terdapat kejadian risiko :parameter yang anda ajukan telah direview dengan status :status oleh :reviewer',
    'send_msg_approved_tindaklanjut_user' => 'Action plan :parameter yang anda ajukan telah direview dengan status :status oleh :reviewer',
    'send_msg_reject_tindaklanjut_user' => 'Action plan :parameter yang anda ajukan telah direview dengan status :status oleh :reviewer',
    'send_msg_realisasi_tindaklanjut_approval' => 'Terdapat realisasi action plan :parameter yang harus anda review/setujui',
    'send_msg_realisasi_tindaklanjut_review' => 'Terdapat realisasi action plan :parameter yang anda ajukan telah direview dengan status :status oleh :reviewer',
    'rcsa' => [
        'notif_review_to_reviewer' => 'Terdapat data penetapan risk register periode <b> :periode </b> yang harus anda review',
        'notif_review_to_operator' => 'Terdapat data penetapan risk register periode <b> :periode </b> yang telah <b> :status </b> oleh reviewer',
        'monitoring' => [
            'reviewer' => 'Terdapat monitoring perlakuan risiko pada <b> :periode </b> yang harus anda review',
            'operator' => 'Terdapat monitoring perlakuan risiko pada <b> :periode </b> yang telah <b> :status </b> oleh reviewer',
        ]
    ],
    'kajianrisiko' => [
        'review' => [
            'notif'     => 'Terdapat kajian risiko yang harus anda review',
            'approved'  => 'Data Kajian risiko telah di approve oleh reviewer :reviewer',
            'rejected'  => 'Data Kajian risiko telah di reject oleh reviewer :reviewer',
            'notif_realisasi'    => 'Terdapat Realisasi Perlakuan Risiko ARM :parameter yang harus anda review/setujui',
            'approved_realisasi' => 'Realisasi Perlakuan Risiko ARM :parameter telah di approve oleh reviewer :reviewer',
            'rejected_realisasi' => 'Realisasi Perlakuan Risiko ARM :parameter telah di reject oleh reviewer :reviewer',
        ],
        'operator' => [
            'notif' => 'Kajian risiko telah di :status oleh reviewer :reviewer',
            'notif_realisasi' => 'Realisasi Perlakuan Risiko ARM :parameter telah di :status oleh reviewer :reviewer'
        ]
    ]
];