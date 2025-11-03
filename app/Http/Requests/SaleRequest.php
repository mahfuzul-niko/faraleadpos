<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaleRequest extends FormRequest
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
            'user_id' => 'nullable|integer',
            'file_no' => 'nullable|integer',
            'saller_id' => 'nullable|integer',
            'sale_date' => 'nullable|date',
            'name' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'mobile' => 'nullable|string|max:15',
            'bill_type' => 'nullable|string',
            'bill_amount' => 'nullable|integer',
            'installation_charge' => 'nullable|integer',
            'installation_date' => 'nullable|date',
            'installer_id' => 'nullable|integer',
            'advance' => 'nullable|integer',
            'due' => 'nullable|integer',
            'note' => 'nullable|string',
            'status' => 'nullable|string',
        ];
    }
}
