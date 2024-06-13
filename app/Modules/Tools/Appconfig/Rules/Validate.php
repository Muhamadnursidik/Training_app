<?php

namespace App\Modules\Tools\Appconfig\Rules;

use Illuminate\Contracts\Validation\Rule;

class Validate implements Rule
{
    private $data;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($data = [])
    {
        $this->data  = $data;
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
        if ($value != 'Bartechmedia@123') {
            return false;
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
        return __('Password salah!');
    }
}
