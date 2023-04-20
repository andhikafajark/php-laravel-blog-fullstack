<?php

namespace App\Http\Requests\Admin\Reference\Category;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return !auth()->guest();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $category = $this->route('category');

        return [
            'title' => $this->input('title') !== $category->title ? 'bail|required|string|max:255|unique:categories' : '',
            'type' => 'bail|required|in:post'
        ];
    }
}
