<?php


class Calculation {

    public $num1;
    public $num2;
    public $num3;
    public $base;
    protected $base_total;
    protected $base_divided;
    protected $base_corrected;
    public $commission;
    protected $commission_total;
    protected $commission_divided;
    protected $commission_corrected;
    protected $tax_total;
    protected $tax_divided;
    protected $tax_corrected;
    protected $total_cost;
    protected $total_cost_divided;
    protected $total_cost_corrected;
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
        $this->base_total = $this->getPercents($this->getType('part'), $this->num1, $this->base);
        $this->commission_total = $this->getPercents($this->getType('part'), $this->base_total, $this->commission);
        $this->tax_total = $this->getPercents($this->getType('part'), $this->base_total, $this->num2);
        $this->total_cost = $this->getPercents($this->getType('total'), $this->base_total, $this->commission_total, $this->tax_total);

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
        $this->base_divided = $this->getPercents($this->getType('overall'), $this->num1, $this->base, $this->num3);
        $this->commission_divided = $this->getPercents($this->getType('part'), $this->base_divided, $this->commission);
        $this->tax_divided = $this->getPercents($this->getType('part'), $this->base_divided, $this->num2);
        $this->total_cost_divided = $this->getPercents($this->getType('total'), $this->base_divided, $this->commission_divided, $this->tax_divided);
        //percents correction
        $this->base_corrected = $this->getPercents($this->getType('correction'), $this->base_total, $this->base_divided, $this->num3);
        $this->commission_corrected = $this->getPercents($this->getType('correction'), $this->commission_total, $this->commission_divided, $this->num3);
        $this->tax_corrected = $this->getPercents($this->getType('correction'), $this->tax_total, $this->tax_divided, $this->num3);
        $this->total_cost_corrected = $this->getPercents($this->getType('correction'), $this->total_cost, $this->total_cost_divided, $this->num3);

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
