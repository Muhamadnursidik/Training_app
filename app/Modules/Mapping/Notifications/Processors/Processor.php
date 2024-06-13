<?php
namespace App\Modules\Mapping\Notifications;

use App\Bases\BaseProcessor;
use App\Modules\Mapping\Notifications\Service;
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
                case 'data':
                    $this->output = $this->service->data($data);
                break;
                case 'count':
                    $this->output = $this->service->count($data);
                break;
                case 'listData':
                    $this->output = $this->service->listData($data);
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
