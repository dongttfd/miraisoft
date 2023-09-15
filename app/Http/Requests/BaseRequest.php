<?php

namespace App\Http\Requests;

use App\Http\Responses\ResponseCode;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class BaseRequest extends FormRequest implements ResponseCode
{
    /**
     * @var int
     */
    protected $code = self::ERROR_CODE;

    /**
     * @var string
     */
    protected $ruleNamespace = 'App\Rules';

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        if (!$this->expectsJson()) {
            return parent::failedValidation($validator);
        }

        $this->assignFailedValidatorStatusCode($validator->failed());

        throw new HttpResponseException(response()->json(
            [
                'code' => $this->code,
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors(),
            ],
            Response::HTTP_UNPROCESSABLE_ENTITY
        ));
    }

    /**
     * Map error field validation to code
     *
     */
    protected function assignFailedValidatorStatusCode(array $failed)
    {
        $map = $this->mapStatusCodes();

        foreach ($map as $field => $codes) {
            if (!($errorRules = ($failed[$field] ?? null))) {
                continue;
            }

            $rules = array_map(function ($rule) {
                return strpos($rule, $this->ruleNamespace) === false
                    ? Str::snake($rule)
                    : $rule;
            }, array_keys($errorRules));

            $rule = array_intersect($rules, array_keys($codes))[0] ?? null;

            if ($rule) {
                $this->code = $codes[$rule];
                break;
            }
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
        return [];
    }

    /**
     * Handle a failed authorization attempt.
     *
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedAuthorization()
    {
        throw new HttpResponseException(response()->json(
            [
                'code' => self::PERMISSION_DENIED,
                'message' => __('app.messages.error.unauthorized'),
            ],
            Response::HTTP_FORBIDDEN
        ));
    }
}
