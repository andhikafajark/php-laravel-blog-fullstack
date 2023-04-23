<?php

namespace App\Http\Requests\Admin\Blog\Post;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
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
        return [
            'categories' => 'required|array|min:1',
            'categories.*' => 'required|string|distinct|exists:categories,id',
            'title' => 'bail|required|string|max:255|unique:posts',
            'subtitle' => 'bail|required|string|max:255',
            'content' => 'bail|required|string',
            'headline_image' => 'bail|required|image',
            'is_active' => 'bail|required|boolean'
        ];
    }
}
