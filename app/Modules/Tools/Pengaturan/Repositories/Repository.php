<?php
namespace App\Modules\Tools\Pengaturan;

use App\Bases\BaseRepository;
use App\Modules\Tools\Pengaturan\Processor;
use App\Modules\Tools\Pengaturan\Rules\ValidasiUpload;

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
            'status'      => $request('status')
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
                    'rules' => ['required', new ValidasiUpload($this->getData())]
                ],
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
        default:
            $this->rules = [];
        }

    }

}
