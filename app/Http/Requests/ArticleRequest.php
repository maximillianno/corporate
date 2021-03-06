<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //мы сразу вызываем функцию проверки определенного права, без посредника в лице Лары
        return \Auth::user()->canDo('ADD_ARTICLES');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {


        return [
            //
            'title' => 'required|max:255',
            'text' => 'required',
            'category_id' => 'required|integer'
        ];
    }
    protected function getValidatorInstance()
    {
        $validator = parent::getValidatorInstance(); //
        $validator->sometimes('articles', 'unique:articles|max:255', function ($input){

            if ($this->route()->hasParameter('alias')) {
                $model = $this->route()->parameter('alias');
                //если пользователь не стер алиас и изменил его, то замыкание возвращает 1 и валидирует
                return ($model->alias != $input->alias) && !empty($input->alias);
            }
            return !empty($input->alias);
        });
        return $validator;
    }
}
