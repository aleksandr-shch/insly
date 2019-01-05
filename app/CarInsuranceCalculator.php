<?php

require_once __DIR__ . '/CarInsuranceCalculation.php';
require_once __DIR__ . '/PolicyCalculation.php';

class CarInsuranceCalculator extends PolicyCalculation
{
    /**
     * @var float Car cost
     */
    protected $carCost;

    /**
     * @var float Tax percentage
     */
    protected $taxPercentage;

    /**
     * @var integer Payments count
     */
    protected $paymentsCount;


    /**
     * @var integer Policy percentage of car cost
     */
    protected $policyPercentage;

    /**
     * @var integer Commission percentage
     */
    protected $commissionPercentage;

    /**
     * @var int Installments count
     */
    protected $installmentsCount = 1;


    /**
     * @param float $carCost
     * @return self
     */
    public function setCarCost(float $carCost)
    {
        $this->carCost = $carCost;
        return $this;
    }

    /**
     * @param float $taxPercentage
     * @return self
     */
    public function setTaxPercentage(float $taxPercentage)
    {
        $this->taxPercentage = $taxPercentage;
        return $this;
    }

    /**
     * @param int $policyPercentage
     * @return self
     */
    public function setPolicyPercentage(int $policyPercentage)
    {
        $this->policyPercentage = $policyPercentage;
        return $this;
    }

    /**
     * @param mixed $commissionPercentage
     * @return self
     */
    public function setCommissionPercentage(int $commissionPercentage)
    {
        $this->commissionPercentage = $commissionPercentage;
        return $this;
    }

    /**
     * @return CarInsuranceCalculation
     */
    public function calculate() : CarInsuranceCalculation
    {
        $result = new CarInsuranceCalculation($this->installmentsCount);

        $result->carCost = $this->carCost;
        $result->basePremium = round($this->carCost / 100 * $this->policyPercentage, CarInsuranceCalculation::BASE_PRECISION);
        $result->commission = round($result->basePremium / 100 * $this->commissionPercentage,CarInsuranceCalculation::BASE_PRECISION);
        $result->tax = round($result->basePremium / 100 * $this->taxPercentage,CarInsuranceCalculation::BASE_PRECISION);
        $result->policyPercentage = $this->policyPercentage;
        $result->taxPercentage = $this->taxPercentage;
        $result->installmentsCount = $this->installmentsCount;
        $result->total = $result->calculatedTotalAttribute();

        if($result->hasInstallments() === true){
            $result->installments = $result->calculateInstallments($this->installmentsCount);
        }
        return $result;
    }


    /**
     * @param int $installmentsCount
     * @return int
     */
    public function setInstallmentsCount(int $installmentsCount) : int
    {
        $this->installmentsCount = $installmentsCount;

        return $this->installmentsCount;
    }
}
