<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class CarsFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @param Request $request
     * @return bool
     */
    public function authorize(Request $request)
    {
        return $request->isMethod('POST') || ($request->isMethod('PUT') && $request->user()->id ==  $request->car->user_id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'model' => "nullable|string|min:1",
            'category' => "nullable|string|min:1",
            'motor_power' => "nullable|string|min:1",
            'ports' => "nullable|integer",
            'board' => "nullable|string|min:1",
            'type_vehicle' => "nullable|string|min:1",
            'year' => "nullable|integer",
            'fuel' => "nullable|string|min:1",
            'mileage' => "nullable|integer",
            'exchange' => "nullable|string|min:1",
            'direction' => "nullable|string|min:1",
            'color' => "nullable|string|min:1",
            'options' => "nullable|string"
        ];
    }
}
