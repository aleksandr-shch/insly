<?php

require_once __DIR__ . '/EmptyDataException.php';
require_once __DIR__ . '/InvalidDataException.php';
require_once __DIR__ . '/PolicyCalculation.php';
require_once __DIR__ . '/CarInsuranceCalculator.php';
require_once __DIR__ . '/CarInsuranceCalculation.php';

class DataValidator
{

    protected $paramsRequired = ["value", "tax", "installment"];
    protected $givenParams;
    protected $missingParams;
    public $post;

    protected $rules = [
        'value' => [
            'filter_value' => 'FILTER_VALIDATE_INT',
            'options' => [
                'min_range' => 100,
                'max_range' => 100000
            ]
        ],
        'tax' => [
            'filter_value' => 'FILTER_VALIDATE_INT',
            'options' => [
                'min_range' => 0,
                'max_range' => 100
            ]
        ],
        'installment' => [
            'filter_value' => 'FILTER_VALIDATE_INT',
            'options' => [
                'min_range' => 1,
                'max_range' => 12
            ]
        ],
    ];

    /**
     * DataValidator constructor.
     * @throws EmptyDataException
     */
    public function __construct()
    {
        $this->post = $_POST;

        $this->givenParams = array_keys($this->post);
        $this->missingParams = array_diff($this->paramsRequired, $this->givenParams);

        if($this->missingParams !== false){

            foreach($this->post as $key => $value)
            {
                $this->post[$key] = (int)filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS);
            }

            return $this->post;
        }
        throw new EmptyDataException("Empty required fields: ".implode(', ',$this->missingParams));
    }


    /**
     * @return mixed
     * @throws InvalidDataException
     */
    public function validateData() :object
    {
        $this->checkData($this->post);

        return $this->post;
    }

    /**
     * @param $post
     * @return mixed
     * @throws InvalidDataException
     */
    protected function checkData(object $post) :object
    {
        foreach($post as $required)
        {
            filter_var($this->post[$required], $this->rules[$required]['filter_value'], $this->rules[$required]['options']);

            throw new InvalidDataException("Invalid {$this->post[$required]}");
        }

        return $this->post;
    }
}