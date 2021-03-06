<?php
/**
 * Created by Olimar Ferraz
 * webmaster@z1lab.com.br
 * Date: 20/09/2018
 * Time: 17:59
 */

namespace Z1lab\JsonApi\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest as LaravelFormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Z1lab\JsonApi\Exceptions\ErrorObject;

abstract class ApiFormRequest extends LaravelFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    abstract public function rules();

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    abstract public function authorize();

    /**
     * Fix for FormRequest throws specific field validation errors in JSON
     *
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();

        $errors = new ErrorObject($errors, JsonResponse::HTTP_UNPROCESSABLE_ENTITY, $errors);

        throw new HttpResponseException(response()->json($errors->toArray(), JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
    }
}
