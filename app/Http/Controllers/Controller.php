<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;

abstract class Controller
{
    /**
     * Determine if the exception was caused by a foreign key (integrity) violation.
     */
    protected function isForeignKeyViolation(QueryException $exception): bool
    {
        return in_array((string) $exception->getCode(), ['23000', '23503'], true);
    }
}
