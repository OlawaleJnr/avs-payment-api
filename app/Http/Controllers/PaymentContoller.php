<?php

namespace App\Http\Controllers;

use App\Http\Responses\ApiErrorResponse;
use Throwable;
use App\Service\FlutterwaveService;
use App\Http\Requests\CardChargeRequest;
use App\Http\Requests\VerifyCardRequest;
use App\Http\Responses\ApiSuccessResponse;

class PaymentContoller extends Controller
{
  public function chargeCard(CardChargeRequest $request)
  {
    try{
      $data = $request->validated();
      $encryptData = (new FlutterwaveService)->encrypt($data);
      $response = (new FlutterwaveService)->handleCardCharge($encryptData);
      return new ApiSuccessResponse($response, ['message' => 'Verify OTP']);
    } catch (Throwable $exception) {
      return new ApiErrorResponse('An error occurred while trying to charge card', $exception);
    }
  }

  public function verifyCardCharge(VerifyCardRequest $request)
  {
    try{
      $data =  $request->validated();
      $response = (new FlutterwaveService)->validateCardOTP($data['otp'], $data['flw_ref']);
      return new ApiSuccessResponse($response, ['message' => 'Card Charge Validated']);
    } catch (Throwable $exception) {
      return new ApiErrorResponse('An error occurred while trying to validate card charge', $exception);
    }
  }
}
