<?php
namespace App\Modules\Master\Rencanaproject\Processors;

use App\Bases\BaseProcessor;
use App\Modules\Master\Rencanaproject\Services\Service;
use Exception;

class Processor extends BaseProcessor
{
    private $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function setProcessor($operation_type, array $data)
    {
        try {
            switch ($operation_type) {
                case 'store':
                    $this->output = $this->service->store($data);
                    break;

                case 'data':
                    $this->output = $this->service->data($data);
                    break;

                case 'get':
                    $this->output = $this->service->get($data);
                    break;

                case 'update':
                    $this->output = $this->service->update($data);
                    break;

                case 'destroy':
                    $this->output = $this->service->destroy($data);
                    break;

                case 'destroys':
                    $this->output = $this->service->destroys($data);
                    break;

                case 'restore':
                    $this->output = $this->service->restore($data);
                    break;

                case 'parent_options':
                    $this->output = $this->service->getParentOptions($data['exclude_id'] ?? null);
                    break;

                case 'by_project':
                    $this->output = $this->service->getByProject($data['kode_project']);
                    break;

                case 'total_bobot':
                    $this->output = $this->service->getTotalBobot($data['kode_project']);
                    break;
            }
            return true;
        } catch (Exception $e) {
            $this->output = $e;
            return false;
        }
    }
}
