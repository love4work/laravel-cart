<?php

use Love4work\Cart\Cart;

class CartTest extends TestCase
{
    /** @test */
    public function it_can_add_item()
    {
        $cart = $this->getCart();
        $this->assertTrue($cart->isEmpty(), 'Cart should be empty');

        $item = $this->getBuyableMock();

        $cart->add($item);

        $this->assertEquals(1, $cart->count());
        $this->assertFalse($cart->isEmpty(), 'Cart should not be empty');
        $cart->add(455, 'Sample Item', 100.99, 2, array());
        
    }
}