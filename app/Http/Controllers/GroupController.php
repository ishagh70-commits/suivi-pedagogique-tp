<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Inertia\Inertia;

class GroupController extends Controller
{
    public function index()
    {
        $groups = Group::query()
            ->withCount('etudiants')
            ->orderBy('name')
            ->get();

        return Inertia::render('Groups/Index', [
            'groups' => $groups,
        ]);
    }
}
