<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use App\Models\MpesaTranscations;

class BusinessSettingsController extends Controller
{

    public function stkPush(Request $request)
    {
        $request->validate([
             'phone' => 'required|string|min:10',
    'amount' => 'required|numeric|min:1'
        ]);

        $business = Auth::user()->business;

        $shortCode = $business->mpesa_short_code;
        $consumerKey = decrypt($business->mpesa_consumer_key);
        $consumerSecret = decrypt($business->mpesa_consumer_secret);
        $passkey = decrypt($business->mpesa_passkey);
        $amount = $request->amount; // Replace with actual cart total logic if needed

        $timestamp = now()->format('YmdHis');
        $password = base64_encode($shortCode . $passkey . $timestamp);

        $tokenResponse = Http::withBasicAuth($consumerKey, $consumerSecret)
            ->get('https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials');

        $accessToken = $tokenResponse['access_token'] ?? null;

        $payload = [
            'BusinessShortCode' => $shortCode,
            'Password' => $password,
            'Timestamp' => $timestamp,
            'TransactionType' => 'CustomerPayBillOnline',
            'Amount' => $request->amount,
            'PartyA' => $request->phone,
            'PartyB' => $shortCode,
            'PhoneNumber' => $request->phone,
            'CallBackURL' => route('mpesa.callback', ['business' => $business->id]),
            'AccountReference' => 'POS-' . now()->timestamp,
            'TransactionDesc' => 'Point of Sale Payment',
        ];

        $response = Http::withToken($accessToken)
            ->post('https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest', $payload);

        $data = $response->json();

        MpesaTransaction::create([
            'business_id' => $business->id,
            'phone' => $request->phone,
            'checkout_request_id' => $data['CheckoutRequestID'] ?? null,
            'merchant_request_id' => $data['MerchantRequestID'] ?? null,
            'response_code' => $data['ResponseCode'] ?? null,
            'response_description' => $data['ResponseDescription'] ?? null,
            'amount' => $request->amount,
            'transaction_status' => $data['ResponseCode'] === '0' ? 'pending' : 'failed',
            'raw_response' => json_encode($data),
        ]);

        return response()->json($data);
    }

    public function handleCallback(Request $request, $businessId)
    {
        $content = $request->getContent();
        $data = json_decode($content, true);

        $checkoutRequestId = $data['Body']['stkCallback']['CheckoutRequestID'] ?? null;
        $resultCode = $data['Body']['stkCallback']['ResultCode'] ?? null;

        $transaction = MpesaTransaction::where('checkout_request_id', $checkoutRequestId)->first();

        if ($transaction) {
            $transaction->transaction_status = $resultCode === 0 ? 'success' : 'failed';
            $transaction->raw_response = json_encode($data);
            $transaction->save();
        }

        return response()->json(['status' => 'ok']);
    }
      // Existing methods: editMpesa and updateMpesa
      public function editMpesa()
      {
          $business = Auth::user()->business;
          return view('business.settings.edit_mpesa', compact('business'));
      }
  
      public function updateMpesa(Request $request)
      {
          $request->validate([
              'mpesa_short_code' => 'required|string',
              'mpesa_consumer_key' => 'required|string',
              'mpesa_consumer_secret' => 'required|string',
              'mpesa_passkey' => 'required|string',
              'mpesa_initiator_name' => 'nullable|string',
              'mpesa_security_credential' => 'nullable|string',
          ]);
  
          $business = Auth::user()->business;
  
          $business->update([
              'mpesa_short_code' => $request->mpesa_short_code,
              'mpesa_consumer_key' => encrypt($request->mpesa_consumer_key),
              'mpesa_consumer_secret' => encrypt($request->mpesa_consumer_secret),
              'mpesa_passkey' => encrypt($request->mpesa_passkey),
              'mpesa_initiator_name' => $request->mpesa_initiator_name,
              'mpesa_security_credential' => $request->mpesa_security_credential ? encrypt($request->mpesa_security_credential) : null,
          ]);
  
          return back()->with('success', 'M-Pesa settings updated successfully.');
      }
  
      // New method: createMpesa
      public function createMpesa()
      {
          $business = Auth::user()->business;
          return view('business.settings.mpesa', compact('business'));
      }
  
      // New method: storeMpesa
      public function storeMpesa(Request $request)
      {
          $request->validate([
              'mpesa_short_code' => 'required|string|unique:businesses,mpesa_short_code',
              'mpesa_consumer_key' => 'required|string',
              'mpesa_consumer_secret' => 'required|string',
              'mpesa_passkey' => 'required|string',
              'mpesa_initiator_name' => 'nullable|string',
              'mpesa_security_credential' => 'nullable|string',
          ]);
  
          $business = Auth::user()->business;
  
          try {
              $business->update([
                  'mpesa_short_code' => $request->mpesa_short_code,
                  'mpesa_consumer_key' => encrypt($request->mpesa_consumer_key),
                  'mpesa_consumer_secret' => encrypt($request->mpesa_consumer_secret),
                  'mpesa_passkey' => encrypt($request->mpesa_passkey),
                  'mpesa_initiator_name' => $request->mpesa_initiator_name,
                  'mpesa_security_credential' => $request->mpesa_security_credential ? encrypt($request->mpesa_security_credential) : null,
              ]);
  
              return redirect()->route('business.settings.mpesa')->with('success', 'M-Pesa settings created successfully.');
          } catch (\Exception $e) {
              Log::error('Error creating M-Pesa settings: ' . $e->getMessage(), ['exception' => $e]);
              return back()->with('error', 'Failed to create M-Pesa settings. Please try again.');
          }
      }
}
