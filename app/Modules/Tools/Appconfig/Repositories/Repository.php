<?php
namespace App\Modules\Tools\Appconfig;

use App\Bases\BaseRepository;
use App\Modules\Tools\Appconfig\Processor;
use App\Modules\Tools\Appconfig\Rules\Validate;

class Repository extends BaseRepository
{

    public function __construct(Processor $processor)
    {
        $this->processor = $processor;
    }

    public function getInput($request) {
        $this->data = [
            'id'          => $request('_id'),
            'config'      => $request('config'),
            'code'        => $request('code'),
            'status'      => $request('status'),
            'password'    => $request('password'),
        ];

    }

    public function setValidationRules() {
        switch ($this->operation_type) {
        case 'store':
            $this->rules = [
                [
                    'field' => 'config',
                    'label' => __('Konfigurasi'),
                    'rules' => 'required'
                ],
                [
                    'field' => 'code',
                    'label' => __('code'),
                    'rules' => 'required'
                ],
                [
                    'field' => 'status',
                    'label' => __('status'),
                    'rules' => 'required'
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
                    'field' => 'config',
                    'label' => __('Konfigurasi'),
                    'rules' => 'required'
                ],
                [
                    'field' => 'code',
                    'label' => __('code'),
                    'rules' => 'required'
                ],
                [
                    'field' => 'status',
                    'label' => __('status'),
                    'rules' => 'required'
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
        case 'unlockconfig':
            $this->rules = [
                [
                    'field' => 'password',
                    'label' => __('Password'),
                    'rules' => [
                        'required',
                        new Validate($this->getData())
                    ]
                ],
            ];

            break;
        default:
            $this->rules = [];
        }

    }

}
