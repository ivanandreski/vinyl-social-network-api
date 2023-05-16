<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Add Friend Request.
 *
 * @property bigInteger $stylus_id
 */
class GetUserStylusHoursRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'stylus_id' => 'required|exists:user_styluses,id'
        ];
    }
}
