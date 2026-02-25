<?php
namespace BitApps\FM\Vendor\BitApps\WPValidator\Rules;

use BitApps\FM\Vendor\BitApps\WPValidator\Rule;

class PresentRule extends Rule
{
    private $message = 'The :attribute field must be present.';

    public function validate($value): bool
    {
        $attributeKey = $this->getInputDataContainer()->getAttributeKey();

        $data = $this->getInputDataContainer()->getData();
        return isset($data[$attributeKey]);

    }

    public function message()
    {
        return $this->message;
    }
}
