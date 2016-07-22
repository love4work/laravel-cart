<?php

use Love4Work\Cart\Cart;
use PHPUnit_Framework_Assert as PHPUnit;

trait CartAssertions
{

    /**
     * Assert that the cart contains the given number of items.
     *
     * @param int|float                     $items
     * @param \Love4Work\Cart\Cart $cart
     */
    public function assertItemsInCart($items, Cart $cart)
    {
        $actual = $cart->count();

        PHPUnit::assertEquals($items, $cart->count(), "Expected the cart to contain {$items} items, but got {$actual}.");
    }

    /**
     * Assert that the cart contains the given number of rows.
     *
     * @param int                           $rows
     * @param \Love4Work\Cart\Cart $cart
     */
    public function assertRowsInCart($rows, Cart $cart)
    {
        $actual = $cart->content()->count();

        PHPUnit::assertCount($rows, $cart->content(), "Expected the cart to contain {$rows} rows, but got {$actual}.");
    }

}