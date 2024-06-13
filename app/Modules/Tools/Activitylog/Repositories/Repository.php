<?php
namespace App\Modules\Tools\Activitylog;

use App\Bases\BaseRepository;
use App\Modules\Tools\Activitylog\Processor;

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
            'keyword'     => $request('keyword'),
            'tanggal'     => $request('tanggal'),
            'user'        => $request('user'),
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
