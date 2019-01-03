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
        $result[0]['base'] = number_format((float)(($result['value']/100)*$result['base_premium']), 2, '.', '');
        $result[0]['commission'] = number_format((float)(($result[0]['base']/100)*$result['commission_rate']), 2, '.', '');
        $result[0]['tax'] = number_format((float)(($result[0]['base']/100)*$result['tax_rate']), 2, '.', '');
        $result[0]['total'] = number_format((float)($result[0]['base']+$result[0]['commission']+$result[0]['tax']), 2, '.', '');

        if($result['installments'] > 1){
            $result[1]['base'] = number_format((float)((($result['value']/100)*$result['base_premium'])/$result['installments']), 2, '.', '');
            $result[1]['commission'] = number_format((float)(($result[1]['base']/100)*$result['commission_rate']), 2, '.', '');
            $result[1]['tax'] = number_format((float)(($result[1]['base']/100)*$result['tax_rate']), 2, '.', '');
            $result[1]['total'] = number_format((float)($result[1]['base']+$result[1]['commission']+$result[1]['tax']), 2, '.', '');

            $result[2]['base'] = number_format((float)(($result[0]['base']-$result[1]['base']*$result['installments'])+$result[1]['base']), 2, '.', '');
            $result[2]['commission'] = number_format((float)(($result[0]['commission']-$result[1]['commission']*$result['installments'])+$result[1]['commission']), 2, '.', '');
            $result[2]['tax'] = number_format((float)(($result[0]['tax']-$result[1]['tax']*$result['installments'])+$result[1]['tax']), 2, '.', '');
            $result[2]['total'] = number_format((float)(($result[0]['total']-$result[1]['total']*$result['installments'])+$result[1]['total']), 2, '.', '');
        }
        return $result;
    }
}
