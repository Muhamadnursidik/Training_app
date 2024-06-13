<?php
namespace App\Modules\Tools\Upload;

use App\Bases\BaseRepository;
use App\Modules\Tools\Upload\Processor;

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
            'action'      => $request('action'),
            'module'      => $request('module')
        ];

    }

    public function setValidationRules() {
        switch ($this->operation_type) {
        case 'Upload':
            $this->rules = [];

            break;
        default:
            $this->rules = [];
        }

    }

}
