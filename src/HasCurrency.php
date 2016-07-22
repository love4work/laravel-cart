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
     * @param bool $convertToCents
     *
     * @return Money
     */
    public function money($value, $convertToCents=true)
    {
        if($convertToCents)
        {
            $value *= 100;
        }
        return $this->formatMoney(new Money($value, $this->currency()));
    }

    protected function formatMoney(Money $money)
    {
        $numberFormatter = new NumberFormatter(config('app.locale_php'), NumberFormatter::CURRENCY);
        return (new IntlMoneyFormatter($numberFormatter))->format($money);
    }

}