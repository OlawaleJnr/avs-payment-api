<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CardChargeRequest extends FormRequest
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
      'amount' => ['required', 'integer', 'digits_between:1,12'],
      'card_number' => ['required', 'integer', 'digits_between:13,16'],
      'cvv' => ['required', 'string'],
      'expiry_month' => ['required', 'string'],
      'expiry_year' => ['required', 'string'],
      'authorization.mode' => ['required', 'string'],
      'authorization.pin' => ['required', 'integer', 'digits:4'],
      'email' => ['required', 'email'],
      'currency' => ['required', 'string'],
      'fullname' => ['required', 'string'],
      'redirect_url' => ['required'],
      'country' => ['required', 'string'],
      'tx_ref' => ['required', 'string'],
    ];
  }

  protected function failedValidation(Validator $validator)
  {
    $errors = $validator->errors();
    $response = response()->json($errors->messages(), 422);
    throw new HttpResponseException($response);
  }
}
