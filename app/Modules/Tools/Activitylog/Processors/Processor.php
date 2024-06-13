<?php
namespace App\Modules\Tools\Activitylog;

use App\Bases\BaseProcessor;
use App\Modules\Tools\Activitylog\Service;
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
                case 'backup':
                    $this->output = $this->service->backup($data);
                    break;
                case 'backupclean':
                    $this->output = $this->service->backup($data);
                    break;
                case 'data':
                    $this->output = $this->service->data($data);
                    break;
                case 'get':
                    $this->output = $this->service->get($data);
                    break;
            }

            return true;
        } catch (Exception $e) {
            $this->output = $e;
            return false;
        }
    }
}
