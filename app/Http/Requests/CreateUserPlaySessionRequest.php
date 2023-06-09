<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Create User Play Session Request.
 *
 * @property string $discogs_id
 * @property bigInteger $stylus_id
 */
class CreateUserPlaySessionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'discogs_id' => 'required|exists:albums_cache,discogs_id',
            'stylus_id' => 'required|exists:user_styluses,id'
        ];
    }
}
