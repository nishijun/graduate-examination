<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditStepRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
          'p_title' => 'required',
          'p_category_id' => 'required',
          'p_achievement_time' => 'required',
          'p_content' => 'required',
          'p_thumbnail' => 'max:2048',
          'title.*' => 'required',
          'content.*' => 'required'
        ];
    }

    public function messages() {
      return [
        'p_title.required' => '親STEPのタイトルは必須です。',
        'p_category_id.required' => '親STEPのカテゴリーは必須です。',
        'p_achievement_time.required' => '親STEPの目安達成時間は必須です。',
        'p_content.required' => '親STEPの内容は必須です。',
        'title.*.required' => '子STEPのタイトルは必須です。',
        'content.*.required' => '子STEPの内容は必須です。',
      ];
    }
}
