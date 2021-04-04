<?php

namespace App\Traits;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * Trait Paginate
 * @package App\Traits
 */
trait Paginate
{
    /**
     * Validate pagination parameters.
     *
     * @param array $parameters
     * @throws
     */
    public static function validatePagination (array $parameters)
    {
        $validator = Validator::make($parameters, [
            'limit' => 'sometimes|required|numeric|min:5|max:250',
            'page'  => 'sometimes|required|numeric',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
}
