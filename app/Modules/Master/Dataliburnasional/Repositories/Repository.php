<?php
namespace App\Modules\Master\Dataliburnasional;

use App\Bases\BaseRepository;
use App\Modules\Master\Dataliburnasional\Processor;

class Repository extends BaseRepository
{
    public function __construct(Processor $processor)
    {
        $this->processor = $processor;
    }

    public function getInput($request) {
        $this->data = [
            'id'          => $request('_id'),
            'tanggal'     => $request('tanggal'),
            'keterangan'  => $request('keterangan'),
        ];
    }

    public function setValidationRules() {
        switch ($this->operation_type) {
            case 'store':
                $this->rules = [
                    [
                        'field' => 'tanggal',
                        'label' => __('Tanggal'),
                        'rules' => 'required|date'
                    ],
                    [
                        'field' => 'keterangan',
                        'label' => __('Keterangan'),
                        'rules' => 'required|string'
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
                        'field' => 'tanggal',
                        'label' => __('Tanggal'),
                        'rules' => 'required|date'
                    ],
                    [
                        'field' => 'keterangan',
                        'label' => __('Keterangan'),
                        'rules' => 'required|string'
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

            case 'restore':
                $this->rules = [
                    [
                        'field' => 'id',
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
