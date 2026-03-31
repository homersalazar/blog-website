<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateAvatarRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateProfileInfoRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profile.index');
    }

    public function updateInfo(UpdateProfileInfoRequest $request)
    {
        DB::beginTransaction();
        try {
            Auth::user()->update($request->only('name', 'email'));

            DB::commit();
            return back()->with('success', 'Profile updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('update info error: ', ['exception' => $e]);
            return redirect()->back()->withErrors([
                'error' => 'Something went wrong. Please try again later.'
            ])->withInput();
        }
    }

    public function updateAvatar(UpdateAvatarRequest $request)
    {
        DB::beginTransaction();
        try {

            if ($request->hasFile('avatar')) {
                $uploadedFile = $request->file('avatar');
                $filename = Auth::id() . '.' . $uploadedFile->extension();
                $path = 'public/avatar/';

                // delete old
                if (Auth::user()->avatar && Storage::exists($path . Auth::user()->avatar)) {
                    Storage::delete($path . Auth::user()->avatar);
                }

                // store new
                $uploadedFile->storeAs($path, $filename);

                Auth::user()->avatar = $filename;
            }

            Auth::user()->save();

            DB::commit();
            return back()->with('success', 'Avatar updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('update avatar error: ', ['exception' => $e]);
            return redirect()->back()->withErrors([
                'error' => 'Something went wrong. Please try again later.'
            ])->withInput();
        }
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'Current password does not match.']);
        }

        Auth::user()->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }
}
