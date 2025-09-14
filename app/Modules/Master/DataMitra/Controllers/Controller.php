<?php
namespace App\Modules\Master\DataMitra\Controllers;

use App\Bases\BaseModule;
use Illuminate\Http\Request;
use App\Modules\Master\DataMitra\Repositories\Repository;
use App\Modules\Master\DataMitra\Models\Datamitra;

class Controller extends BaseModule
{
    private $repo;

    public function __construct(Repository $repo)
    {
        $this->repo   = $repo;
        $this->module = 'master.datamitra';
        parent::__construct();
    }

    public function index()
    {
        activity('Akses menu')->log('Akses menu ' . $this->pageTitle);
        return $this->serveView();
    }

    public function data(Request $request)
    {
        $result = $this->repo->startProcess('data', $request);
        return $this->serveJSON($result);
    }

    public function create()
    {
        return $this->serveView();
    }

    public function store(Request $request)
    {
        $result = $this->repo->startProcess('store', $request);
        return $this->serveJSON($result);
    }

    public function edit($id)
    {
        try {
            $decryptedId = decrypt($id);
            $data = Datamitra::findOrFail($decryptedId);
            return $this->serveView(['data' => $data]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan: ' . $e->getMessage()
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $decryptedId = decrypt($id);
            $request->merge(['id' => $decryptedId]);
            $result = $this->repo->startProcess('update', $request);
            return $this->serveJSON($result);
        } catch (\Exception $e) {
            return $this->serveJSON([
                'status' => false,
                'message' => 'Gagal decrypt ID: ' . $e->getMessage()
            ]);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $decryptedId = decrypt($id);
            $request->merge(['id' => $decryptedId]);
            $result = $this->repo->startProcess('destroy', $request);
            return $this->serveJSON($result);
        } catch (\Exception $e) {
            return $this->serveJSON([
                'status' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ]);
        }
    }

    public function destroys(Request $request)
    {
        $result = $this->repo->startProcess('destroys', $request);
        return $this->serveJSON($result);
    }

    public function download(Request $request)
    {
        return $this->repo->startProcess('download', $request);
    }

    public function import()
    {
        return $this->serveView([]);
    }

    public function importPost(Request $request)
    {
        $result = $this->repo->startProcess('import', $request);
        return $this->serveJSON($result);
    }

    public function exportPdf(Request $request)
    {
        try {
            return $this->repo->startProcess('export_pdf', $request);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal export PDF: ' . $e->getMessage()
            ], 500);
        }
    }

    public function exportExcel(Request $request)
    {
        try {
            return $this->repo->startProcess('export_excel', $request);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal export Excel: ' . $e->getMessage()
            ], 500);
        }
    }

    public function exportWord(Request $request)
    {
        try {
            return $this->repo->startProcess('export_word', $request);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal export Word: ' . $e->getMessage()
            ], 500);
        }
    }
}
