<?php
use Love4Work\Cart\Cart;
use Love4Work\Cart\Conditions\Tax;
use Mockery as m;

class CartConditionTest extends TestCase  {

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

    public function test_total_with_simplified_tax_condition()
    {
        $this->fillCart();

        $this->assertEquals(187.49, $this->cart->subTotal(), 'Cart should have sub total of 187.49');

        // add condition
        $condition = new Tax('BTW 21%', '21%');

        $this->cart->condition($condition);

        // no changes in subtotal as the condition's target added was for total
        $this->assertEquals(187.49, $this->cart->subTotal(), 'Cart should have sub total of 187.49');

        // total should be changed
        $this->assertEquals(226.86, $this->cart->total(), 'Cart should have a total of 226.86');
    }

    public function test_add_item_with_simplified_tax_condition()
    {
        $condition1 = new Tax('BTW -5%', '-5%', 'item');

        $item = array(
            'id' => 456,
            'name' => 'Sample Item 1',
            'price' => 100,
            'quantity' => 1,
            'attributes' => array(),
            'conditions' => $condition1
        );

        $this->cart->add($item);

        $this->assertEquals(95, $this->cart->get(456)->priceSumWithConditions());
        $this->assertEquals(95, $this->cart->subTotal());
    }

}
