<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrganizationsController extends Controller
{
    public function index()
    {
        return view('organizations.index');
    }

    // public function create_organization()
    // {
    //     return view('user.organization.create');
    // }
}
