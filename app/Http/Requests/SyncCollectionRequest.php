<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SyncCollectionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            // 'albums' => 'present|array',
            // 'albums.*.discogs_id' => 'required',
            // 'albums.*.discogs_resource_url' => 'required',
            // 'albums.*.discogs_release_url' => 'required',
            // 'albums.*.image_url' => 'required',
            // 'albums.*.title' => 'required',
            // 'albums.*.artist_name' => 'required',
            // 'albums.*.release_year' => 'required|numeric',
            // 'albums.*.length_in_seconds' => 'required|numeric',
        ];
    }
}
