<?php
namespace App\Modules\Admin\Menu;

use App\Bases\BaseProcessor;
use App\Modules\Admin\Menu\Service;

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
                case 'save-order':
                    $this->output = $this->service->saveOrder($data);
                    break;
            }

            return true;
        } catch (Exception $e) {
            $this->output = $e;
            return false;
        }
    }
}
