<?php
/*
 *    Desclare your module here
 *    Example:
 *    'modules' => [
 *        'Vendor' => [
 *            'Test' => ['Ahay'] // <== sub module
 *        ],
 *        'Employee',
 *    ]
 *
 *    Hot to get view in module?
 *    return view('admin/test/ahay::index');
 */

$submodules =  [
    'Admin'    => include app_path('Modules/Admin/submodules.php'),
    'Master'    => include app_path('Modules/Master/submodules.php'),
    'Tools' => include app_path('Modules/Tools/submodules.php'),
    'Laporan' => include app_path('Modules/Laporan/submodules.php'),
];

return $submodules;