<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class TargetValidation implements Rule
{
    private $type;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($type)
    {
        $this->type = $type;
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
        $targetType = request('target_type');
        $type = request($this->type . '_type');

        // If target_type is 1, the video is not required
        if ($targetType == 1) {
            return true;
        }

        // If target_type is 2 and video_type is not 0, the video is required
        if ($targetType == 2 && $type != 0) {
            return !empty($value);
        }

        // In all other cases, the validation passes
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The ' . $this->type . ' field is required.';
    }
}
