<?php
namespace App\Modules\Master\Penyesuaianrencanaproject\Services;

use App\Bases\BaseService;
use App\Modules\Master\Penyesuaianrencanaproject\Models\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class Service extends BaseService
{
    public function __construct()
    {
    }

    public function data(array $data)
    {
        $query = Model::with(['parent'])->data();

        return DataTables::of($query)
            ->filter(function ($query) use ($data) {
                if (!empty($data['kode_project'])) {
                    $query->where('kode_project', $data['kode_project']);
                }
                if (!empty($data['aktivitas'])) {
                    $query->whereRaw('LOWER(aktivitas) LIKE ?', ['%' . strtolower($data['aktivitas']) . '%']);
                }
                if (!empty($data['level'])) {
                    $query->where('level', $data['level']);
                }
                if (!empty($data['minggu_ke'])) {
                    $query->where('minggu_ke', $data['minggu_ke']);
                }
                if (!empty($data['tanggal_mulai']) && !empty($data['tanggal_akhir'])) {
                    $query->whereBetween('tanggal_mulai', [$data['tanggal_mulai'], $data['tanggal_akhir']]);
                } elseif (!empty($data['tanggal_mulai'])) {
                    $query->where('tanggal_mulai', '>=', $data['tanggal_mulai']);
                } elseif (!empty($data['tanggal_akhir'])) {
                    $query->where('tanggal_akhir', '<=', $data['tanggal_akhir']);
                }
            })
            ->addColumn('id', fn($query) => encrypt($query->id))
            ->editColumn('aktivitas', function ($row) {
                $indent = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $row->level - 1);
                return $indent . $row->aktivitas;
            })
            ->editColumn('parent.aktivitas', fn($row) => $row->parent ? $row->parent->aktivitas : '-')
            ->editColumn('bobot', fn($row) => $row->bobot ? $row->bobot . '%' : '0%')
            ->editColumn('tanggal_mulai', fn($row) => $row->tanggal_mulai ? $row->tanggal_mulai->format('d/m/Y') : '-')
            ->editColumn('tanggal_akhir', fn($row) => $row->tanggal_akhir ? $row->tanggal_akhir->format('d/m/Y') : '-')
            ->editColumn('minggu_ke', fn($row) => $row->minggu_ke ?: '-')
            ->rawColumns(['aktivitas', 'action'])
            ->make(true)
            ->getData(true);
    }

    public function store(array $data)
    {
        if (empty($data['minggu_ke']) && !empty($data['tanggal_mulai'])) {
            $data['minggu_ke'] = Carbon::parse($data['tanggal_mulai'])->weekOfYear;
        }

        if (!empty($data['parent_id'])) {
            $parent = Model::find($data['parent_id']);
            if (!$parent) {
                throw new \Exception('Parent yang dipilih tidak ditemukan.');
            }
            $data['level'] = $parent->level + 1;
        } else {
            $data['level'] = 1;
        }

        return DB::transaction(fn() => Model::create($data));
    }

    public static function get($id)
    {
        try {
            $query = Model::find($id);
            return $query ?: false;
        } catch (\Exception $e) {
            Log::error('Error getting data: ' . $e->getMessage());
            return false;
        }
    }

    public function update(array $data)
    {
        $model = Model::findOrFail($data['id']);

        if (!empty($data['parent_id']) && $data['parent_id'] == $data['id']) {
            throw new \Exception('Parent tidak boleh sama dengan data itu sendiri');
        }

        if (!empty($data['parent_id'])) {
            $this->validateCircularReference($data['id'], $data['parent_id']);
            $parent = Model::find($data['parent_id']);
            if ($parent) {
                $data['level'] = $parent->level + 1;
            }
        } else {
            $data['level'] = 1;
        }

        if (!empty($data['tanggal_mulai']) && empty($data['minggu_ke'])) {
            $data['minggu_ke'] = Carbon::parse($data['tanggal_mulai'])->weekOfYear;
        }

        $model->update($data);
        $this->updateChildrenLevel($model);

        return $model;
    }

    public function destroy(array $data)
    {
        $model = Model::findOrFail($data['id']);
        if ($model->children()->exists()) {
            throw new \Exception('Data tidak dapat dihapus karena masih memiliki sub-aktivitas');
        }
        return $model->delete();
    }

    public function destroys(array $data)
    {
        $models = Model::whereIn('id', $data['id'])->get();
        foreach ($models as $model) {
            if ($model->children()->exists()) {
                throw new \Exception('Data "' . $model->aktivitas . '" tidak dapat dihapus karena masih memiliki sub-aktivitas');
            }
        }
        return Model::whereIn('id', $data['id'])->delete();
    }

    public function restore(array $data)
    {
        return Model::withTrashed()->findOrFail($data['id'])->restore();
    }

    public function getParentOptions($excludeId = null)
    {
        $query = Model::select('id', 'aktivitas', 'level', 'kode_project')
            ->orderBy('kode_project')
            ->orderBy('level')
            ->orderBy('aktivitas');

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->get()->map(function ($item) {
            $indent = str_repeat('-- ', $item->level - 1);
            return [
                'id'    => $item->id,
                'text'  => $indent . $item->aktivitas . ' (' . $item->kode_project . ')',
                'level' => $item->level,
            ];
        });
    }

    public function getByProject($kodeProject)
    {
        return Model::with(['parent', 'children'])
            ->where('kode_project', $kodeProject)
            ->orderBy('level')
            ->orderBy('tanggal_mulai')
            ->get();
    }

    public function getTotalBobot($kodeProject)
    {
        return Model::where('kode_project', $kodeProject)->sum('bobot');
    }

    private function validateCircularReference($currentId, $parentId)
    {
        $visited = [];
        $current = $parentId;
        while ($current && !in_array($current, $visited)) {
            if ($current == $currentId) {
                throw new \Exception('Parent tidak boleh menjadi child dari data ini (circular reference)');
            }
            $visited[] = $current;
            $parent    = Model::find($current);
            $current   = $parent ? $parent->parent_id : null;
        }
    }

    private function updateChildrenLevel($model)
    {
        foreach ($model->children as $child) {
            $child->update(['level' => $model->level + 1]);
            $this->updateChildrenLevel($child);
        }
    }
}
