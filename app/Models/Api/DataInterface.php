<?php

namespace App\Models\Api;

interface DataInterface
{

    /**
     * Set model id
     *
     * @param integer $id
     * 
     * @return mixed
     */
    public function setId(string $id);

    /**
     * Save data
     *
     * @return $this
     */
    public function save();
}