<?php

namespace Love4work\Cart;

use Money\Currency;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money;
use NumberFormatter;

trait HasCurrency
{
    private $currency;

    /**
     * Get the Currency
     *
     * @return \Money\Currency $currency
     */
    public function currency()
    {
        if(empty($this->currency))
            $this->setCurrency(config('cart.defaultCurrency'));
        return $this->currency;
    }

    /**
     * @param $currency
     * @return $this
     */
    public function setCurrency($currency)
    {
        $this->currency = new Currency($currency);

        return $this;
    }

    /**
     * @param $value
     * @return MoneyProxy
     */
    public function moneyProxy($value)
    {
        return new MoneyProxy($value, $this->currency());
    }

}