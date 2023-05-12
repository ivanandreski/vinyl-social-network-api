<?php

namespace App\Http\Requests;

use App\Models\Enums\UserProfileVisibilityEnum;
use Illuminate\Foundation\Http\FormRequest;

class ChangeProfileVisibilityRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'visibility_type' => 'required|in:' . implode(',', UserProfileVisibilityEnum::getAllVisibilities()),
        ];
    }
}
