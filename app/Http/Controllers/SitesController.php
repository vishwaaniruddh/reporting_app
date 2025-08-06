<?php

namespace App\Http\Controllers;

use App\Models\Postgres\PgSite;
use Illuminate\Http\Request;

class SitesController extends Controller
{
    public function index(Request $request)
    {
        $query = PgSite::query()
            ->select([
                'sites.SN',
                'sites.OldPanelID',
                'sites.NewPanelID',
                'sites.Customer',
                'sites.Bank',
                'sites.ATMID',
                'sites.SiteAddress',
                'sites.City',
                'sites.State',
                'sites.Zone',
                'sites.DVRIP',
                'sites.Panel_Make'
            ]);

        // Search functionality
        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ATMID', 'ilike', "%{$search}%")
                  ->orWhere('Customer', 'ilike', "%{$search}%")
                  ->orWhere('Bank', 'ilike', "%{$search}%")
                  ->orWhere('City', 'ilike', "%{$search}%")
                  ->orWhere('State', 'ilike', "%{$search}%")
                  ->orWhere('OldPanelID', 'ilike', "%{$search}%")
                  ->orWhere('NewPanelID', 'ilike', "%{$search}%");
            });
        }

        // Sorting
        $sort = $request->input('sort', 'SN');
        $direction = $request->input('direction', 'asc');
        $query->orderBy($sort, $direction);

        $sites = $query->paginate(15);

        return view('sites.index', compact('sites'));
    }

    public function show($id)
    {
        $site = PgSite::findOrFail($id);
        return view('sites.show', compact('site'));
    }
}
