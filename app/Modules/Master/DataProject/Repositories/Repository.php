<?php

namespace App\Modules\Master\DataProject\Repositories;

use App\Modules\Master\DataProject\Models\DataProject;
use App\Modules\Master\DataProject\Services\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Repository
{
    private $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function startProcess(string $action, Request $request)
    {
        return match ($action) {
            'data'         => $this->data($request),
            'store'        => $this->store($request),
            'update'       => $this->update($request),
            'destroy'      => $this->destroy($request),
            'destroys'     => $this->destroys($request),
            'export_pdf'   => $this->exportPdf($request),
            'export_excel' => $this->exportExcel($request),
            'export_word'  => $this->exportWord($request),
            default        => ['status' => false, 'message' => 'Action not found'],
        };
    }

    private function data(Request $request)
    {
        $query = DataProject::with('mitra'); // ✅ join relasi mitra

        if ($request->filled('kode_project')) {
            $query->where('kode_project', 'like', "%{$request->kode_project}%");
        }

        if ($request->filled('nama_project')) {
            $query->where('nama_project', 'like', "%{$request->nama_project}%");
        }

        if ($request->filled('mitra_id')) {
            $query->whereHas('mitra', function ($q) use ($request) {
                $q->where('nama_mitra', 'like', "%{$request->mitra_id}%");
            });
        }

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_mulai', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('tanggal_akhir', '<=', $request->tanggal_akhir);
        }

        $start = $request->get('start', 0);
        $length = $request->get('length', 10);
        $draw = $request->get('draw', 1);

        $totalRecords = $query->count();

        $data = $query->skip($start)
            ->take($length)
            ->get()
            ->map(function ($item) {
                return [
                    'id'                => encrypt($item->id),
                    'kode_project'      => $item->kode_project,
                    'nama_project'      => $item->nama_project,
                    'kode_mitra_display' => $item->mitra?->kode_mitra,
                    'tanggal_mulai'     => $item->tanggal_mulai,
                    'tanggal_akhir'     => $item->tanggal_akhir,
                    'DT_RowId'          => encrypt($item->id),
                ];
            });
        return [
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $data
        ];
    }


    private function store(Request $request)
    {
        try {
            // Validasi sederhana
            if ($request->tanggal_mulai && $request->tanggal_akhir && $request->tanggal_mulai > $request->tanggal_akhir) {
                return [
                    'status'  => false,
                    'message' => 'Tanggal mulai tidak boleh lebih besar dari tanggal akhir'
                ];
            }

            $data = DataProject::create([
                'id'             => (string) Str::uuid(),
                'kode_project'   => $request->kode_project,
                'mitra_id'       => $request->mitra_id,
                'nama_project'   => $request->nama_project,
                'tanggal_mulai'  => $request->tanggal_mulai,
                'tanggal_akhir'  => $request->tanggal_akhir,
            ]);

            return [
                'status'  => true,
                'message' => 'Data project berhasil disimpan',
                'data'    => $data
            ];
        } catch (\Exception $e) {
            return [
                'status'  => false,
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ];
        }
    }

    private function update(Request $request)
    {
        try {
            $data = DataProject::findOrFail($request->id);

            if ($request->tanggal_mulai && $request->tanggal_akhir && $request->tanggal_mulai > $request->tanggal_akhir) {
                return [
                    'status'  => false,
                    'message' => 'Tanggal mulai tidak boleh lebih besar dari tanggal akhir'
                ];
            }

            $data->update([
                'kode_project'   => $request->kode_project,
                'mitra_id'       => $request->mitra_id,
                'nama_project'   => $request->nama_project,
                'tanggal_mulai'  => $request->tanggal_mulai,
                'tanggal_akhir'  => $request->tanggal_akhir,
            ]);

            return [
                'status'  => true,
                'message' => 'Data project berhasil diperbarui',
                'data'    => $data
            ];
        } catch (\Exception $e) {
            return [
                'status'  => false,
                'message' => 'Gagal mengupdate data: ' . $e->getMessage()
            ];
        }
    }

    private function destroy(Request $request)
    {
        try {
            $data = DataProject::findOrFail($request->id);

            // Rule bisnis: tidak bisa dihapus kalau masih punya relasi dengan DataMitra
            if ($data->mitra) {
                return [
                    'status'  => false,
                    'message' => 'Project tidak bisa dihapus karena masih terkait dengan Mitra'
                ];
            }

            $data->forceDelete(); // Hard delete

            return [
                'status'  => true,
                'message' => 'Data project berhasil dihapus'
            ];
        } catch (\Exception $e) {
            return [
                'status'  => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ];
        }
    }

    private function destroys(Request $request)
    {
        try {
            $ids = [];
            if ($request->has('ids')) {
                foreach ($request->ids as $encryptedId) {
                    try {
                        $ids[] = decrypt($encryptedId);
                    } catch (\Exception $e) {
                        continue;
                    }
                }
            }

            if (empty($ids)) {
                return [
                    'status'  => false,
                    'message' => 'Tidak ada data yang valid untuk dihapus'
                ];
            }

            $projects = DataProject::whereIn('id', $ids)->get();

            foreach ($projects as $project) {
                if ($project->mitra) {
                    return [
                        'status'  => false,
                        'message' => "Project {$project->nama_project} tidak bisa dihapus karena masih terkait dengan Mitra"
                    ];
                }
                $project->forceDelete();
            }

            return [
                'status'  => true,
                'message' => 'Data project terpilih berhasil dihapus'
            ];
        } catch (\Exception $e) {
            return [
                'status'  => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ];
        }
    }

    private function exportPdf(Request $request)
    {
        try {
            $filters = $request->only(['kode_project', 'nama_project', 'mitra_id']);
            return $this->service->exportPdf($filters);
        } catch (\Exception $e) {
            return [
                'status'  => false,
                'message' => 'Gagal export PDF: ' . $e->getMessage()
            ];
        }
    }

    private function exportExcel(Request $request)
    {
        try {
            $filters = $request->only(['kode_project', 'nama_project', 'mitra_id']);
            return $this->service->exportExcel($filters);
        } catch (\Exception $e) {
            return [
                'status'  => false,
                'message' => 'Gagal export Excel: ' . $e->getMessage()
            ];
        }
    }

    private function exportWord(Request $request)
    {
        try {
            $filters = $request->only(['kode_project', 'nama_project', 'mitra_id']);
            return $this->service->exportWord($filters);
        } catch (\Exception $e) {
            return [
                'status'  => false,
                'message' => 'Gagal export Word: ' . $e->getMessage()
            ];
        }
    }
    
}
