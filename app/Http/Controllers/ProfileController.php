<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // dd($request->all());
        
        $validated = $request->validated();

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        if ($request->avatar) {
            if (!empty($request->user()->avatar)) {
                // Storage::disk('public')->delete($request->user()->avatar);
                Storage::disk(config('filesystems.default_public_disk'))->delete($request->user()->avatar);
            }
            $newFilename = Str::after($request->avatar, 'tmp/');
            $pathNewFilename = 'img/' . $newFilename;
            // Storage::disk('public')->move($request->avatar, $pathNewFilename);
            Storage::disk(config('filesystems.default_public_disk'))->move($request->avatar, $pathNewFilename);
            $validated['avatar'] = $pathNewFilename;
        }
        
        // upload laravel
        // if ($request->hasFile('avatar')) {
        //     if (!empty($request->user()->avatar)) {
        //         // Delete old avatar if exists
        //         Storage::disk('public')->delete($request->user()->avatar);
        //     }
        //     $path = $request->file('avatar')->store('img', 'public');
        //     $validated['avatar'] = $path;
        // }

        $request->user()->update($validated);

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    // public function upload(Request $request): RedirectResponse
    public function upload(Request $request)
    {
        // $request->validate([
        //     'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        // ]);

        // $user = $request->user();

        if ($request->hasFile('avatar')) {
            // if (!empty($user->avatar)) {
            //     // Delete old avatar if exists
            //     Storage::disk('public')->delete($user->avatar);
            // }
            $path = $request->file('avatar')->store('tmp', config('filesystems.default_public_disk'));
            // $request->file('avatar')->store('tmp', 'public');
            // $user->avatar = $path;
            // $user->save();
        }

        return $path; // or return Redirect::route('profile.edit')->with('status', 'Avatar uploaded successfully.');

        // return Redirect::route('profile.edit')->with('status', 'Avatar uploaded successfully.');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
