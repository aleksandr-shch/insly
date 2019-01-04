<?php


class Calculation {

    public $num1;
    public $num2;
    public $num3;
    public $base;
    public $policy_price;
    public $commission;
    public $type;
    public $percents;

    /**
     * Calculation constructor.
     * @param $estimated
     * @param $num2Inserted
     * @param $num3Inserted
     * @param $friday
     */
    public function __construct($estimated, $num2Inserted, $num3Inserted, $friday) {

        $this->num1 = $estimated;
        $this->num2 = $num2Inserted;
        $this->num3 = $num3Inserted;
        $this->base = $friday;

        if($this->base == 'friday'){
            $this->policy_price = 13;
        }
        else{
            $this->policy_price = 11;
        }
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
     * @return mixed
     */
    public function calculate() {

        $result['value'] = $this->num1;
        $result['base_premium'] = $this->policy_price;
        $result['commission_rate'] = $this->commission;
        $result['tax_rate'] = $this->num2;
        $result['installments'] = $this->num3;
        //total percents
        $result[0]['base'] = $this->getPercents($this->getType('part'), $result['value'], $result['base_premium']);
        $result[0]['commission'] = $this->getPercents($this->getType('part'), $result[0]['base'], $result['commission_rate']);
        $result[0]['tax'] = $this->getPercents($this->getType('part'), $result[0]['base'], $result['tax_rate']);
        $result[0]['total'] = $this->getPercents($this->getType('total'), $result[0]['base'], $result[0]['commission'], $result[0]['tax']);

        if($result['installments'] > 1){
            $result = $this->getInstallments($result);
        }
        return $result;
    }

    /**
     * @param $result
     * @return mixed
     */
    private function getInstallments($result){

        //installment percents
        $result[1]['base'] = $this->getPercents($this->getType('overall'), $result['value'], $result['base_premium'], $result['installments']);
        $result[1]['commission'] = $this->getPercents($this->getType('part'), $result[1]['base'], $result['commission_rate']);
        $result[1]['tax'] = $this->getPercents($this->getType('part'), $result[1]['base'], $result['tax_rate']);
        $result[1]['total'] = $this->getPercents($this->getType('total'), $result[1]['base'], $result[1]['commission'], $result[1]['tax']);
        //percents correction
        $result[2]['base'] = $this->getPercents($this->getType('correction'), $result[0]['base'], $result[1]['base'], $result['installments']);
        $result[2]['commission'] = $this->getPercents($this->getType('correction'), $result[0]['commission'], $result[1]['commission'], $result['installments']);
        $result[2]['tax'] = $this->getPercents($this->getType('correction'), $result[0]['tax'], $result[1]['tax'], $result['installments']);
        $result[2]['total'] = $this->getPercents($this->getType('correction'), $result[0]['total'], $result[1]['total'], $result['installments']);

        return $result;
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
