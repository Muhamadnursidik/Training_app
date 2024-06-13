<?php
namespace App\Bases;

abstract class BaseProcessor
{
    protected $output;

    public function __construct()
    {
    }

    abstract public function setProcessor($operation_type, array $data);

    public function run($operation_type, array $data)
    {
        $this->setProcessor($operation_type, $data);
        return $this->output;
    }
}
