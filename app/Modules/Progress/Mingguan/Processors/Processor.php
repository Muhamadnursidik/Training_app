<?php

namespace App\Modules\Progress\Mingguan\Processors;

use App\Bases\BaseProcessor;
use App\Modules\Progress\Mingguan\Services\Service;
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

                case 'by_project':
                    $kodeProject = $data['kode_project'] ?? null;
                    if (!$kodeProject) {
                        throw new Exception("Parameter 'kode_project' diperlukan");
                    }
                    $this->output = $this->service->getByProject($kodeProject);
                    break;

                case 'by_week':
                    $minggu = $data['minggu_ke'] ?? null;
                    if (!$minggu) {
                        throw new Exception("Parameter 'minggu_ke' diperlukan");
                    }
                    $this->output = $this->service->getByWeek($minggu);
                    break;

                case 'by_date_range':
                    $start = $data['start_date'] ?? null;
                    $end   = $data['end_date'] ?? null;
                    if (!$start || !$end) {
                        throw new Exception("Parameter 'start_date' dan 'end_date' diperlukan");
                    }
                    $this->output = $this->service->getByDateRange($start, $end);
                    break;

                default:
                    throw new Exception("Operation type [$operation_type] tidak dikenali.");
            }

            return true;
        } catch (Exception $e) {
            $this->output = $e;
            return false;
        }
    }
}
