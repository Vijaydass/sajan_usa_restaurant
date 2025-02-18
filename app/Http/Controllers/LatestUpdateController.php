<?php

namespace App\Http\Controllers;

use App\Models\LatestUpdate;
use Illuminate\Http\Request;

class LatestUpdateController extends Controller
{
    public function index()
    {
        $updates = LatestUpdate::when(request('title'),function($q){
            $q->where('title','LIKE','%'.request('title').'%');
        })
        ->when(request('content'),function($q){
            $q->where('content','LIKE','%'.request('content').'%');
        })
        ->latest()
        ->paginate(request('total_records',10));
        return view('latest_updates.index', compact('updates'));
    }

    public function create()
    {
        return view('latest_updates.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'url' => 'nullable|url',
        ]);

        LatestUpdate::create($request->all());
        return redirect()->route('latest-updates.index')->with('success', 'Latest Update created successfully.');
    }

    public function edit(LatestUpdate $latest_update)
    {
        return view('latest_updates.edit', compact('latest_update'));
    }

    public function update(Request $request, LatestUpdate $latest_update)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'url' => 'nullable|url',
        ]);

        $latest_update->update($request->all());
        return redirect()->route('latest-updates.index')->with('success', 'Latest Update updated successfully.');
    }

    public function destroy(LatestUpdate $latest_update)
    {
        $latest_update->delete();
        return redirect()->route('latest-updates.index')->with('success', 'Latest Update deleted successfully.');
    }
}
