<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    // Optional: require auth middleware
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function index()
    {
        $reports = Report::paginate(10);
        return view('reports.index', compact('reports'));
    }

    public function create()
    {
        return view('reports.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|boolean',
        ]);

        Report::create($request->only('name', 'status'));

        return redirect()->route('reports.index')->with('success', 'Customer created!');
    }

    public function show(Report $report)
    {
        return view('reports.show', compact('client'));
    }

    public function edit(Report $report)
    {
        return view('reports.edit', compact('client'));
    }

    public function update(Request $request, Report $report)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|boolean',
        ]);

        $report->update($request->only('name', 'status'));

        return redirect()->route('reports.index')->with('success', 'Customer updated!');
    }

    public function destroy(Report $report)
    {
        $report->delete();

        return redirect()->route('reports.index')->with('success', 'Client deleted successfully!');
    }
}
