<?php 

namespace Love4work\Cart;

use Money\Currency;
use Money\Money;
use NumberFormatter;

/**
 * Class MoneyProxy
 * @package Love4work\Cart
 */
class MoneyProxy {

    /**
     * @var
     */
    public static $doNotConvertToCents;

    /**
     * MoneyProxy constructor.
     * @param $value
     * @param Currency $currency
     */
    public function __construct($value, Currency $currency)
    {
        $this->calculateValue($value);
        $this->money = new Money($value, $currency);
        $this->formatter = new NumberFormatter(config('app.locale_php'), NumberFormatter::CURRENCY);
    }

    /**
     * @param null $formatter
     * @return string
     */
    public function format($formatter = null)
    {
        $formatter = $formatter ?: $this->formatter;
        return $formatter->format($this->getAmount());
    }

    /**
     * @param $value
     */
    private function calculateValue(&$value)
    {
        if(self::$doNotConvertToCents){
            return;
        }
        $value = intval(strval($value) * 100);
    }

    /**
     * @param bool $convert
     * @return float|string
     */
    public function getAmount($convert = false)
    {
        if(self::$doNotConvertToCents || $convert){
            return $this->money->getAmount();
        }

        return ($this->money->getAmount() / 100);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getAmount();
    }
}

