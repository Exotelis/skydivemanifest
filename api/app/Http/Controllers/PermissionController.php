<?php

namespace App\Http\Controllers;

use App\Models\Permission;

/**
 * Class PermissionController
 * @package App\Http\Controllers
 */
class PermissionController extends Controller
{
    /**
     * Get a list of all existing permissions.
     *
     * @return array
     */
    public function all()
    {
        return Permission::all()->toArray();
    }
}
