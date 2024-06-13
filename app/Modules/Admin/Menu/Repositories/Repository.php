<?php
namespace App\Modules\Admin\Menu;

use App\Bases\BaseRepository;
use App\Modules\Admin\Menu\Processor;
use Illuminate\Validation\Rule;
use App\Modules\Admin\Menu\Rules\UniquePermision;
use App\Modules\Admin\Menu\Rules\UniquePermisionName;

class Repository extends BaseRepository
{

    public function __construct(Processor $processor)
    {
        $this->processor = $processor;
    }

    public function getInput($request) {
        $this->data = [
            'id'             => $request('_id'),
            'name'           => $request('name'),
            'custom_url'     => $request('custom_url'),
            'route'          => $request('route'),
            'url'            => $request('url'),
            'description'    => $request('description'),
            'icon'           => $request('icon'),
            'parent_id'      => $request('parent_id'),
            'data_authority' => $request('data_authority'),
            'category'       => $request('category'),
            'features'       => $request('features'),
            'sequence'       => $request('sequence'),
            'actions_permission'       => $request('actions_permission')
        ];

    }

    public function setValidationRules() {

        switch ($this->operation_type) {
        case 'store':
            $this->rules = [
                [
                    'field' => 'name',
                    'label' => __('Nama'),
                    'rules' => 'required'
                ],
                [
                    'field' => 'route',
                    'label' => __('Route'),
                    'rules' => [
                        Rule::requiredIf(empty($this->data['custom_url']))
                    ]
                ],
                [
                    'field' => 'url',
                    'label' => __('URL'),
                    'rules' => [
                        Rule::requiredIf(!empty($this->data['custom_url']))
                    ]
                ],
                [
                    'field' => 'features.*.name',
                    'label' => __('Nama Fitur'),
                    'rules' => [
                        Rule::requiredIf(!empty($this->data['features'])),
                        new UniquePermisionName($this->getData())
                    ]
                ],
                [
                    'field' => 'features.*.permission_id',
                    'label' => __('Kode Permission'),
                    'rules' => [
                        Rule::requiredIf(!empty($this->data['features'])),
                        new UniquePermision($this->getData())
                    ]
                ]
            ];

            break;
        case 'update':
            $this->rules = [
                [
                    'field' => 'id',
                    'label' => __('ID'),
                    'rules' => 'required'
                ],
                [
                    'field' => 'name',
                    'label' => __('Nama'),
                    'rules' => 'required'
                ],
                [
                    'field' => 'route',
                    'label' => __('Route'),
                    'rules' => [
                        Rule::requiredIf(empty($this->data['custom_url']))
                    ]
                ],
                [
                    'field' => 'url',
                    'label' => __('URL'),
                    'rules' => [
                        Rule::requiredIf(!empty($this->data['custom_url']))
                    ]
                ],
                [
                    'field' => 'features.*.name',
                    'label' => __('Nama Fitur'),
                    'rules' => [
                        Rule::requiredIf(!empty($this->data['features'])),
                        new UniquePermisionName($this->getData())
                    ]
                ],
                [
                    'field' => 'features.*.permission_id',
                    'label' => __('Kode Permission'),
                    'rules' => [
                        Rule::requiredIf(!empty($this->data['features'])),
                        new UniquePermision($this->getData())
                    ]
                ]
            ];

            break;
        case 'destroy':
            $this->rules = [
                [
                    'field' => 'id',
                    'label' => __('ID'),
                    'rules' => 'required'
                ]
            ];

            break;
        case 'destroys':
            $this->rules = [
                [
                    'field' => 'id',
                    'label' => __('ID'),
                    'rules' => 'required|array'
                ],
                [
                    'field' => 'id.*',
                    'label' => __('ID'),
                    'rules' => 'required'
                ]
            ];

            break;
        case 'save-order':
            $this->rules = [
                [
                    'field' => 'sequence',
                    'label' => __('Menu'),
                    'rules' => 'required'
                ]
            ];

            break;
        default:
            $this->rules = [];
        }

    }

}
