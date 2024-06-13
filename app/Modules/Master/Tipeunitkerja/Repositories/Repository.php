<?php
namespace App\Modules\Master\Tipeunitkerja;

use App\Bases\BaseRepository;
use App\Modules\Master\Tipeunitkerja\Processor;

class Repository extends BaseRepository
{

    public function __construct(Processor $processor)
    {
        $this->processor = $processor;
    }

    public function getInput($request) {
        $this->data = [
            'id'          => $request('_id'),
            'name'        => $request('name'),
            'code'        => $request('code'),
            'status'      => $request('status')
        ];

    }

    public function setValidationRules() {
        switch ($this->operation_type) {
        case 'store':
            $this->rules = [
                [
                    'field' => 'name',
                    'label' => __('Nama'),
                    'rules' => 'required'
                ],
                [
                    'field' => 'code',
                    'label' => __('code'),
                    'rules' => 'required|unique:master_tipe_unit_kerja'
                ],
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
                    'field' => 'code',
                    'label' => __('code'),
                    'rules' => 'required|unique:master_tipe_unit_kerja,code,'.$this->data['id']
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
