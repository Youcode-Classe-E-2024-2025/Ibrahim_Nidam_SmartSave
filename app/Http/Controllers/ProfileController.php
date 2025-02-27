<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function verifyPin(Request $request)
{
    $validated = $request->validate([
        'profile_id' => 'required|exists:profiles,id',
        'pin'        => 'required|digits:4',
    ]);

    $profile = Profile::where('id', $validated['profile_id'])
                      ->where('user_id', Auth::id())
                      ->firstOrFail();

    if (Hash::check($validated['pin'], $profile->profile_pin)) {
        // Store the selected profile in the session
        session(['selected_profile_id' => $profile->id]);
        return response()->json(['success' => true]);
    }

    return response()->json(['success' => false, 'message' => 'Incorrect PIN.'], 422);
}


    public function selectProfile(Request $request, $profileId){
        $profile = Profile::where('id', $profileId)
                            ->where('user_id', Auth::id())
                            ->firstOrFail();

        session(['selected_profile_id' => $profile->id]);

        return redirect('/dashboard');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $profiles = $user->profiles;
        return view ('profiles.index', compact('profiles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request; ensure 'pin' is exactly 4 digits and numeric.
        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'pin'   => ['required', 'digits:4'],
            'color' => ['required', 'string'],
        ]);
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Use the relationship method (profiles()) to create a new profile
        $newProfile = $user->profiles()->create([
            'name'        => $validated['name'],
            'profile_pin' => Hash::make($validated['pin']), 
            'color'       => $validated['color'],
            'role'        => 'member',
        ]);

        return response()->json(['success' => true, 'profile' => $newProfile]);
    }


    /**
     * Display the specified resource.
     */
    public function show(Profile $profile)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Profile $profile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Profile $profile)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Profile $profile)
    {
        //
    }
}
