<?php
namespace App\Bases;

use Validator;
use Illuminate\Http\Request;

abstract class BaseRepository
{
    protected $processor;
    protected $data;
    protected $rules;
    protected $errors;
    protected $operation_type;
    protected $allowed_from_xss = [];

    public function __construct()
    {
    }

    abstract public function getInput($request);

    abstract public function setValidationRules();

    public function validate(Request $request) {

        $request = $this->cleanFromXss($request);

        $this->getInput(function($name, $default = NULL) use ($request){
            return isset($request[$name]) ? $request[$name] : $default;
        });

        $this->setValidationRules();

        if ($this->rules) {
            $rules      = [];
            $attributes = [];
            $messages   = [];

            foreach ($this->rules as $rule) {
                if (isset($rule['field'])) {
                    $rules[$rule['field']] = $rule['rules'];
                    $attributes[$rule['field']] = $rule['label'];

                    if (isset($rule['messages']) && is_array($rule['messages'])) {
                        foreach ($rule['messages'] as $key => $message) {
                            $messages[$rule['field'] . '.' . $key] = $message;
                        }
                    }
                }
            }

            $validator = Validator::make($this->getData(), $rules, $messages);
            $validator->setAttributeNames($attributes);
            if ($validator->fails()) {
                $this->errors = $validator->errors();
                return false;
            }
        }

        return true;
    }

    public function startProcess($operation_type, Request $request) {
        $this->operation_type = $operation_type;
   
        if (!$this->validate($request)) {
            return [
                'code' => 400,
                'status' => 'fail',
                'message' => __('errors.422'),
                'data' => $this->getErrors()
            ];
        }
        
        return $this->processor->run($operation_type, $this->getData());
    }

    public function cleanFromXss(Request $request) {
        $input = $request->all();
        array_walk_recursive($input, function(&$input, $key) {
            if(!in_array($key, $this->allowed_from_xss)){
                $input = strip_tags($input);
            }
        },true);
        $request->merge($input);
        return $request;
    }

    public function getErrors() {
        return $this->errors;
    }

    public function getData() {
        return $this->data;
    }

    public function getOperationType() {
        return $this->operation_type;
    }

    public function getRules() {
        return $this->rules;
    }

}
