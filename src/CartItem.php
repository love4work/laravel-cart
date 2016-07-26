<?php

namespace Love4work\Cart;

use Gloudemans\Shoppingcart\CartItemOptions;
use Illuminate\Contracts\Support\Arrayable;
use Gloudemans\Shoppingcart\Contracts\Buyable;

class CartItem implements Arrayable
{
    use HasCurrency;
    /**
     * The rowID of the cart item.
     *
     * @var string
     */
    public $rowId;

    /**
     * The ID of the cart item.
     *
     * @var int|string
     */
    public $id;

    /**
     * The quantity for this cart item.
     *
     * @var int|float
     */
    public $qty;

    /**
     * The name of the cart item.
     *
     * @var string
     */
    public $name;

    /**
     * The price without TAX of the cart item.
     *
     * @var float
     */
    public $price;


    /**
     * The options for this cart item.
     *
     * @var array
     */
    public $options;

    /**
     * The FQN of the associated model.
     *
     * @var string|null
     */
    private $associatedModel = null;

    /**
     * The tax rate for the cart item.
     *
     * @var int|float
     */
    private $taxRate = 0;

    /**
     * CartItem constructor.
     *
     * @param int|string $id
     * @param string     $name
     * @param float      $price
     * @param array      $options
     */
    public function __construct($id, $name, $price, array $options = [])
    {
        if(empty($id)) {
            throw new \InvalidArgumentException('Please supply a valid identifier.');
        }
        if(empty($name)) {
            throw new \InvalidArgumentException('Please supply a valid name.');
        }
        if(strlen($price) < 0 || ! is_numeric($price)) {
            throw new \InvalidArgumentException('Please supply a valid price.');
        }

        $this->id       = $id;
        $this->name     = $name;
        $this->price    = floatval($price);
        $this->options  = new CartItemOptions($options);
        $this->rowId = $this->generateRowId($id, $options);
    }

    /**
     * Returns the formatted price without TAX.
     *
     * @return \Love4work\Cart\MoneyProxy
     */
    public function price()
    {
        return $this->moneyProxy($this->price);
    }

    /**
     * Returns the formatted price with TAX.
     *
     * @return \Love4work\Cart\MoneyProxy
     */
    public function priceTax()
    {
        return $this->moneyProxy($this->priceTax);
    }

    /**
     * Returns the formatted subtotal.
     * Subtotal is price for whole CartItem without TAX
     *
     * @return \Love4work\Cart\MoneyProxy
     */
    public function subtotal()
    {
        return $this->moneyProxy($this->subtotal);
    }

    /**
     * Returns the formatted total.
     * Total is price for whole CartItem with TAX
     *
     * @return \Love4work\Cart\MoneyProxy
     */
    public function total()
    {
        return $this->moneyProxy($this->total);
    }

    /**
     * Returns the formatted tax.
     *
     * @return \Love4work\Cart\MoneyProxy
     */
    public function tax()
    {
        return $this->moneyProxy($this->tax);
    }

    /**
     * Returns the formatted tax.
     *
     * @return \Love4work\Cart\MoneyProxy
     */
    public function taxTotal()
    {
        return $this->moneyProxy($this->taxTotal);
    }

    /**
     * Set the quantity for this cart item.
     *
     * @param int|float|string $qty
     * @return $this
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

        return $this;
    }

    /**
     * Update the cart item from a Buyable.
     *
     * @param \Gloudemans\Shoppingcart\Contracts\Buyable $item
     * @return void
     */
    public function updateFromBuyable(Buyable $item)
    {
        $this->id       = $item->getBuyableIdentifier();
        $this->name     = $item->getBuyableDescription();
        $this->price    = $item->getBuyablePrice();
        $this->priceTax = $this->price + $this->tax;
    }

    /**
     * Update the cart item from an array.
     *
     * @param array $attributes
     * @return void
     */
    public function updateFromArray(array $attributes)
    {
        $this->id       = array_get($attributes, 'id', $this->id);
        $this->qty      = array_get($attributes, 'qty', $this->qty);
        $this->name     = array_get($attributes, 'name', $this->name);
        $this->price    = array_get($attributes, 'price', $this->price);
        $this->priceTax = $this->price + $this->tax;
        $this->options  = new CartItemOptions(array_get($attributes, 'options', []));

        $this->rowId = $this->generateRowId($this->id, $this->options->all());
    }

    /**
     * Associate the cart item with the given model.
     *
     * @param mixed $model
     * @return void
     */
    public function associate($model)
    {
        $this->associatedModel = is_string($model) ? $model : get_class($model);
    }

    /**
     * Set the tax rate.
     *
     * @param int|float $taxRate
     * @return $this
     */
    public function setTaxRate($taxRate)
    {
        $this->taxRate = $taxRate;
        return $this;
    }
    /**
     * Get an attribute from the cart item or get the associated model.
     *
     * @param string $attribute
     * @return mixed
     */
    public function __get($attribute)
    {
        if(property_exists($this, $attribute)) {
            return $this->{$attribute};
        }

        if($attribute === 'priceTax') {
            return $this->price + $this->tax;
        }

        if($attribute === 'subtotal') {
            return $this->qty * $this->price;
        }

        if($attribute === 'total') {
            return $this->qty * ($this->priceTax);
        }

        if($attribute === 'tax') {
            return $this->price * ($this->taxRate / 100);
        }

        if($attribute === 'taxTotal') {
            return $this->tax * $this->qty;
        }

        if($attribute === 'model') {
            return with(new $this->associatedModel)->find($this->id);
        }

        return null;
    }

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
     * Generate a unique id for the cart item.
     *
     * @param string $id
     * @param array  $options
     * @return string
     */
    protected function generateRowId($id, array $options)
    {
        ksort($options);

        return md5($id . serialize($options));
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'rowId'    => $this->rowId,
            'id'       => $this->id,
            'name'     => $this->name,
            'qty'      => $this->qty,
            'price'    => $this->price,
            'options'  => $this->options,
            'tax'      => $this->tax,
            'subtotal' => $this->subtotal
        ];
    }
}
