<?php

namespace App\Service;
use GuzzleHttp\Client;

class FlutterwaveService
{
  protected $flutterwavePublicKey;
  protected $flutterwaveSecretKey;
  protected $flutterwaveEncryptionKey;

  public function __construct()
  {
    $this->flutterwavePublicKey = env('FLUTTERWAVE_PUBLIC_KEY');
    $this->flutterwaveSecretKey = env('FLUTTERWAVE_SECRET_KEY');
    $this->flutterwaveEncryptionKey = env('FLUTTERWAVE_ENCRYPTION_KEY');
  }

  protected $flutterwave_init_url = "https://api.flutterwave.com/v3/charges?type=card";
  protected $flutterwave_validate_card_charge_url = "https://api.flutterwave.com/v3/validate-charge";


  function encrypt(array $payload) : string
  {
    $encrypted = openssl_encrypt(json_encode($payload), 'DES-EDE3', $this->flutterwaveEncryptionKey, OPENSSL_RAW_DATA);
    return base64_encode($encrypted);
  }

  public function generateTrxRef() : string
  {
    return 'FLWTRX-' . (mt_rand(2, 101) + time());
  }

  public function handleCardCharge($encryptData)
  {
    $client = new Client();
    $response = $client->request('POST', $this->flutterwave_init_url, [
      'headers' => [
        'Accept' => 'application/json',
        'Accept-Charset' => 'application/json',
        "Authorization" => 'Bearer ' . $this->flutterwaveSecretKey,
        "Content-Type" => "application/json",
      ],
      'json' => [
        'client' => $encryptData,
      ],
    ]);
    $body = json_decode($response->getBody());
    return $body;
  }

  public function validateCardOTP($otp, $reference_no)
  {
    $client = new Client();
    $response = $client->request('POST', $this->flutterwave_validate_card_charge_url, [
      'headers' => [
        'Accept' => 'application/json',
        'Accept-Charset' => 'application/json',
        "Authorization" => 'Bearer ' . $this->flutterwaveSecretKey,
        "Content-Type" => "application/json",
      ],
      'json' => [
        'otp' => $otp,
        'flw_ref' => $reference_no,
        'type' => 'card',
      ],
    ]);
    $body = json_decode($response->getBody());
    return $body;
  }
}
