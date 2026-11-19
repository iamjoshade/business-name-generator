<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use App\Services\BusinessNameService;
use Illuminate\Validation\Rule;


class GenerateNamesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
             'keywords' => ['required', 'string', 'max:100', 'regex:/[a-zA-Z]{2,}/'],
            'industry' => ['required', 'string', Rule::in(app(BusinessNameService::class)->industries())],
        ];
    }


    public function messages(): array
    {
        return [
            'keywords.required' => 'Tell us a keyword or your business idea.',
            'keywords.regex'    => 'Use at least one word made of letters.',
            'keywords.max'      => 'Keep your keywords under 100 characters.',
            'industry.required' => 'Choose an industry.',
            'industry.in'       => 'Choose an industry from the list.',
        ];
    }
}