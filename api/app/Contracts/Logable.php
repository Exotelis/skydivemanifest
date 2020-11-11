<?php

namespace App\Contracts;

/**
 * Interface Logable
 * @package App\Contracts
 */
interface Logable
{
    /**
     * Get the values of the most important attributes of the model.
     *
     * @return string
     */
    public function logString();
}
