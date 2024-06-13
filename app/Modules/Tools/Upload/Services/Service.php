<?php

namespace App\Modules\Tools\Upload;

use App\Bases\BaseService;
use Exception;
use Illuminate\Support\Facades\Storage;
use Uuid;

class Service extends BaseService
{
    protected $storage_via;

    public function __construct()
    {
        $this->storage_via = config('app.storage_via');
    }

    public static function upload(array $data)
    {
        $storage_via = (new self)->storage_via;
        if (isset($data['files'][0]) && !empty($data['files'][0])) {
            foreach ($data['files'] as $file) {
                $oriname = $file->getClientOriginalName();

                Model::createOne([
                    'oriname'   => $oriname,
                    'source_id' => $data['source_id'],
                    'module'    => $data['module'],
                ], function ($query, $event) use ($file, $data, $storage_via) {
                    
                    if ($storage_via == 'local') {
                        $filename = Storage::disk('public')->put($data['module'], $file);
                    } elseif ($storage_via == 's3') {
                        $filename = Storage::disk('s3')->put($data['module'], $file);
                    }

                    $event->update(['name' => basename($filename)]);
                });
            }

            return true;
        }
    }

    public static function uploadNoRename(array $data)
    {
        $storage_via = (new self)->storage_via;
        if (isset($data['files'][0]) && !empty($data['files'][0])) {
            foreach ($data['files'] as $file) {
                $oriname = $file->getClientOriginalName();

                Model::createOne([
                    'oriname'   => $oriname,
                    'source_id' => $data['source_id'],
                    'module'    => $data['module'],
                ], function ($query, $event) use ($file, $data, $storage_via, $oriname) {

                    if ($storage_via == 'local') {
                        $filename = Storage::disk('public')->put($data['module'].'/'.$oriname, file_get_contents($file));
                    } elseif ($storage_via == 's3') {
                        $filename = Storage::disk('s3')->put($data['module'].'/'.$oriname, file_get_contents($file));
                    }

                    $event->update(['name' => $oriname]);
                });
            }

            return true;
        }
    }

    public static function uploadSingle(array $data)
    {
        $storage_via = (new self)->storage_via;

        if (isset($data['files']) && !empty($data['files'])) {

            //Check data
            $files = Model::where(function ($query) use ($data) {
                $query->where('source_id', $data['source_id']);
                $query->where('module', $data['module']);
            })->first();

            if ($files != null) {
                Service::destroy(array('id' => $files->id));
            }

            $file = $data['files'];

            $oriname = $file->getClientOriginalName();

            return Model::createOne([
                'oriname'   => $oriname,
                'source_id' => $data['source_id'],
                'module'    => $data['module'],
            ], function ($query, $event) use ($file, $data, $storage_via) {

                if ($storage_via == 'local') {
                    $filename = Storage::disk('public')->put($data['module'], $file);
                } elseif ($storage_via == 's3') {
                    $filename = Storage::disk('s3')->put($data['module'], $file);
                }

                $event->update(['name' => basename($filename)]);
            });
        }
    }

    public static function get(array $data)
    {
        $files = Model::where(function ($query) use ($data) {
            $query->where('source_id', $data['source_id']);
            $query->where('module', $data['module']);
        })->get();

        return $files;
    }

    public static function attachment_list(array $data)
    {
        if ($data['source_id'] && $data['module']) {
            $files = (new self)->get($data);
            return view('tools' . DIRECTORY_SEPARATOR . 'upload::list', ['files' => $files, 'action' => $data['action'], 'showLabel' => isset($data['showLabel']) ? $data['showLabel'] : true, 'source_id' => $data['source_id']])->render();
        }
    }

    public static function destroy(array $data)
    {
        $storage_via = (new self)->storage_via;
        return Model::deleteOne($data['id'], function ($query, $event, $cursor) use ($storage_via) {
            if ($storage_via == 'local') {
                Storage::disk('public')->delete($cursor->module . DIRECTORY_SEPARATOR . $cursor->name);
            } elseif ($storage_via == 's3') {
                Storage::disk('s3')->delete($cursor->module . '/' . $cursor->name);
            }
        });
    }

    public function download(array $data)
    {
        try {
            $file = Model::where(['id' => $data['id'], 'module' => $data['module']])->first();
            if ($this->storage_via == 'local') {
                $filename = $file->module . DIRECTORY_SEPARATOR . $file->name;
                // if (pathinfo($file->oriname, PATHINFO_EXTENSION) == 'pdf'){
                //     header('Content-Type: application/pdf');
                //     header('Content-Disposition: inline; filename="' .$file->oriname.'"');
                //     return view('tools' . DIRECTORY_SEPARATOR . 'upload::viewpdf', ['file' => $file]);
                // } else {
                    return Storage::disk('public')->download($filename, $file->oriname);
                // }
                // return response()->download(Storage::disk('public')->get($file->module . DIRECTORY_SEPARATOR . $file->name));
            } elseif ($this->storage_via == 's3') {
                $filename = $file->module . '/' . $file->name;
                return Storage::disk('s3')->download($filename, $file->oriname);
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public static function getViewSign(array $data)
    {
        try {
            $file = Model::where(['source_id' => $data['id']])->first();

            if ($file == null) {
                return false;
            }
            $storage_via = (new self)->storage_via;
            if ($storage_via == 'local') {
                return Storage::disk('public')->url($file->module . DIRECTORY_SEPARATOR . $file->name);
            } elseif ($storage_via == 's3') {
                $url = Storage::disk('s3')->temporaryUrl($file->module . '/' . $file->name,now()->addHour(100));
                return $url;
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
