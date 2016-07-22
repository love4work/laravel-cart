<?php namespace Love4Work\Cart\Conditions;

use Love4Work\Cart\Condition;

/**
 * Class Tax
 * @package Love4Work\Cart
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