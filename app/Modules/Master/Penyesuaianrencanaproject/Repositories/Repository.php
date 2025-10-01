<?php

namespace App\Modules\Master\Penyesuaianrencanaproject\Repositories;

use App\Bases\BaseRepository;
use App\Modules\Master\Penyesuaianrencanaproject\Processors\Processor;

class Repository extends BaseRepository
{
    protected $processor;

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
            'id'            => $request->input('id'),
            'kode_addendum' => $request->input('kode_addendum'),
            'kode_project'  => $request->input('kode_project'),
            'aktivitas'     => $request->input('aktivitas'),
            'level'         => $request->input('level'),
            'parent_id'     => $request->input('parent_id'),
            'bobot'         => $request->input('bobot'),
            'tanggal_mulai' => $request->input('tanggal_mulai'),
            'tanggal_akhir' => $request->input('tanggal_akhir'),
            'minggu_ke'     => $request->input('minggu_ke'),
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
                    ['field' => 'kode_addendum', 'label' => __('Kode Addendum'), 'rules' => 'required|string|max:50'],
                    ['field' => 'kode_project',  'label' => __('Kode Project'),  'rules' => 'required|string|max:50'],
                    ['field' => 'aktivitas',     'label' => __('Aktivitas'),     'rules' => 'required|string|max:255'],
                    ['field' => 'tanggal_mulai', 'label' => __('Tanggal Mulai'), 'rules' => 'required|date'],
                    ['field' => 'tanggal_akhir', 'label' => __('Tanggal Akhir'), 'rules' => 'required|date|after_or_equal:tanggal_mulai'],
                    ['field' => 'level',         'label' => __('Level'),         'rules' => 'nullable|integer|min:1'],
                    ['field' => 'bobot',         'label' => __('Bobot'),         'rules' => 'nullable|numeric|min:0|max:100'],
                ];
                break;

            case 'update':
                $this->rules = [
                    ['field' => 'id',            'label' => __('ID'),            'rules' => 'required|exists:penyesuaian_rencana_projects,id'],
                    ['field' => 'kode_addendum', 'label' => __('Kode Addendum'), 'rules' => 'required|string|max:50'],
                    ['field' => 'kode_project',  'label' => __('Kode Project'),  'rules' => 'required|string|max:50'],
                    ['field' => 'aktivitas',     'label' => __('Aktivitas'),     'rules' => 'required|string|max:255'],
                    ['field' => 'tanggal_mulai', 'label' => __('Tanggal Mulai'), 'rules' => 'required|date'],
                    ['field' => 'tanggal_akhir', 'label' => __('Tanggal Akhir'), 'rules' => 'required|date|after_or_equal:tanggal_mulai'],
                    ['field' => 'level',         'label' => __('Level'),         'rules' => 'nullable|integer|min:1'],
                    ['field' => 'bobot',         'label' => __('Bobot'),         'rules' => 'nullable|numeric|min:0|max:100'],
                ];
                break;

            case 'destroy':
                $this->rules = [
                    ['field' => 'id', 'label' => __('ID'), 'rules' => 'required|exists:penyesuaian_rencana_projects,id'],
                ];
                break;

            case 'destroys':
                $this->rules = [
                    ['field' => 'id',   'label' => __('ID'), 'rules' => 'required|array|min:1'],
                    ['field' => 'id.*', 'label' => __('ID'), 'rules' => 'required|exists:penyesuaian_rencana_projects,id'],
                ];
                break;

            case 'restore':
                $this->rules = [
                    ['field' => 'id', 'label' => __('ID'), 'rules' => 'required|exists:penyesuaian_rencana_projects,id'],
                ];
                break;

            default:
                $this->rules = [];
        }
    }
}
