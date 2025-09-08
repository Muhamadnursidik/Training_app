<?php

namespace App\Modules\Master\Dataliburnasional;

use App\Bases\BaseModule;
use Illuminate\Http\Request;
use App\Modules\Master\Dataliburnasional\Repository;
use App\Modules\Master\Dataliburnasional\Service;
use Illuminate\Support\Facades\Log;

class Controller extends BaseModule
{
    private $repo;
    protected $service;

    public function __construct(Repository $repo)
    {
        $this->repo   = $repo;
        $this->service = new Service();
        $this->module = 'master.dataliburnasional';
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
    try {
        $service = new Service();
        $result = $service->store($request->all());
        
        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

    public function edit($id)
    {
        $data = Service::get(decrypt($id));
        return $this->serveView([
            'data' => $data
        ]);
    }

 public function update(Request $request, $id)
    {
        try {
            // Validate request
            $request->validate([
                'tanggal' => 'required|date',
                'keterangan' => 'required|string|max:1000'
            ]);

            // Prepare data
            $data = [
                'id' => $id,
                'tanggal' => $request->tanggal,
                'keterangan' => $request->keterangan
            ];

            // Update data
            $result = $this->service->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diupdate',
                'data' => $result
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Update field specific
    public function updateField(Request $request, $id)
    {
        try {
            $field = $request->input('field');
            $value = $request->input('value');

            $result = $this->service->updateField($id, $field, $value);

            return response()->json([
                'success' => true,
                'message' => 'Field berhasil diupdate',
                'data' => $result
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Batch update
    public function batchUpdate(Request $request)
    {
        try {
            $dataArray = $request->input('data', []);
            $result = $this->service->batchUpdate($dataArray);

            return response()->json([
                'success' => true,
                'message' => "{$result} data berhasil diupdate",
                'updated_count' => $result
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

public function destroy($id)
{
    $processor = new Processor(new Service());
    if ($processor->setProcessor('destroy', ['id' => $id])) {
        return response()->json(['success' => true, 'data' => $processor->output ?? null]);
    }
    return response()->json(['success' => false, 'message' => $processor->output ?? 'Proses gagal']);
}


    public function destroys(Request $request)
    {
        $result = $this->repo->startProcess('destroys', $request);
        return $this->serveJSON($result);
    }

public function restore(Request $request, $id)
{
    // Konsisten dengan destroy
    $request->merge(['id' => decrypt($id)]);
    $result = $this->repo->startProcess('restore', $request);
    return $this->serveJSON($result);
}
}
