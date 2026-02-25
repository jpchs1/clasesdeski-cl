<?php
namespace BitApps\FM\Vendor\BitApps\WPValidator\Rules;

use BitApps\FM\Vendor\BitApps\WPValidator\Helpers;
use BitApps\FM\Vendor\BitApps\WPValidator\Rule;

class RequiredRule extends Rule
{
    use Helpers;

    private $message = 'The :attribute field is required';

    public function validate($value): bool
    {
        return !$this->isEmpty($value);
    }

    public function message()
    {
        return $this->message;
    }
}
