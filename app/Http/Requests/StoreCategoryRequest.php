<?php

namespace App\Http\Requests;

use App\Models\Category;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreCategoryRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('category_create');
    }

    public function rules()
    {
        return [
            'type' => [
                'required',
            ],
            'icon.*' => ['mimes:jpg,png,jpeg,gif,svg'],
        ];
    }
}
