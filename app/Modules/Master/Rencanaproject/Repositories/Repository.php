<?php
namespace App\Modules\Master\Rencanaproject\Repositories;

use App\Bases\BaseRepository;
use App\Modules\Master\Rencanaproject\Processors\Processor;

class Repository extends BaseRepository
{
    public function __construct(Processor $processor)
    {
        $this->processor = $processor;
    }

    /**
     * Ambil input request & simpen ka $this->data
     */
    public function getInput($request)
    {
        $this->data = [
            'id'            => $request('id'),
            'kode_project'  => $request('kode_project'),
            'aktivitas'     => $request('aktivitas'),
            'level'         => $request('level'),
            'parent_id'     => $request('parent_id'),
            'bobot'         => $request('bobot'),
            'tanggal_mulai' => $request('tanggal_mulai'),
            'tanggal_akhir' => $request('tanggal_akhir'),
            'minggu_ke'     => $request('minggu_ke'),
        ];
    }

    /**
     * Set aturan validasi per operasi (store, update, destroy, dst)
     */
    public function setValidationRules()
    {
        switch ($this->operation_type) {
            case 'store':
                $this->rules = [
                    ['field' => 'kode_project',  'label' => __('Kode Project'), 'rules' => 'required|string|max:50'],
                    ['field' => 'aktivitas',     'label' => __('Aktivitas'), 'rules' => 'required|string|max:255'],
                    ['field' => 'tanggal_mulai', 'label' => __('Tanggal Mulai'),'rules' => 'required|date'],
                    ['field' => 'tanggal_akhir', 'label' => __('Tanggal Akhir'),'rules' => 'required|date|after_or_equal:tanggal_mulai'],
                    ['field' => 'level',         'label' => __('Level'), 'rules' => 'nullable|integer|min:1'],
                    ['field' => 'bobot',         'label' => __('Bobot'), 'rules' => 'nullable|numeric|min:0|max:100'],
                ];
                break;

            case 'update':
                $this->rules = [
                    ['field' => 'id',            'label' => __('ID'), 'rules' => 'required|exists:rencana_projects,id'],
                    ['field' => 'kode_project',  'label' => __('Kode Project'), 'rules' => 'required|string|max:50'],
                    ['field' => 'aktivitas',     'label' => __('Aktivitas'), 'rules' => 'required|string|max:255'],
                    ['field' => 'tanggal_mulai', 'label' => __('Tanggal Mulai'),'rules' => 'required|date'],
                    ['field' => 'tanggal_akhir', 'label' => __('Tanggal Akhir'),'rules' => 'required|date|after_or_equal:tanggal_mulai'],
                    ['field' => 'level',         'label' => __('Level'), 'rules' => 'nullable|integer|min:1'],
                    ['field' => 'bobot',         'label' => __('Bobot'), 'rules' => 'nullable|numeric|min:0|max:100'],
                ];
                break;

            case 'destroy':
                $this->rules = [
                    ['field' => 'id', 'label' => __('ID'), 'rules' => 'required|exists:rencana_projects,id']
                ];
                break;

            case 'destroys':
                $this->rules = [
                    ['field' => 'id',   'label' => __('ID'), 'rules' => 'required|array|min:1'],
                    ['field' => 'id.*', 'label' => __('ID'), 'rules' => 'required|exists:rencana_projects,id']
                ];
                break;

            case 'restore':
                $this->rules = [
                    ['field' => 'id', 'label' => __('ID'), 'rules' => 'required|exists:rencana_projects,id']
                ];
                break;

            default:
                $this->rules = [];
        }
    }
}
