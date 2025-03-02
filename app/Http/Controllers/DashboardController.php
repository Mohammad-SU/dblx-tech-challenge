<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $search = $request->input('search');
        
        $query = Item::query();
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }
        
        return Inertia::render('Dashboard', [
            'items' => $query->oldest()->limit(10)->get(),
            'filters' => [
                'search' => $search,
            ],
        ]);
    }
}