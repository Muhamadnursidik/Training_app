<?php
namespace App\Modules\Admin\User;

use App\Bases\BaseRepository;
use App\Modules\Admin\User\Processor;
use App\Modules\Admin\User\Model;
use App\Modules\Admin\User\Rules\MatchOldPassword;

class Repository extends BaseRepository
{

    public function __construct(Processor $processor)
    {
        $this->processor = $processor;
    }

    public function getInput($request)
    {
        $this->data = [
            'id' => $request('_id'),
            'name' => $request('name'),
            'email' => $request('email'),
            'username' => $request('username'),
            'password' => $request('password'),
            'is_admin' => $request('is_admin'),
            'is_reviewer' => $request('is_reviewer'),
            'unit_kerja_id' => $request('unit_kerja_id'),
            'unit_kerja_code' => $request('unit_kerja_code'),
            'roles' => $request('roles'),
            'view_all_unit' => $request('view_all_unit'),
            'status' => $request('status'),
            'old_password' => $request('old_password'),
            'password_confirmation' => $request('password_confirmation'),
            'files' => $request('files'),
        ];

    }

    public function setValidationRules()
    {
        switch ($this->operation_type) {
            case 'store':
                $this->rules = [
                    [
                        'field' => 'name',
                        'label' => __('Nama'),
                        'rules' => 'required'
                    ],
                    [
                        'field' => 'email',
                        'label' => __('Email'),
                        'rules' => 'required|email|unique:users'
                    ],
                    [
                        'field' => 'username',
                        'label' => __('Username'),
                        'rules' => 'required|unique:users'
                    ],
                    [
                        'field' => 'unit_kerja_code',
                        'label' => __('Unit Kerja'),
                        'rules' => 'required'
                    ],
                    [
                        'field' => 'password',
                        'label' => __('Password'),
                        'rules' => 'required'
                    ],
                    [
                        'field' => 'roles',
                        'label' => __('Peran'),
                        'rules' => 'required|array'
                    ]
                ];

                break;
            case 'update':
                $this->rules = [
                    [
                        'field' => 'id',
                        'label' => __('ID'),
                        'rules' => 'required'
                    ],
                    [
                        'field' => 'name',
                        'label' => __('Nama'),
                        'rules' => 'required'
                    ],
                    [
                        'field' => 'email',
                        'label' => __('Email'),
                        'rules' => 'required|email|unique:users,email,' . $this->data['id']
                    ],
                    [
                        'field' => 'username',
                        'label' => __('Username'),
                        'rules' => 'required|unique:users,username,' . $this->data['id']
                    ],
                    [
                        'field' => 'unit_kerja_code',
                        'label' => __('Unit Kerja'),
                        'rules' => 'required'
                    ],
                    [
                        'field' => 'roles',
                        'label' => __('Peran'),
                        'rules' => 'required|array'
                    ]
                ];

                break;
            case 'destroy':
                $this->rules = [
                    [
                        'field' => 'id',
                        'label' => __('ID'),
                        'rules' => 'required'
                    ]
                ];

                break;
            case 'destroys':
                $this->rules = [
                    [
                        'field' => 'id',
                        'label' => __('ID'),
                        'rules' => 'required|array'
                    ],
                    [
                        'field' => 'id.*',
                        'label' => __('ID'),
                        'rules' => 'required'
                    ]
                ];

                break;
            case 'change-password':
                $this->rules = [
                    [
                        'field' => 'password',
                        'label' => __('Password Baru'),
                        'rules' => 'required|confirmed|min:6|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!@$#%*?^]).*$/'
                    ],
                    [
                        'field' => 'password_confirmation',
                        'label' => __('Konfirmasi Password'),
                        'rules' => 'required'
                    ],
                    [
                        'field' => 'old_password',
                        'label' => __('Password Lama'),
                        'rules' => ['required', new MatchOldPassword]
                    ]
                ];

                break;
            case 'import-user':
                $this->rules = [
                    [
                        'field' => 'files',
                        'label' => __('File'),
                        'rules' => 'required|mimes:xlsx'
                    ]
                ];

                break;
            default:
                $this->rules = [];
        }

    }

}
