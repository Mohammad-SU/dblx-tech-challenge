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
        $type = $request->input('type');
        
        $query = Item::query();
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }
        
        if ($type) {
            $query->where('content_type', $type);
        }
        
        return Inertia::render('Dashboard', [
            'items' => $query->oldest()->limit(10)->get(),
            'filters' => [
                'search' => $search,
                'type' => $type,
            ],
        ]);
    }

    public function destroy(Item $item, Request $request)
    {
        $item->delete();
    
        // Get the current filters from the request
        $search = $request->input('search');
        $type = $request->input('type');
        
        // Redirect back to dashboard with the filters preserved in the URL
        return redirect()->route('dashboard', [
            'search' => $search,
            'type' => $type
        ])->with('success', 'Item deleted successfully.');
    }
}