<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function index()
    {
        $unitIds = Auth::user()->units()->pluck('units.id');
        $ratings = Rating::whereIn('unit_id', $unitIds)->with('user', 'unit')->paginate(15);

        return view('employee.ratings.index', compact('ratings'));
    }

    public function show(Rating $rating)
    {
        $this->authorize('view', $rating);
        return view('employee.ratings.show', compact('rating'));
    }
}
