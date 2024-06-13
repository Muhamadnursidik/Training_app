<?php
namespace App\Modules\Tools\Pengaturan;

use App\Bases\BaseProcessor;
use App\Modules\Tools\Pengaturan\Service;
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
            }

            return true;
        } catch (Exception $e) {
            $this->output = $e;
            return false;
        }
    }
}
