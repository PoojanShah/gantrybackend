<?php


namespace App\Http\Request;


use Illuminate\Foundation\Http\FormRequest;

class InstallationSubscriptionRequest extends FormRequest
{

    public function messages()
    {
        return [
            'localId.required' => 'localId is required!',
            'localId.exists' => 'No subscription with such installation_id!',
        ];
    }


    public function rules(): array
    {

        return [
            'localId' => 'required|exists:customers,installation_id',
        ];
    }
}