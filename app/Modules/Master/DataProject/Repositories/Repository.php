<?php

namespace App\Modules\Master\DataProject\Repositories;

use App\Bases\BaseRepository;
use App\Modules\Master\DataProject\Processors\Processor;
use App\Modules\Master\DataMitra\Models\Datamitra;

class Repository extends BaseRepository
{
    protected $processor;

    public function __construct(Processor $processor)
    {
        $this->processor = $processor;
    }
    public function getInput($request)
    {
        $this->data = [
            'id'             => $request('id'),
            'kode_project'   => $request('kode_project'),
            'nama_project'   => $request('nama_project'),
            'mitra_id'       => $request('kode_mitra'), // Form menggunakan kode_mitra
            'tanggal_mulai'  => $request('tanggal_mulai'),
            'tanggal_akhir'  => $request('tanggal_akhir'),
            'files'          => $request('files'),
        ];
    }

    public function setValidationRules()
    {
        switch ($this->operation_type) {
            case 'store':
                $this->rules = [
                    [
                        'field' => 'kode_project',
                        'label' => __('Kode Project'),
                        'rules' => 'required|unique:data_project,kode_project'
                    ],
                    [
                        'field' => 'nama_project',
                        'label' => __('Nama Project'),
                        'rules' => 'required'
                    ],
                    [
                        'field' => 'kode_mitra',
                        'label' => __('Mitra'),
                        'rules' => 'required|exists:data_mitra,id'
                    ],
                    [
                        'field' => 'tanggal_mulai',
                        'label' => __('Tanggal Mulai'),
                        'rules' => 'required|date'
                    ],
                    [
                        'field' => 'tanggal_akhir',
                        'label' => __('Tanggal Akhir'),
                        'rules' => 'required|date|after_or_equal:tanggal_mulai'
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
                        'field' => 'nama_project',
                        'label' => __('Nama Project'),
                        'rules' => 'required'
                    ],
                    [
                        'field' => 'kode_mitra',
                        'label' => __('Mitra'),
                        'rules' => 'required|exists:data_mitra,id'
                    ],
                    [
                        'field' => 'tanggal_mulai',
                        'label' => __('Tanggal Mulai'),
                        'rules' => 'required|date'
                    ],
                    [
                        'field' => 'tanggal_akhir',
                        'label' => __('Tanggal Akhir'),
                        'rules' => 'required|date|after_or_equal:tanggal_mulai'
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

            case 'import':
                $this->rules = [
                    [
                        'field' => 'files',
                        'label' => __('File'),
                        'rules' => 'required|mimes:xlsx|max:5120'
                    ]
                ];
                break;

            case 'export_pdf':
            case 'export_excel':
            case 'export_word':
                $this->rules = []; // tidak butuh rules khusus
                break;

            default:
                $this->rules = [];
        }
    }

    /**
     * Ambil daftar mitra untuk dropdown
     */
    public function getMitraOptions()
    {
        return Datamitra::pluck('nama_mitra', 'id')->toArray();
    }
}
