<?php

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
    }

    /** @test */
    public function it_can_represent_total_in_money()
    {
        $cart = $this->getCart();

        $item = $this->getBuyableMock();
        $item->setTaxRate(0);
        $cart->add($item);

        $this->assertEquals(1, $cart->count());
        $this->assertEquals($cart->money($cart->total()), '€ 12,10');
    }
}