<?php
namespace App\Modules\Mapping\Notifications;

use App\Bases\BaseRepository;
use App\Modules\Mapping\Notifications\Processor;

class Repository extends BaseRepository
{

    public function __construct(Processor $processor)
    {
        $this->processor = $processor;
    }

    public function getInput($request) {
        $this->data = [
            'id'    => $request('_id'),
            'formData'    => $request('formData'),
            'unit_kerja_code' => $request('unit_kerja_code'),
            'keyword' => $request('keyword')
        ];

    }

    public function setValidationRules() {
        switch ($this->operation_type) {
        default:
            $this->rules = [];
        }

    }

}
