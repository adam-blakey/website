<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Concert;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ConcertController extends Controller
{
    public function index()
    {
        return view('dashboard.concerts.index', ['concerts' => Concert::orderByDesc('start_datetime')->paginate(20)]);
    }

    public function create()
    {
        return view('dashboard.concerts.form', ['concert' => new Concert]);
    }

    public function store(Request $request)
    {
        $concert = Concert::create($this->validated($request, new Concert));

        return redirect()->route('dashboard.concerts.index')->with('status', "Created “{$concert->name}”.");
    }

    public function edit(Concert $concert)
    {
        return view('dashboard.concerts.form', ['concert' => $concert]);
    }

    public function update(Request $request, Concert $concert)
    {
        $concert->update($this->validated($request, $concert));

        return redirect()->route('dashboard.concerts.index')->with('status', "Saved “{$concert->name}”.");
    }

    public function destroy(Concert $concert)
    {
        $concert->delete();

        return redirect()->route('dashboard.concerts.index')->with('status', "Deleted “{$concert->name}”.");
    }

    private function validated(Request $request, Concert $concert): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'slug' => ['required', 'alpha_dash', 'max:255', Rule::unique('concerts')->ignore($concert)],
            'ensemble' => ['required', Rule::in(array_keys(Concert::ENSEMBLES))],
            'start_datetime' => 'required|date',
            'end_datetime' => 'nullable|date|after_or_equal:start_datetime',
            'location' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'poster_url' => 'nullable|url|max:255',
            'ticket_url' => 'nullable|url|max:255',
            'event_url' => 'nullable|url|max:255',
            'keep_local_edits' => 'boolean',
        ]);
    }
}
