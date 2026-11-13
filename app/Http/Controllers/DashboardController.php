<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $stats = [
            'total' => $user->assets()->count(),
            'active' => $user->assets()->where('status', 'active')->count(),
            'public' => $user->assets()->where('is_public', true)->count(),
            'maintenance' => $user->assets()->where('status', 'maintenance')->count(),
        ];

        $brokenCount = $user->assets()
            ->where(function ($query) {
                $query->where('status', 'broken')
                    ->orWhere('condition', 'broken');
            })
            ->count();

        $expiringWarrantyCount = $user->assets()
            ->whereNotIn('status', ['sold', 'retired', 'lost'])
            ->whereBetween('warranty_expires_at', [
                today()->toDateString(),
                today()->addDays(30)->toDateString(),
            ])
            ->count();

        return view('dashboard', compact(
            'user',
            'stats',
            'brokenCount',
            'expiringWarrantyCount'
        ));
    }
}