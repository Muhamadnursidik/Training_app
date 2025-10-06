<?php

namespace App\Modules\Master\DataMitra\Repositories;

use App\Modules\Master\DataMitra\Models\Datamitra;
use App\Modules\Master\DataMitra\Services\Service;
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
            'data'        => $this->data($request),
            'store'       => $this->store($request),
            'update'      => $this->update($request),
            'destroy'     => $this->destroy($request),
            'destroys'    => $this->destroys($request),
            'import'      => $this->import($request),
            'download'    => $this->download($request),
            'export_pdf'  => $this->exportPdf($request),
            'export_excel' => $this->exportExcel($request),
            'export_word' => $this->exportWord($request),
            default       => ['status' => false, 'message' => 'Action not found'],
        };
    }

    private function data(Request $request)
    {
        $query = Datamitra::query(); // Hapus withTrashed()

        if ($request->filled('kode_mitra')) {
            $query->where('kode_mitra', 'like', "%{$request->kode_mitra}%");
        }

        if ($request->filled('nama_mitra')) {
            $query->where('nama_mitra', 'like', "%{$request->nama_mitra}%");
        }

        if ($request->filled('alamat')) {
            $query->where('alamat', 'like', "%{$request->alamat}%");
        }

        $start = $request->get('start', 0);
        $length = $request->get('length', 10);
        $draw = $request->get('draw', 1);

        $totalRecords = $query->count();

        $data = $query->select(['id', 'kode_mitra', 'nama_mitra', 'alamat'])
                      ->skip($start)
                      ->take($length)
                      ->get()
                      ->map(function($item) {
                          return [
                              'id' => encrypt($item->id),
                              'kode_mitra' => $item->kode_mitra,
                              'nama_mitra' => $item->nama_mitra,
                              'alamat' => $item->alamat,
                              // Hapus 'deleted_at'
                              'DT_RowId' => encrypt($item->id),
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
            $data = Datamitra::create([
                'id'         => (string) Str::uuid(),
                'kode_mitra' => $request->kode_mitra,
                'nama_mitra' => $request->nama_mitra,
                'alamat'     => $request->alamat,
            ]);

            return [
                'status' => true,
                'message' => 'Data berhasil disimpan',
                'data' => $data
            ];
        } catch (\Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ];
        }
    }

    private function update(Request $request)
    {
        try {
            $data = Datamitra::findOrFail($request->id);
            $data->update([
                'kode_mitra' => $request->kode_mitra,
                'nama_mitra' => $request->nama_mitra,
                'alamat'     => $request->alamat,
            ]);

            return [
                'status' => true,
                'message' => 'Data berhasil diupdate',
                'data' => $data
            ];
        } catch (\Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal mengupdate data: ' . $e->getMessage()
            ];
        }
    }

    private function destroy(Request $request)
    {
        try {
            $data = Datamitra::findOrFail($request->id);
            $data->forceDelete(); // Hard delete

            return [
                'status' => true,
                'message' => 'Data berhasil dihapus'
            ];
        } catch (\Exception $e) {
            return [
                'status' => false,
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
                    'status' => false,
                    'message' => 'Tidak ada data yang valid untuk dihapus'
                ];
            }

            Datamitra::whereIn('id', $ids)->forceDelete(); // Hard delete

            return [
                'status' => true,
                'message' => 'Data terpilih berhasil dihapus'
            ];
        } catch (\Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ];
        }
    }

    private function import(Request $request)
    {
        return [
            'status' => true,
            'message' => 'Import masih dummy'
        ];
    }

    private function download(Request $request)
    {
        return [
            'status' => true,
            'message' => 'Download masih dummy'
        ];
    }

    private function exportPdf(Request $request)
    {
        try {
            $filters = $request->only(['kode_mitra', 'nama_mitra', 'alamat']);
            return $this->service->exportPdf($filters);
        } catch (\Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal export PDF: ' . $e->getMessage()
            ];
        }
    }

    private function exportExcel(Request $request)
    {
        try {
            $filters = $request->only(['kode_mitra', 'nama_mitra', 'alamat']);
            return $this->service->exportExcel($filters);
        } catch (\Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal export Excel: ' . $e->getMessage()
            ];
        }
    }

    private function exportWord(Request $request)
    {
        try {
            $filters = $request->only(['kode_mitra', 'nama_mitra', 'alamat']);
            return $this->service->exportWord($filters);
        } catch (\Exception $e) {
            return [
                'status' => false,
                'message' => 'Gagal export Word: ' . $e->getMessage()
            ];
        }
    }
}
