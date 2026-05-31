<?php

namespace App\Http\Controllers;

use App\Models\Chirp;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ChirpController extends Controller
{
    use AuthorizesRequests;
    public function index(){
        $chirps = Chirp::with('user')->latest()->get();
        return view('home', ['chirps' => $chirps]);
    }

    public function store(Request $request)
    {
    //    $validated = $request->validate([
    //         'message' => 'required|string|max:255',
    //     ], [
    //         'message.required' => 'Please write something to chirp!',
    //         'message.max' => 'Chirps must be 255 characters or less.',
    //     ]);

    $user = $request->user();
    $validated = $request->validate([
        'message' => [
            'required',
            'string',
            'max:255',
            Rule::unique('chirps')->where(function ($query) use ($user) {
                return $query->where('user_id', $user->id);
            })
        ],
    ]);

    auth()->user()->chirps()->create($validated);

        return redirect('/')->with('success', 'Your chirp has been posted!');
    }

    public function edit(Chirp $chirp)
    {
        $this->authorize('update', $chirp);
        return view('chirps.edit', compact('chirp'));
    }

    public function update(Request $request, Chirp $chirp)
    {
        $this->authorize('update', $chirp);
        $validated = $request->validate([
            'message' => 'required|string|max:255',
        ], [
            'message.required' => 'Please write something to chirp!',
            'message.max' => 'Chirps must be 255 characters or less.',
        ]);

        $chirp->update($validated);

        return redirect('/')->with('success', 'Your chirp has been updated!');
    }

    public function destroy(Chirp $chirp)
    {
        $this->authorize('delete', $chirp);
        $chirp->delete();

        return redirect('/')->with('success', 'Your chirp has been deleted!');
    }
}
