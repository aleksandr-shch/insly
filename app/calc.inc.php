<?php


class Calculation {

    public $num1;
    public $num2;
    public $num3;
    public $base;
    public $baseTotal;
    public $baseDivided;
    public $baseCorrected;
    public $commission;
    public $commissionTotal;
    public $commissionDivided;
    public $commissionCorrected;
    public $taxTotal;
    public $taxDivided;
    public $taxCorrected;
    public $totalCost;
    public $totalCostDivided;
    public $totalCostCorrected;
    protected $type;
    protected $percents;


    /**
     * Calculation constructor.
     * @param $estimated
     * @param $tax
     * @param $installment
     * @param $friday
     */
    public function __construct($estimated, $tax, $installment, $friday) {

        $this->num1 = $estimated;
        $this->num2 = $tax;
        $this->num3 = $installment;
        $this->base = $friday;
        $this->commission = 17;
    }

    /**
     * @param $type
     * @return mixed
     */
    private function getType($type){
        $this->type = $type;
        return $this->type;
    }


    /**
     * @return $this
     */
    public function calculate() {

        //total percents
        $this->baseTotal = $this->getPercents($this->getType('part'), $this->num1, $this->base);
        $this->commissionTotal = $this->getPercents($this->getType('part'), $this->baseTotal, $this->commission);
        $this->taxTotal = $this->getPercents($this->getType('part'), $this->baseTotal, $this->num2);
        $this->totalCost = $this->getPercents($this->getType('total'), $this->baseTotal, $this->commissionTotal, $this->taxTotal);

        if($this->num3 > 1){
            $this->getInstallments();
        }
        return $this;
    }


    /**
     * @return Calculation
     */
    private function getInstallments(){

        //installment percents
        $this->baseDivided = $this->getPercents($this->getType('overall'), $this->num1, $this->base, $this->num3);
        $this->commissionDivided = $this->getPercents($this->getType('part'), $this->baseDivided, $this->commission);
        $this->taxDivided = $this->getPercents($this->getType('part'), $this->baseDivided, $this->num2);
        $this->totalCostDivided = $this->getPercents($this->getType('total'), $this->baseDivided, $this->commissionDivided, $this->taxDivided);
        //percents correction
        $this->baseCorrected = $this->getPercents($this->getType('correction'), $this->baseTotal, $this->baseDivided, $this->num3);
        $this->commissionCorrected = $this->getPercents($this->getType('correction'), $this->commissionTotal, $this->commissionDivided, $this->num3);
        $this->taxCorrected = $this->getPercents($this->getType('correction'), $this->taxTotal, $this->taxDivided, $this->num3);
        $this->totalCostCorrected = $this->getPercents($this->getType('correction'), $this->totalCost, $this->totalCostDivided, $this->num3);

        return $this;
    }


    /**
     * @param $type
     * @param $number1
     * @param $number2
     * @param int $number3
     * @return string
     */
    private function getPercents($type, $number1, $number2, $number3 = 0){

        switch($type){
            case 'part':
                $this->percents = number_format((float)(($number1/100)*$number2), 2, '.', '');
                break;
            case 'total':
                $this->percents =  number_format((float)($number1+$number2+$number3), 2, '.', '');
                break;
            case 'overall':
                $this->percents =  number_format((float)((($number1/100)*$number2)/$number3), 2, '.', '');
                break;
            case 'correction':
                $this->percents =  number_format((float)(($number1-$number2*$number3)+$number2), 2, '.', '');
                break;
        }
        return $this->percents;
    }
}
