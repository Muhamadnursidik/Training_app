<?php
namespace App\Modules\Tools\Upload;

use App\Bases\BaseProcessor;
use App\Modules\Tools\Upload\Service;
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
                case 'Upload':
                    $this->output = $this->service->Upload($data);
                    break;
                case 'get':
                    $this->output = $this->service->get($data);
                    break;
                case 'destroy':
                    $this->output = $this->service->destroy($data);
                    break;
                case 'download':
                    $this->output = $this->service->download($data);
                    break;
            }

            return true;
        } catch (Exception $e) {
            $this->output = $e;
            return false;
        }
    }
}
