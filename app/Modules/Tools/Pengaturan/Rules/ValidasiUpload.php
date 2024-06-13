<?php

namespace App\Modules\Tools\Pengaturan\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class ValidasiUpload implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected $data, $message;
    
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $config = $value;
        foreach ($config as $key => $val) {
            if ($val['tipe'] == 'upload') {
                if ($val['value'] != '') {
                    $file = $val['value'];
                    $allowedExtension = ltrim($val['fileallow'], '.');
                    if (!($file->getClientOriginalExtension() === $allowedExtension)) {
                        $this->message = 'Format file tidak sesuai';
                        return false;
                    }
                }
            }
        }
        
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
