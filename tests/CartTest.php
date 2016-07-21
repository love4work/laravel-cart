<?php

use Love4Work\Cart\Cart;
use Mockery as m;

class CartTest extends PHPUnit_Framework_TestCase  {

    /**
     * @var Love4Work\Cart\Cart
     */
    protected $cart;

    public function setUp()
    {
        $events = m::mock('Illuminate\Contracts\Events\Dispatcher');
        $events->shouldReceive('fire');

        $this->cart = new Cart(
            new SessionMock(),
            $events,
            'shopping',
            'SAMPLESESSIONKEY'
        );
    }

    public function tearDown()
    {
        m::close();
    }

    public function test_cart_can_add_item()
    {
        $this->cart->add(455, 'Sample Item', 100.99, 2, array());

        $this->assertFalse($this->cart->isEmpty(), 'Cart should not be empty');
        $this->assertEquals(1, $this->cart->getContent()->count(),'Cart content should be 1');
        $this->assertEquals(455, $this->cart->getContent()->first()['id'], 'Item added has ID of 455 so first content ID should be 455');
        $this->assertEquals(100.99, $this->cart->getContent()->first()['price'], 'Item added has price of 100.99 so first content price should be 100.99');
    }
}