<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\Account\SettingsEmailRequest;
use App\Http\Requests\Account\SettingsInfoRequest;
use App\Http\Requests\Account\SettingsPasswordRequest;
use App\Models\UserInfo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(): View
    {
        $info = auth()->user()->info;

        // get the default inner page
        return view('pages.account.settings.settings', compact('info'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SettingsInfoRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        auth()->user()->update([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
        ]);

        // save on user info
        $info = UserInfo::where('user_id', auth()->user()->id)->first();

        if ($info === null) {
            // create new model
            $info = new UserInfo();
        }

        // attach this info to the current user
        $info->user()->associate(auth()->user());

        $profileFields = [
            'company',
            'phone',
            'website',
            'country',
            'language',
            'timezone',
            'currency',
            'communication',
            'marketing',
        ];

        foreach ($request->only($profileFields) as $key => $value) {
            if (is_array($value)) {
                $value = serialize($value);
            }
            $info->$key = $value;
        }

        // include to save avatar
        if ($avatar = $this->upload($request)) {
            if ($info->avatar) {
                Storage::disk('public')->delete($info->avatar);
            }
            $info->avatar = $avatar;
        }

        if ($request->boolean('avatar_remove')) {
            Storage::disk('public')->delete($info->avatar);
            $info->avatar = null;
        }

        $info->save();

        return redirect()->intended('account/settings')->with('status', __('Dados da conta atualizados com sucesso.'));
    }

    /**
     * Function for upload avatar image
     *
     * @param  Request  $request
     * @param  string  $folder
     * @param  string  $key
     *
     * @return string|null
     */
    public function upload(Request $request, string $folder = 'images', string $key = 'avatar')
    {
        $file = null;
        if ($request->hasFile($key)) {
            $file = Storage::disk('public')->putFile($folder, $request->file($key), 'public');
        }

        return $file;
    }

    /**
     * Function to accept request for change email
     *
     * @param  SettingsEmailRequest  $request
     */
    public function changeEmail(SettingsEmailRequest $request)
    {
        // prevent change email for demo account
        if ($request->input('current_email') === 'demo@demo.com') {
            return redirect()->intended('account/settings');
        }

        auth()->user()->update(['email' => $request->input('email')]);

        if ($request->expectsJson()) {
            return response()->json($request->all());
        }

        return redirect()->intended('account/settings');
    }

    /**
     * Function to accept request for change password
     *
     * @param  SettingsPasswordRequest  $request
     */
    public function changePassword(SettingsPasswordRequest $request)
    {
        // prevent change password for demo account
        if ($request->input('current_email') === 'demo@demo.com') {
            return redirect()->intended('account/settings');
        }

        auth()->user()->update(['password' => Hash::make($request->input('password'))]);

        if ($request->expectsJson()) {
            return response()->json($request->all());
        }

        return redirect()->intended('account/settings');
    }
}
