<?php

namespace TimMcLeod\ViewModel;

use Exception;

abstract class BaseViewModel
{
    /** @var array */
    protected $rules = [];

    /** @var array */
    protected $data = [];

    /**
     * ViewModel constructor.
     *
     * @param array $data
     */
    public function __construct($data = [])
    {
        $this->data = array_merge($this->data, $data);

        $this->validate();
    }

    /**
     * @param string $field
     * @return mixed
     */
    public function __get($field)
    {
        return isset($this->data[$field]) ? $this->data[$field] : null;
    }

    /**
     * @param $field
     * @return bool
     */
    public function __isset($field)
    {
        return array_key_exists($field, $this->data);
    }

    /**
     * @throws Exception
     */
    protected function validate()
    {
        $validator = validator($this->data, $this->rules);

        if ($validator->fails())
        {
            throw new Exception('Invalid data: ' . $validator->getMessageBag()->first());
        }
    }
}