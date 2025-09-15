<?php
namespace App\Modules\Master\Rencanaproject\Services;

use App\Bases\BaseService;
use App\Modules\Master\Rencanaproject\Models\Model;
// use DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
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
                if (! empty($data['kode_project'])) {
                    $query->where('kode_project', $data['kode_project']);
                }
                if (! empty($data['aktivitas'])) {
                    $query->whereRaw('LOWER(aktivitas) LIKE ?', ['%' . strtolower($data['aktivitas']) . '%']);
                }
                if (! empty($data['level'])) {
                    $query->where('level', $data['level']);
                }
                if (! empty($data['minggu_ke'])) {
                    $query->where('minggu_ke', $data['minggu_ke']);
                }
                if (! empty($data['tanggal_mulai']) && ! empty($data['tanggal_akhir'])) {
                    $query->whereBetween('tanggal_mulai', [$data['tanggal_mulai'], $data['tanggal_akhir']]);
                } elseif (! empty($data['tanggal_mulai'])) {
                    $query->where('tanggal_mulai', '>=', $data['tanggal_mulai']);
                } elseif (! empty($data['tanggal_akhir'])) {
                    $query->where('tanggal_akhir', '<=', $data['tanggal_akhir']);
                }
            })
            ->addColumn('id', function ($query) {
                return encrypt($query->id);
            })
            ->editColumn('aktivitas', function ($row) {
                $indent = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $row->level - 1);
                return $indent . $row->aktivitas;
            })
            ->editColumn('parent.aktivitas', function ($row) {
                return $row->parent ? $row->parent->aktivitas : '-';
            })
            ->editColumn('bobot', function ($row) {
                return $row->bobot ? $row->bobot . '%' : '0%';
            })
            ->editColumn('tanggal_mulai', function ($row) {
                return $row->tanggal_mulai ? $row->tanggal_mulai->format('d/m/Y') : '-';
            })
            ->editColumn('tanggal_akhir', function ($row) {
                return $row->tanggal_akhir ? $row->tanggal_akhir->format('d/m/Y') : '-';
            })
            ->editColumn('minggu_ke', function ($row) {
                return $row->minggu_ke ?: '-';
            })
            ->rawColumns(['aktivitas', 'action'])
            ->make(true)
            ->getData(true);
    }

    /**
     * Simpan data baru
     */
    public function store(array $data)
    {
        if (empty($data['minggu_ke']) && ! empty($data['tanggal_mulai'])) {
            $data['minggu_ke'] = Carbon::parse($data['tanggal_mulai'])->weekOfYear;
        }

        if (! empty($data['parent_id'])) {
            $parent = Model::find($data['parent_id']);
            if (! $parent) {
                throw new \Exception('Parent yang dipilih tidak ditemukan.');
            }
            $data['level'] = $parent->level + 1;
        } else {
            $data['level'] = 1; // Root level
        }

        return DB::transaction(function () use ($data) {
            return Model::create($data);
        });
    }

    /**
     * Ambil data berdasarkan ID
     */
    public function get($id)
    {
        return Model::with(['parent'])->findOrFail($id);
    }

    /**
     * Update data
     */
    public function update(array $data)
    {
        $model = Model::findOrFail($data['id']);

        // Validasi parent_id tidak boleh sama dengan dirinya sendiri
        if (! empty($data['parent_id']) && $data['parent_id'] == $data['id']) {
            throw new \Exception('Parent tidak boleh sama dengan data itu sendiri');
        }

        // Validasi parent tidak boleh menjadi child dari anaknya sendiri (circular reference)
        if (! empty($data['parent_id'])) {
            $this->validateCircularReference($data['id'], $data['parent_id']);

            $parent = Model::find($data['parent_id']);
            if ($parent) {
                $data['level'] = $parent->level + 1;
            }
        } else {
            $data['level'] = 1;
        }

        // Auto update minggu_ke jika tanggal_mulai berubah
        if (! empty($data['tanggal_mulai']) && empty($data['minggu_ke'])) {
            $data['minggu_ke'] = Carbon::parse($data['tanggal_mulai'])->weekOfYear;
        }

        $model->update($data);

        // Update level untuk semua children
        $this->updateChildrenLevel($model);

        return $model;
    }

    /**
     * Hapus data
     */
    public function destroy(array $data)
    {
        $model = Model::findOrFail($data['id']);

        // Check if has children
        if ($model->children()->exists()) {
            throw new \Exception('Data tidak dapat dihapus karena masih memiliki sub-aktivitas');
        }

        return $model->delete();
    }

    /**
     * Hapus multiple data
     */
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

    /**
     * Restore data yang telah dihapus
     */
    public function restore(array $data)
    {
        return Model::withTrashed()->findOrFail($data['id'])->restore();
    }

    /**
     * Ambil opsi parent untuk dropdown
     */
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

    /**
     * Ambil data berdasarkan kode project untuk membuat hierarki
     */
    public function getByProject($kodeProject)
    {
        return Model::with(['parent', 'children'])
            ->where('kode_project', $kodeProject)
            ->orderBy('level')
            ->orderBy('tanggal_mulai')
            ->get();
    }

    /**
     * Hitung total bobot berdasarkan kode project
     */
    public function getTotalBobot($kodeProject)
    {
        return Model::where('kode_project', $kodeProject)->sum('bobot');
    }

    /**
     * Ambil statistik data
     */
    public function getStatistics()
    {
        return [
            'total_projects'        => Model::distinct('kode_project')->count(),
            'total_activities'      => Model::count(),
            'total_root_activities' => Model::where('level', 1)->count(),
            'completed_activities'  => Model::where('tanggal_akhir', '<', now())->count(),
            'ongoing_activities'    => Model::where('tanggal_mulai', '<=', now())
                ->where('tanggal_akhir', '>=', now())
                ->count(),
            'upcoming_activities'   => Model::where('tanggal_mulai', '>', now())->count(),
        ];
    }

    /**
     * Validasi circular reference untuk parent-child relationship
     */
    private function validateCircularReference($currentId, $parentId)
    {
        $visited = [];
        $current = $parentId;

        while ($current && ! in_array($current, $visited)) {
            if ($current == $currentId) {
                throw new \Exception('Parent tidak boleh menjadi child dari data ini (circular reference)');
            }

            $visited[] = $current;
            $parent    = Model::find($current);
            $current   = $parent ? $parent->parent_id : null;
        }
    }

    /**
     * Update level untuk semua children secara rekursif
     */
    private function updateChildrenLevel($model)
    {
        $children = $model->children;

        foreach ($children as $child) {
            $child->update(['level' => $model->level + 1]);
            $this->updateChildrenLevel($child);
        }
    }

    /**
     * Generate tree structure untuk tampilan hierarki
     */
    public function getTreeStructure($kodeProject = null)
    {
        $query = Model::with(['children' => function ($q) {
            $q->orderBy('tanggal_mulai')->orderBy('aktivitas');
        }]);

        if ($kodeProject) {
            $query->where('kode_project', $kodeProject);
        }

        $rootItems = $query->whereNull('parent_id')
            ->orderBy('tanggal_mulai')
            ->orderBy('aktivitas')
            ->get();

        return $this->buildTree($rootItems);
    }

    /**
     * Build tree structure secara rekursif
     */
    private function buildTree($items)
    {
        $tree = [];

        foreach ($items as $item) {
            $node = [
                'id'            => $item->id,
                'kode_project'  => $item->kode_project,
                'aktivitas'     => $item->aktivitas,
                'level'         => $item->level,
                'bobot'         => $item->bobot,
                'tanggal_mulai' => $item->tanggal_mulai,
                'tanggal_akhir' => $item->tanggal_akhir,
                'minggu_ke'     => $item->minggu_ke,
                'children'      => [],
            ];

            if ($item->children->count() > 0) {
                $node['children'] = $this->buildTree($item->children);
            }

            $tree[] = $node;
        }

        return $tree;
    }

    /**
     * Clone/duplicate aktivitas beserta sub-aktivitasnya
     */
    public function cloneActivity($id, $newKodeProject = null)
    {
        $original = Model::with('children')->findOrFail($id);

        $cloned = $original->replicate();
        if ($newKodeProject) {
            $cloned->kode_project = $newKodeProject;
        }
        $cloned->save();

        // Clone children recursively
        foreach ($original->children as $child) {
            $this->cloneChildActivity($child, $cloned->id, $newKodeProject);
        }

        return $cloned;
    }

    /**
     * Clone child activity secara rekursif
     */
    private function cloneChildActivity($original, $newParentId, $newKodeProject = null)
    {
        $cloned            = $original->replicate();
        $cloned->parent_id = $newParentId;
        if ($newKodeProject) {
            $cloned->kode_project = $newKodeProject;
        }
        $cloned->save();

        foreach ($original->children as $child) {
            $this->cloneChildActivity($child, $cloned->id, $newKodeProject);
        }

        return $cloned;
    }
}
