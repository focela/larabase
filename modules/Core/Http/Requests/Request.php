<?php
/*
 * Copyright (c) 2024 Focela Technologies. All rights reserved.
 * Use of this source code is governed by a MIT style
 * license that can be found in the LICENSE file.
 */

namespace Modules\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

abstract class Request extends FormRequest
{
    /**
     * Available attributes.
     *
     * @var string
     */
    protected $availableAttributes = '';

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
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        $attributes = trans($this->availableAttributes) ?: [];

        if (! is_array($attributes)) {
            return [];
        }

        return array_map('mb_strtolower', array_dot($attributes));
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        $attributesAndRules = $this->parseRules($this->rules());

        $messages = [];

        foreach ($attributesAndRules as $attributeAndRule) {
            $rule = last(explode('.', $attributeAndRule));

            $messages[$attributeAndRule] = trans("core::validation.{$rule}");
        }

        return $messages;
    }

    /**
     * Parse rules for the given attributes.
     *
     * Gives
     * [
     *  'name' => 'required|size:60',
     * ]
     *
     * Returns
     * [
     *  'name.required',
     *  'name.size',
     * ]
     *
     * @param array $rules
     *
     * @return array
     */
    protected function parseRules(array $rules)
    {
        $attributesAndRules = [];

        foreach ($rules as $attribute => $rulesList) {
            if (! is_array($rulesList)) {
                $rulesList = explode('|', $rulesList);
            }

            foreach ($rulesList as $rule) {
                if ($rule instanceof \Closure) {
                    continue;
                }

                if (str_contains($rule, ':')) {
                    list($rule) = explode(':', $rule, 2);
                }

                $attributesAndRules[] = "{$attribute}.{$rule}";
            }
        }

        return $attributesAndRules;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param Validator $validator
     *
     * @throws ValidationException
     *
     * @return void
     */
    protected function failedValidation(Validator $validator)
    {
        throw new ValidationException($validator);
    }
}
