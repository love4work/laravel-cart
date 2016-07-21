<?php namespace Love4Work\Cart;

use Darryldecode\Cart\Cart as ThirdPartyCart;
use Darryldecode\Cart\ItemAttributeCollection;

/**
 * Class Cart
 * @package Love4Work\Cart
 */
class Cart extends ThirdPartyCart {

    /**
     * update a cart
     *
     * @param $id
     * @param $data
     *
     * the $data will be an associative array, you don't need to pass all the data, only the key value
     * of the item you want to update on it
     */
    public function update($id, $data)
    {
        $this->events->fire($this->getInstanceName().'.updating', array($data, $this));

        $cart = $this->getContent();

        $item = $cart->pull($id);

        foreach($data as $key => $value)
        {
            if( $key == 'quantity' )
            {
                $item = $this->updateQuantity($item, $key, $value);
            }
            elseif( $key == 'attributes' )
            {
                $item[$key] = new ItemAttributeCollection($value);
            }
            else
            {
                $item[$key] = $value;
            }
        }

        $cart->put($id, $item);

        $this->save($cart);

        $this->events->fire($this->getInstanceName().'.updated', array($item, $this));
    }

    /**
     * update a cart item quantity relative to its current quantity
     *
     * @param $item
     * @param $key
     * @param $value
     * @return mixed
     */
    protected function updateQuantity($item, $key, $value)
    {
        if( preg_match('/\-/', $value) == 1 )
        {
            $value = (int) str_replace('-','',$value);

            // we will not allowed to reduced quantity to 0, so if the given value
            // would result to item quantity of 0, we will not do it.
            if( ($item[$key] - $value) > 0 )
            {
                $item[$key] -= $value;
            }
        }
        elseif( preg_match('/\+/', $value) == 1 )
        {
            $item[$key] += (int) str_replace('+','',$value);
        }
        else
        {
            $item[$key] = (int) $value;
        }

        return $item;
    }

}