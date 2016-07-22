<?php 

namespace Love4work\Cart;

use Money\Currency;
use Money\Money;
use NumberFormatter;
use Money\Formatter\IntlMoneyFormatter;

/**
 * Class MoneyProxy
 * @package Love4work\Cart
 */
class MoneyProxy {

    public static $doNotConvertToCents;

    public function __construct($value, Currency $currency)
    {
        $this->calculateValue($value);
        $this->money = new Money($value, $currency);
        $this->formatter = new NumberFormatter(config('app.locale_php'), NumberFormatter::CURRENCY);
    }

    public function format()
    {
        return (new IntlMoneyFormatter($this->formatter))->format($this->money);
    }

    private function calculateValue(&$value)
    {
        if(self::$doNotConvertToCents){
            return;
        }
        $value *= 100;
    }

    public function __toString()
    {
        return $this->money->getAmount();
    }
}

