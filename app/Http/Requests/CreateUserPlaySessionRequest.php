<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Create User Play Session Request.
 *
 * @property string $discogs_id
 * @property string $length_in_minutes
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
            'length_in_minutes' => 'numeric|min:1'
        ];
    }
}
