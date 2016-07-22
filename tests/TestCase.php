<?php

use Love4work\Cart\Cart;
use Gloudemans\Shoppingcart\Contracts\Buyable;

abstract class TestCase extends Orchestra\Testbench\TestCase
{
    /**
     * Set the package service provider.
     *
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [\Love4work\Cart\CartServiceProvider::class];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.locale_php', 'nl_NL');
        $app['config']->set('cart.database.connection', 'testing');
        $app['config']->set('cart.defaultCurrency', 'EUR');
        $app['config']->set('cart.tax', 21);

        $app['config']->set('session.driver', 'array');

        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }
    
    /**
     * Get an instance of the cart.
     *
     * @return \Love4Work\Cart\Cart
     */
    protected function getCart()
    {
        $session = $this->app->make('session');
        $events = $this->app->make('events');

        $cart = new Cart($session, $events);

        return $cart;
    }

    /**
     * Get a mock of a Buyable item.
     *
     * @param int    $id
     * @param string $name
     * @param float  $price
     * @return \Mockery\MockInterface
     */
    protected function getBuyableMock($id = 1, $name = 'Item name', $price = 10.00)
    {
        $item = Mockery::mock(Buyable::class)->shouldIgnoreMissing();

        $item->shouldAllowMockingMethod('setTaxRate');
        $item->shouldReceive('getBuyableIdentifier')->andReturn($id);
        $item->shouldReceive('getBuyableDescription')->andReturn($name);
        $item->shouldReceive('getBuyablePrice')->andReturn($price);

        return $item;
    }

}
