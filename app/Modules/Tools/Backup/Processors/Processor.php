<?php
namespace App\Modules\Tools\Backup;

use App\Bases\BaseProcessor;
use App\Modules\Tools\Backup\Service;
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
                case 'destroy':
                    $this->output = $this->service->destroy($data);
                    break;
            }

            return true;
        } catch (Exception $e) {
            $this->output = $e;
            return false;
        }
    }
}
