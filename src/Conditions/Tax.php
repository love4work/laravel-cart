<?php namespace Love4work\Cart\Conditions;

use Love4work\Cart\Condition;

/**
 * Class Tax
 * @package Love4work\Cart
 */
class Tax extends Condition
{
    /**
     * Tax constructor.
     * @param array $name
     * @param $value
     * @param string $target
     */
    public function __construct($name, $value, $target = 'subtotal')
    {
        $args = [
            'name' => $name,
            'type' => 'tax',
            'target' => $target,
            'value' => $value,
        ];

        parent::__construct($args);
    }

}