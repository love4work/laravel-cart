<?php

use Love4work\Cart\Conditions\Tax;

class CartConditionTest extends TestCase
{
    /** @test */
    public function total_with_simplified_tax_condition()
    {
        $cart = $this->getCart();

        $item = $this->getBuyableMock();

        $cart->add($item);

        $this->assertEquals(1, $cart->count());

        // add condition
        //$condition = new Tax('BTW 21%', '21%');

        //$cart->condition($condition);
        // total should be changed
        //$this->assertEquals(226.86, $cart->total(), 'Cart should have a total of 226.86');
    }

}
