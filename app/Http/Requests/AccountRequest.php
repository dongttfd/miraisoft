<?php

namespace App\Http\Requests;

class AccountRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $rules = [
            'login' => [
                'required',
                'string',
                'max:20',
            ],
            'password' => 'required|string|max:40',
            'phone' => 'nullable|string|regex:^(?!-)[0-9-]+(?!-)$',
        ];

        if ($this->isMethod('POST')) {
            $rules['login'][] = 'unique:accounts,login';
        }

        if ($this->isMethod('PUT')) {
            $rules['login'][] = "unique:accounts,login,{$this->register_id},registerId";
            $rules['register_id'] = 'required|integer|exists:accounts,registerId';
        }

        return $rules;
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if ($this->isMethod('PUT')) {
            $this->merge(['register_id' =>  $this->register_id]);
        }
    }

    /**
     * Map failed of validator to error code
     * Format :['field' => ['rule' => <code>]]
     * Eg: ['username' => ['unique' => 1006]]
     *
     * @return array
     */
    protected function mapStatusCodes()
    {
        return [
            'register_id' => ['exists' => self::ACCOUNT_NOT_EXIST],
            'login' => ['unique' => self::ACCOUNT_LOGIN_MUST_UNIQUE],
        ];
    }
}
