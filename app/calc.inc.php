<?php


class Calculation {

    public $num1;
    public $num2;
    public $num3;
    public $base;
    public $policy_price;
    public $commission;

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
     * @return mixed
     */
    public function calculate() {

        $result['value'] = $this->num1;
        $result['base_premium'] = $this->policy_price;
        $result['commission_rate'] = $this->commission;
        $result['tax_rate'] = $this->num2;
        $result['installments'] = $this->num3;
        //total percents
        $result[0]['base'] = $this->getPercents($result['value'], $result['base_premium']);
        $result[0]['commission'] = $this->getPercents($result[0]['base'], $result['commission_rate']);
        $result[0]['tax'] = $this->getPercents($result[0]['base'], $result['tax_rate']);
        $result[0]['total'] = $this->getPercents($result[0]['base'], $result[0]['commission'], $result[0]['tax']);

        if($result['installments'] > 1){
            //installment percents
            $result[1]['base'] = $this->getPercents($result['value'], $result['base_premium'], $result['installments'],0,1);
            $result[1]['commission'] = $this->getPercents($result[1]['base'], $result['commission_rate']);
            $result[1]['tax'] = $this->getPercents($result[1]['base'], $result['tax_rate']);
            $result[1]['total'] = $this->getPercents($result[1]['base'], $result[1]['commission'], $result[1]['tax']);
            //overall percents correction
            $result[2]['base'] = $this->getPercents($result[0]['base'], $result[1]['base'], $result['installments'], 1);
            $result[2]['commission'] = $this->getPercents($result[0]['commission'], $result[1]['commission'], $result['installments'], 1);
            $result[2]['tax'] = $this->getPercents($result[0]['tax'], $result[1]['tax'], $result['installments'], 1);
            $result[2]['total'] = $this->getPercents($result[0]['total'], $result[1]['total'], $result['installments'], 1);
        }
        return $result;
    }

    /**
     * @param $number1
     * @param $number2
     * @param int $number3
     * @param int $correction
     * @param int $overall
     * @return string
     */
    private function getPercents($number1, $number2, $number3 = 0, $correction = 0, $overall = 0){

        if($number3 > 0){

            if($correction > 0){
                return number_format((float)(($number1-$number2*$number3)+$number2), 2, '.', '');
            }
            elseif($overall > 0){
                return number_format((float)((($number1/100)*$number2)/$number3), 2, '.', '');
            }
            return number_format((float)($number1+$number2+$number3), 2, '.', '');
        }
        return number_format((float)(($number1/100)*$number2), 2, '.', '');
    }
}
