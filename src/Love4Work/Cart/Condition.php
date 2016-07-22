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
        // if value has a percentage sign on it, we will get first
        // its percentage then we will evaluate again if the value
        // has a minus or plus sign so we can decide what to do with the
        // percentage, whether to add or subtract it to the total/subtotal/price
        // if we can't find any plus/minus sign, we will assume it as plus sign
        if ($this->valueIsPercentage($conditionValue)) {
            if ($this->valueIsToBeSubtracted($conditionValue)) {
                $value = Helpers::normalizePercentage($this->cleanValue($conditionValue));

                $this->parsedRawValue = $totalOrSubTotalOrPrice * ($value / 100);

                $result = Helpers::round($totalOrSubTotalOrPrice - $this->parsedRawValue, $this->precision);
            } else if ($this->valueIsToBeAdded($conditionValue)) {
                $value = Helpers::normalizePercentage($this->cleanValue($conditionValue));

                $this->parsedRawValue = $totalOrSubTotalOrPrice * ($value / 100);

                $result = Helpers::round($totalOrSubTotalOrPrice + $this->parsedRawValue, $this->precision);
            } else {
                $value = Helpers::normalizePercentage($conditionValue);

                $this->parsedRawValue = $totalOrSubTotalOrPrice * ($value / 100);

                $result = Helpers::round($totalOrSubTotalOrPrice + $this->parsedRawValue, $this->precision);
            }
        }

        // if the value has no percent sign on it, the operation will not be a percentage
        // next is we will check if it has a minus/plus sign so then we can just deduct it to total/subtotal/price
        else {
            if ($this->valueIsToBeSubtracted($conditionValue)) {
                $this->parsedRawValue = Helpers::normalizePrice($this->cleanValue($conditionValue));

                $result = Helpers::round($totalOrSubTotalOrPrice - $this->parsedRawValue, $this->precision);
            } else if ($this->valueIsToBeAdded($conditionValue)) {
                $this->parsedRawValue = Helpers::normalizePrice($this->cleanValue($conditionValue));

                $result = Helpers::round($totalOrSubTotalOrPrice + $this->parsedRawValue, $this->precision);
            } else {
                $this->parsedRawValue = Helpers::normalizePrice($conditionValue);

                $result = Helpers::round($totalOrSubTotalOrPrice + $this->parsedRawValue, $this->precision);
            }
        }

        // Do not allow items with negative prices.
        return $result < 0 ? 0 : $result;
    }

}