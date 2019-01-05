<?php

require_once __DIR__ . '/CarInsuranceCalculation.php';
require_once __DIR__ . '/CarInsuranceCalculator.php';

class PolicyCalculation
{

    /**
     * @var float Base premium
     */
    public $basePremium = 0;

    /**
     * @var float Commission
     */
    public $commission = 0;

    /**
     * @var float Tax
     */
    public $tax = 0;

    /**
     * @var float Total policy cost
     */
    public $total = 0;

    /**
     * @var array
     */

    /**
     * Calculate total
     */
    protected function calculatedTotalAttribute() : int
    {
        return $this->basePremium + $this->commission + $this->tax;
    }

    public function __get($name)
    {
        $calculatedPropertyName = 'calculated' . ucfirst($name) . 'Attribute';

        if(method_exists($this, $calculatedPropertyName)){
            return $this->{$calculatedPropertyName}();
        }

        if(property_exists($this, $name)) {
            return $this->{$name};
        }

        throw  new InvalidArgumentException("Property {$name} does not exist");
    }
}
