<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleSearchRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'search' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'source' => 'nullable|string|max:255',
            'author' => 'nullable|string|max:255',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'limit' => 'nullable|integer|min:1|max:20',
            'page' => 'nullable|integer|min:1',
        ];
    }

    /**
     * Customize the error messages.
     */
    public function messages(): array
    {
        return [
            'date_from.before_or_equal' => 'The start date must be before or equal to the end date.',
            'date_to.after_or_equal' => 'The end date must be after or equal to the start date.',
        ];
    }
}
