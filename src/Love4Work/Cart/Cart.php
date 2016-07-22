<?php namespace Love4Work\Cart;

use Bnet\Cart\Attribute;
use Bnet\Cart\Cart as ThirdPartyCart;
use Bnet\Cart\Helpers\Helpers;
use Bnet\Cart\Item;

/**
 * Class Cart
 * @package Love4Work\Cart
 */
class Cart extends ThirdPartyCart {

    /**
     * add item to the cart, it can be an array or multi dimensional array
     *
     * @param string|array $id
     * @param string $name
     * @param int $price
     * @param int $quantity
     * @param array $attributes
     * @param Condition|array $conditions
     * @return $this
     * @throws InvalidItemException
     */
    public function add($id, $name = null, $price = null, $quantity = 1, $attributes = array(), $conditions = array())
    {
        if (is_array($id)) {
            // the first argument is an array, now we will need to check if it is a multi dimensional
            if (!Helpers::isMultiArray($id)) {
                $id = [$id];
            }

            foreach ($id as $item) {
                $this->add(
                    $item['id'],
                    $item['name'],
                    $item['price'],
                    $item['quantity'],
                    @$item['attributes'] ?: array(),
                    @$item['conditions'] ?: array()
                );
            }

            return $this;
        }

        // validate data
        $item = $this->validate(array(
            'id' => $id,
            'name' => $name,
            'price' => Helpers::normalizePrice($price),
            'quantity' => $quantity,
            'attributes' => new Attribute($attributes),
            'conditions' => $conditions,
        ));

        // get the cart
        $cart = $this->items();

        // if the item is already in the cart we will just update it
        if ($cart->has($id)) {
            $this->update($id, $item);
        } else {
            $this->addRow($id, $item);
        }

        return $this;
    }

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

        $cart = $this->items();

        $item = $cart->pull($id);

        foreach($data as $key => $value)
        {
            if( $key == 'quantity' ) {
                $item = $this->updateQuantity($item, $key, $value);
            }
            elseif( $key == 'attributes' ) {
                $item[$key] = new Attribute($value);
            }
            else {
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
        if( preg_match('/\-/', $value) == 1 ) {
            $value = (int) str_replace('-','',$value);

            // we will not allowed to reduced quantity to 0, so if the given value
            // would result to item quantity of 0, we will not do it.
            if( ($item[$key] - $value) > 0 ) {
                $item[$key] -= $value;
            }
        }
        elseif( preg_match('/\+/', $value) == 1 ) {
            $item[$key] += (int) str_replace('+','',$value);
        }
        else {
            $item[$key] = (int) $value;
        }

        return $item;
    }

    /**
     * get cart sub total
     *
     * @param int $precision
     *
     * @return int
     */
    public function subTotal($precision = 2) {
        $cart = $this->items();

        $sum = $cart->sum(function (Item $item) {
            return $item->priceSumWithConditions();
        });

        return Helpers::round($sum, $precision);
    }

    /**
     * the new total in which conditions are already applied
     *
     * @param int $precision
     *
     * @return int
     */
    public function total($precision = 2)
    {
        $newTotal = $this->subTotal($precision);

        $conditions = $this->getConditions();

        if (!$conditions->count()) return $newTotal;

        $process = 0;

        $conditions->each(function ($cond) use (&$newTotal, &$process, $precision) {
            if ($cond->getTarget() === 'subtotal') {

                $newTotal = $cond->applyCondition($newTotal, $precision);

                $process++;
            }
        });

        return $newTotal;
    }

}