<?php

namespace App\Http\Requests\V1;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BulkStoreInvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {

        /** @var User $user */
//        $user = $this->user();
//        return $user != null && $user->tokenCan('create');
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
            '*.customerId' => ['required', 'integer'],
            '*.amount' => ['required', 'numeric'],
            '*.status' => ['required',  Rule::in(['B', 'P', 'V', 'b','p', 'v'])],
            '*.billedDate' => ['required', 'date_format:Y-m-d H:i:s'],
            '*.paidDate' => ['required', 'date_format:Y-m-d H:i:s', 'nullable']
        ];
    }

    protected function prepareForValidation()
    {
        $data = [];
        foreach ($this->toArray() as $item)
        {
            $item['customer_id'] = $item['customerId'] ?? null;
            $item['billed_date'] = $item['billedDate'] ?? null;
            $item['paid_date'] = $item['paidDate'] ?? null;

            $data[] = $item;
        }
        $this->merge($data);
    }
}
