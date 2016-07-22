<?php

namespace Love4work\Cart;

use Gloudemans\Shoppingcart\CartItem as ThirdPartyCartItem;
use Gloudemans\Shoppingcart\Contracts\Buyable;

class CartItem extends ThirdPartyCartItem
{
    /**
     * Create a new instance from a Buyable.
     *
     * @param \Gloudemans\Shoppingcart\Contracts\Buyable $item
     * @param array                                      $options
     * @return \Love4work\Cart\CartItem
     */
    public static function fromBuyable(Buyable $item, array $options = [])
    {
        return new self($item->getBuyableIdentifier(), $item->getBuyableDescription(), $item->getBuyablePrice(), $options);
    }

    /**
     * Create a new instance from the given array.
     *
     * @param array $attributes
     * @return \Love4work\Cart\CartItem
     */
    public static function fromArray(array $attributes)
    {
        $options = array_get($attributes, 'options', []);

        return new self($attributes['id'], $attributes['name'], $attributes['price'], $options);
    }

    /**
     * Create a new instance from the given attributes.
     *
     * @param int|string $id
     * @param string     $name
     * @param float      $price
     * @param array      $options
     * @return \Love4work\Cart\CartItem
     */
    public static function fromAttributes($id, $name, $price, array $options = [])
    {
        return new self($id, $name, $price, $options);
    }

    /**
     * Set the quantity for this cart item.
     *
     * @param int|float|string $qty
     */
    public function setQuantity($qty)
    {
        if(! is_numeric(str_replace('+', '', $qty)))
            throw new \InvalidArgumentException('Please supply a valid quantity.');

        if( preg_match('/\-/', $qty) == 1 ) {
            $this->qty -= (int) str_replace('-','',$qty);
        }
        elseif( preg_match('/\+/', $qty) == 1 ) {
            $this->qty += (int) str_replace('+','',$qty);
        } else {
            $this->qty = $qty;
        }
    }
}