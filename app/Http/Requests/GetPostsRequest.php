<?php

namespace App\Http\Requests;

use App\Models\Enums\SortTypeEnum;
use Illuminate\Foundation\Http\FormRequest;

class GetPostsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'page' => 'required|min:1',
            'sort' => 'string|in:' . implode(',', SortTypeEnum::getAllSortTypes()),
        ];
    }
}
