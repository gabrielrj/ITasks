<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaskPostAndPutRequest extends FormRequest
{
    use ConvertResponseToJson;

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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string'],
            'description' => ['required', 'string', 'max:1024'],
            'status' => [
                'required',
                'string',
                Rule::in(['created', 'started', 'cancelled', 'completed']),
            ],
            'users_id' => [
                'required',
                'exists:App\Models\User,uuid'
            ]
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Informe o título da tarefa.',
            'description.required' => 'Insira uma descrição da tarefa.',
            'status.required' => 'Informe o status da tarefa.',
            'status.in' => 'Informe um status válido para a tarefa.',
            'users_id.required' => 'Escolha o usuário da tarefa.',
            'users_id.exists' => 'O usuário selecionado não existe.',
        ];
    }
}
