<?php
namespace App\Bases;

class BaseService
{
	public function __construct()
	{
	}

	public function outputResult($data, $code = 200, $message = '')
	{
		return  [
	        'code'    => $code,
	        'status'  => $code == 200 ? 'success' : 'error',
	        'message' => $message,
	        'data'    => $data
	    ];
	}
}
