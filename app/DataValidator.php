<?php

require_once __DIR__ . '/EmptyDataException.php';
require_once __DIR__ . '/InvalidDataException.php';

class DataValidator
{

    protected $paramsRequired = ["value", "tax", "installments"];
    protected $givenParams;
    protected $missingParams;
    public $post;

    protected $rules = [
        'value' => [
            'type' => 'float',
            'options' => [
                'min_range' => 100,
                'max_range' => 100000,
            ]
        ],
        'tax' => [
            'type' => 'float',
            'options' => [
                'min_range' => 0,
                'max_range' => 100,
            ]
        ],
        'installment' => [
            'type' => 'int',
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

        if(count($this->missingParams) == 0){
            return $this->post;
        }
        throw new EmptyDataException("Empty required fields: ".implode(', ', $this->missingParams));
    }

    /**
     * @return object
     */
    public function validateData() : object
    {
        try
        {
            foreach($this->post as $required)
            {
                if($this->rules[$required]['type'] == 'float'){

                    if($this->rules[$required]['options']['min_range'] <= (float)$this->post[$required] &&  $this->post[$required] <= $this->rules[$required]['options']['min_range']){
                        throw new InvalidDataException("Invalid {$this->post[$required]}");
                    }
                }
                elseif($this->rules[$required]['type'] == 'int'){

                    if($this->rules[$required]['options']['min_range'] <= (int)$this->post[$required] &&  $this->post[$required] <= $this->rules[$required]['options']['min_range']){
                        throw new InvalidDataException("Invalid {$this->post[$required]}");
                    }
                }
            }
            return $this->post;
        }
        catch (InvalidDataException $exception){
            errorResponse($exception->getMessage());
        }
    }
}
