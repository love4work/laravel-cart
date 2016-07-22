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
    public function it_can_present_subtotal_of_item_in_money()
    {
        $cart = $this->getCart();
        $item = $this->getBuyableMock(1, "Test Product", 40.00);

        $cartItem = $cart->add($item);

        $this->assertEquals('€ 40,00', $cartItem->subtotal());
    }

    /** @test */
    public function it_can_present_total_of_item_in_money()
    {
        $cart = $this->getCart();
        $item = $this->getBuyableMock(1, "Test Product", 40.00);

        $cartItem = $cart->add($item);

        $this->assertEquals('€ 48,40', $cartItem->total());
    }

    /** @test */
    public function it_can_present_subtotal_in_money()
    {
        $cart = $this->getCart();
        $item = $this->getBuyableMock();

        $cart->add($item);

        $this->assertEquals('€ 10,00', $cart->subtotal()->format());
    }

    /** @test */
    public function it_can_present_total_in_money()
    {
        $cart = $this->getCart();
        $item = $this->getBuyableMock();

        $cart->add($item)->setTaxRate(0);

        $this->assertEquals('€ 10,00', $cart->total()->format());
    }
}