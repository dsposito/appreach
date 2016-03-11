<?php

namespace App\Models;

/**
 * Serves as the base model.
 */
class Model
{
    /**
     * Initializes the class.
     *
     * @param array $data The data to populate the model object with.
     *
     * @return void
     */
    public function __construct(array $data = array())
    {
        if (!empty($data)) {
            $this->loadData($data);
        }
    }

    /**
     * Attempts to map array data to object properties.
     *
     * @param array $data The data to populate the model object with.
     *
     * @return void
     */
    public function loadData(array $data = array())
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }
}
