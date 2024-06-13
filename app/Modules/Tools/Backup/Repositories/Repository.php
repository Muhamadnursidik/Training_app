<?php
namespace App\Modules\Tools\Backup;

use App\Bases\BaseRepository;
use App\Modules\Tools\Backup\Processor;

class Repository extends BaseRepository
{

    public function __construct(Processor $processor)
    {
        $this->processor = $processor;
    }

    public function getInput($request) {
        $this->data = [
            'id'          => $request('_id'),
            'name'        => $request('name'),
            'action'      => $request('action')
        ];

    }

    public function setValidationRules() {
        switch ($this->operation_type) {
        case 'backup':
            $this->rules = [];

            break;
        default:
            $this->rules = [];
        }

    }

}
