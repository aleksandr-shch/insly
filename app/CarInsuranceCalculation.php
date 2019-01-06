<?php

require_once __DIR__ . '/CarInsuranceCalculator.php';
require_once __DIR__ . '/PolicyCalculation.php';


class CarInsuranceCalculation extends PolicyCalculation
{
    const BASE_PRECISION = 2;

    /**
     * @var float Car value
     */
    public $carValue;

    /**
     * @var PolicyCalculation[]
     */
    public $installments = [];

    /**
     * @var float Tax percentage
     */
    public $taxPercentage;

    /**
     * @var integer Policy percentage of car value
     */
    public $policyPercentage;

    /**
     * @var integer Installments
     */
    public $installmentsCount;


    /**
     * @param int $installmentsCount
     * @return array|PolicyCalculation[]
     */
    public function calculateInstallments(int $installmentsCount)
    {
        $penultimateInstallmentIndex = $installmentsCount - 1;
        $baseInstallment = $this->getBaseInstallment($installmentsCount);
        $lastInstallment = $this->getLastInstallment($baseInstallment, $installmentsCount);
        $this->installments = array_fill(0, $penultimateInstallmentIndex, $baseInstallment);
        array_push($this->installments, $lastInstallment);

        return $this->installments;
    }

    /**
     * @param int $installmentsCount
     * @return PolicyCalculation
     */
    protected function getBaseInstallment(int $installmentsCount) : PolicyCalculation
    {
        $baseInstallment = new PolicyCalculation();

        $baseInstallment->basePremium = round($this->basePremium/ $installmentsCount, static::BASE_PRECISION);
        $baseInstallment->commission = round($this->commission / $installmentsCount, static::BASE_PRECISION);
        $baseInstallment->tax = round($this->tax / $installmentsCount, static::BASE_PRECISION);
        $baseInstallment->total = round($this->total / $installmentsCount, static::BASE_PRECISION);

        return $baseInstallment;
    }

    /**
     * @param PolicyCalculation $baseInstallment
     * @param int $installmentsCount
     * @return PolicyCalculation
     */
    protected function getLastInstallment(PolicyCalculation $baseInstallment, int $installmentsCount) : PolicyCalculation
    {
        $lastInstallment = new PolicyCalculation();

        $count = $installmentsCount - 1;
        $lastInstallment->basePremium = round($this->basePremium - $baseInstallment->basePremium * $count,static::BASE_PRECISION);
        $lastInstallment->commission = round($this->commission - $baseInstallment->commission * $count,static::BASE_PRECISION);
        $lastInstallment->tax = round($this->tax - $baseInstallment->tax * $count,static::BASE_PRECISION);
        $lastInstallment->total = round($this->total - $baseInstallment->total * $count, static::BASE_PRECISION);

        return $lastInstallment;
    }

    /**
     * CarInsuranceCalculation constructor.
     * @param int $installmentsCount
     */
    public function __construct(int $installmentsCount = 1)
    {
        if($installmentsCount > 1){
            $this->calculateInstallments($installmentsCount);
        }
    }

    /**
     * @return bool
     */
    public function hasInstallments() : bool
    {
        return (bool)count($this->installments);
    }
}
