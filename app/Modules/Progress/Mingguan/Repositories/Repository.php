<?php

namespace App\Modules\Progress\Mingguan\Repositories;

use App\Bases\BaseRepository;
use App\Modules\Progress\Mingguan\Processors\Processor;

class Repository extends BaseRepository
{
    public function __construct(Processor $processor)
    {
        $this->processor = $processor;
    }

    /**
     * Ambil input request & simpen ke $this->data
     */
    public function getInput($request)
    {
        $this->data = [
            'id'               => $request('id'),
            'kode_project'     => $request('kode_project'),
            'aktivitas'        => $request('aktivitas'),
            'minggu_ke'        => $request('minggu_ke'),
            'realisasi'        => $request('realisasi'),
            'tanggal_realisasi'=> $request('tanggal_realisasi'),
        ];
    }

    /**
     * Set aturan validasi per operasi
     */
    public function setValidationRules()
    {
        switch ($this->operation_type) {
            case 'store':
                $this->rules = [
                    ['field' => 'kode_project',      'label' => __('Kode Project'),      'rules' => 'required|string|max:50'],
                    ['field' => 'aktivitas',         'label' => __('Aktivitas'),         'rules' => 'required|string|max:255'],
                    ['field' => 'tanggal_realisasi', 'label' => __('Tanggal Realisasi'), 'rules' => 'required|date'],
                    ['field' => 'realisasi',         'label' => __('Realisasi'),         'rules' => 'required|numeric|min:0|max:100'],
                ];
                break;

            case 'update':
                $this->rules = [
                    ['field' => 'id',                'label' => __('ID'),                'rules' => 'required|exists:realisasi_rencana_mingguan,id'],
                    ['field' => 'kode_project',      'label' => __('Kode Project'),      'rules' => 'required|string|max:50'],
                    ['field' => 'aktivitas',         'label' => __('Aktivitas'),         'rules' => 'required|string|max:255'],
                    ['field' => 'tanggal_realisasi', 'label' => __('Tanggal Realisasi'), 'rules' => 'required|date'],
                    ['field' => 'realisasi',         'label' => __('Realisasi'),         'rules' => 'required|numeric|min:0|max:100'],
                ];
                break;

            case 'destroy':
                $this->rules = [
                    ['field' => 'id', 'label' => __('ID'), 'rules' => 'required|exists:realisasi_rencana_mingguan,id'],
                ];
                break;

            case 'destroys':
                $this->rules = [
                    ['field' => 'id',   'label' => __('ID'), 'rules' => 'required|array|min:1'],
                    ['field' => 'id.*', 'label' => __('ID'), 'rules' => 'required|exists:realisasi_rencana_mingguan,id'],
                ];
                break;

            case 'restore':
                $this->rules = [
                    ['field' => 'id', 'label' => __('ID'), 'rules' => 'required|exists:realisasi_rencana_mingguan,id'],
                ];
                break;

            default:
                $this->rules = [];
        }
    }
}
