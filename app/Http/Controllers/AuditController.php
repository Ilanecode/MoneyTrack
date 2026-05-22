<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    public function index(Request $request)
    {
        $query = Audit::with('user')->latest();

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        if ($request->filled('time')) {
            $query->where('created_at', 'like', '% ' . $request->time . '%');
        }

        $audits = $query->paginate(10)->withQueryString();
        return view('audits.index', compact('audits'));
    }
}
