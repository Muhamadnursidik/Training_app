<?php

namespace App\Modules\Admin\Menu\Rules;

use Illuminate\Contracts\Validation\Rule;

class UniquePermision implements Rule
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
        $counter = 0;
        if (is_array($this->data['features'])) {
            foreach ($this->data['features'] as $feature) {
                if ($feature['permission_id'] == $value) {
                    $counter++;
                }
            }
        }
        return $counter > 1 ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('validation.must_unique');
    }
}
