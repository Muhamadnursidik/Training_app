<?php

namespace App\Modules\Master\DataProject\Processors;

use App\Bases\BaseProcessor;
use App\Modules\Master\DataProject\Services\Service;
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
                    $this->output = $this->service->get($data['id'] ?? null);
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

                case 'export_pdf':
                    $this->output = $this->service->exportPdf($data);
                    break;

                case 'export_excel':
                    $this->output = $this->service->exportExcel($data);
                    break;

                case 'export_word':
                    $this->output = $this->service->exportWord($data);
                    break;

                default:
                    throw new Exception("Operation type {$operation_type} tidak dikenali.");
            }

            return true;
        } catch (Exception $e) {
            $this->output = $e;
            return false;
        }
    }
}
