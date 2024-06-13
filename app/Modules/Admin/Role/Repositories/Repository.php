<?php
namespace App\Modules\Admin\Role;

use App\Bases\BaseRepository;
use App\Modules\Admin\Role\Processor;
use App\Modules\Admin\Role\Model;

class Repository extends BaseRepository
{

    public function __construct(Processor $processor)
    {
        $this->processor = $processor;
    }

    public function getInput($request) {

        $this->data = [
            'id'           => $request('_id'),
            'name'         => $request('name'),
            'description'  => $request('description'),
            'guard_name'   => $request('guard_name', 'web'),
            'status'       => $request('status'),
            'permissions'  => $request('permissions'),
            'visibilities' => $request('visibilities'),
            'files'        => $request('files'),
            'mapping'      => $request('mapping'),
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
                    'field' => 'files',
                    'label' => __('File User Manual'),
                    'rules' => 'nullable|mimes:pdf'
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
                    'field' => 'files',
                    'label' => __('File User Manual'),
                    'rules' => 'nullable|mimes:pdf'
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
        default:
            $this->rules = [];
        }

    }

}
