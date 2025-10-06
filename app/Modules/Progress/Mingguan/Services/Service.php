<?php
namespace App\Modules\Progress\Mingguan\Services;

use App\Bases\BaseService;
use App\Modules\Progress\Mingguan\Models\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class Service extends BaseService
{
    public function __construct()
    {
    }

    /**
     * Data untuk DataTables
     */
    public function data(array $data)
    {
        $query = Model::query()->with(['project'])->select('*');

        return DataTables::of($query)
            ->filter(function ($query) use ($data) {
                if (!empty($data['kode_project'])) {
                    $query->where('kode_project', $data['kode_project']);
                }
                if (!empty($data['aktivitas'])) {
                    $query->whereRaw('LOWER(aktivitas) LIKE ?', ['%' . strtolower($data['aktivitas']) . '%']);
                }
                if (!empty($data['minggu_ke'])) {
                    $query->where('minggu_ke', $data['minggu_ke']);
                }
                if (!empty($data['tanggal_realisasi'])) {
                    $query->whereDate('tanggal_realisasi', $data['tanggal_realisasi']);
                }
            })
            ->addColumn('id', fn($row) => encrypt($row->id))
            ->editColumn('realisasi', fn($row) => $row->realisasi ? $row->realisasi . '%' : '0%')
            ->editColumn('tanggal_realisasi', fn($row) => $row->tanggal_realisasi ? Carbon::parse($row->tanggal_realisasi)->format('d/m/Y') : '-')
            ->rawColumns(['aktivitas', 'action'])
            ->make(true)
            ->getData(true);
    }

    /**
     * Store data baru
     */
    public function store(array $data)
    {
        if (empty($data['minggu_ke']) && !empty($data['tanggal_realisasi'])) {
            $data['minggu_ke'] = Carbon::parse($data['tanggal_realisasi'])->weekOfYear;
        }

        return DB::transaction(fn() => Model::create($data));
    }

    /**
     * Get by id
     */
    public static function get($id)
    {
        try {
            $query = Model::find($id);
            return $query ?: false;
        } catch (\Exception $e) {
            Log::error('Error get data: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Update data
     */
    public function update(array $data)
    {
        $model = Model::findOrFail($data['id']);

        if (empty($data['minggu_ke']) && !empty($data['tanggal_realisasi'])) {
            $data['minggu_ke'] = Carbon::parse($data['tanggal_realisasi'])->weekOfYear;
        }

        $model->update($data);
        return $model;
    }

    /**
     * Soft delete
     */
    public function destroy(array $data)
    {
        $model = Model::findOrFail($data['id']);
        return $model->delete();
    }

    /**
     * Bulk delete
     */
    public function destroys(array $data)
    {
        return Model::whereIn('id', $data['id'])->delete();
    }

    /**
     * Restore soft delete
     */
    public function restore(array $data)
    {
        return Model::withTrashed()->findOrFail($data['id'])->restore();
    }

    /**
     * Get semua data by project
     */
    public function getByProject($kodeProject)
    {
        return Model::where('kode_project', $kodeProject)
            ->orderBy('minggu_ke')
            ->get();
    }

    /**
     * Hitung total realisasi by project
     */
    public function getTotalRealisasi($kodeProject)
    {
        return Model::where('kode_project', $kodeProject)->avg('realisasi');
    }

    /**
     * Get semua data by minggu ke
     */
    public function getByWeek($minggu)
    {
        return Model::where('minggu_ke', $minggu)
            ->orderBy('tanggal_realisasi', 'desc')
            ->get();
    }

    /**
     * Get semua data by tanggal range
     */
    public function getByDateRange($start, $end)
    {
        return Model::whereBetween('tanggal_realisasi', [$start, $end])
            ->orderBy('tanggal_realisasi', 'desc')
            ->get();
    }
}
