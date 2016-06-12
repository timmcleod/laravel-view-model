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

    /**
     * Returns true if any "old" inputs exist.
     *
     * @return bool
     */
    public function hasOldInput()
    {
        return count(old()) > 0;
    }

    /**
     * Returns true if the given checkbox should be checked.
     *
     * @param string $field
     * @param bool   $default
     * @return bool
     */
    public function checkboxIsChecked($field, $default)
    {
        // if old input exists, use it the old value, otherwise, use the default
        if ($this->hasOldInput()) return old($field) === 'on';

        return !!$default;
    }

    /**
     * Returns 'checked' if the given checkbox should be checked, otherwise, returns ''.
     *
     * @param string $field
     * @param bool   $default
     * @return string
     */
    public function checkboxChecked($field, $default)
    {
        return $this->checkboxIsChecked($field, $default) ? 'checked' : '';
    }
}