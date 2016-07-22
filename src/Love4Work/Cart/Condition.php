<?php namespace Love4Work\Cart;

use Bnet\Cart\Condition as ThirdPartyCondition;
use Bnet\Cart\Helpers\Helpers;

/**
 * Class Condition
 * @package Love4Work\Cart
 */
class Condition extends ThirdPartyCondition {

    /**
     * the parsed raw value of the condition
     *
     * @var
     */
    private $parsedRawValue;

    /**
     * Define the precision of the values
     * @var
     */
    private $precision;

    /**
     * apply condition to total or subtotal
     *
     * @param $totalOrSubTotalOrPrice
     * @param int $precision
     *
     * @return int
     */
    public function applyCondition($totalOrSubTotalOrPrice, $precision=2)
    {
        $this->precision = $precision;
        return $this->apply($totalOrSubTotalOrPrice, $this->getValue());
    }

    /**
     * get the calculated value of this condition supplied by the subtotal|price
     *
     * @param $totalOrSubTotalOrPrice
     *
     * @return mixed
     */
    public function getCalculatedValue($totalOrSubTotalOrPrice) {
        $this->apply($totalOrSubTotalOrPrice, $this->getValue());

        return $this->parsedRawValue;
    }

    /**
     * apply condition
     *
     * @param $totalOrSubTotalOrPrice
     * @param $conditionValue
     * @return int
     */
    protected function apply($totalOrSubTotalOrPrice, $conditionValue) {

        $value = $this->cleanValue($conditionValue);

        if ($this->valueIsPercentage($conditionValue)) {
            $this->parsedRawValue = $totalOrSubTotalOrPrice * (Helpers::normalizePercentage($value) / 100);
        } else {
            $this->parsedRawValue = Helpers::normalizePrice($value);
        }

        $sum = ($this->valueIsToBeSubtracted($conditionValue))
            ? $totalOrSubTotalOrPrice - $this->parsedRawValue
            : $totalOrSubTotalOrPrice + $this->parsedRawValue;

        $result = Helpers::round($sum, $this->precision);
        // Do not allow items with negative prices.
        return $result < 0 ? 0 : $result;
    }

}