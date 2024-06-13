<?php
namespace App\Modules\Master\Unitkerja;

use App\Bases\BaseRepository;
use App\Modules\Master\Unitkerja\Processor;

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
            'tipe_unit_kerja_id' => $request('tipe_unit_kerja_id'),
            'tipe_unit_kerja_code' => $request('tipe_unit_kerja_code'),
            'unit_kerja' => $request('unit_kerja'),
            'status'      => $request('status'),
            'files'       => $request('files'),
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
                    'rules' => 'required|unique:master_unit_kerja'
                ],
                [
                    'field' => 'tipe_unit_kerja_id',
                    'label' => __('Tipe Unit Kerja'),
                    'rules' => 'required',
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
                    'rules' => 'nullable',
                ],
                [
                    'field' => 'tipe_unit_kerja_id',
                    'label' => __('Tipe Unit Kerja'),
                    'rules' => 'required',
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
        case 'import':
            $this->rules = [
                [
                    'field' => 'files',
                    'label' => __('File'),
                    'rules' => 'required|mimes:xlsx|max:5120'
                ]
            ];

            break;
        default:
            $this->rules = [];
        }

    }

}
