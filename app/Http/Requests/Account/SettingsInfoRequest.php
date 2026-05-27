<?php

namespace App\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;

class SettingsInfoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name'    => 'required|string|max:255',
            'last_name'     => 'required|string|max:255',
            'avatar'        => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'avatar_remove' => 'nullable|boolean',
            'company'       => 'nullable|string|max:255',
            'phone'         => 'nullable|string|max:255',
            'website'       => 'nullable|url|max:255',
            'country'       => 'nullable|string|max:255',
            'language'      => 'nullable|string|max:255',
            'timezone'      => 'nullable|string|max:255',
            'currency'      => 'nullable|string|max:255',
            'communication' => 'nullable|array',
            'marketing'     => 'nullable|boolean',
        ];
    }
}
