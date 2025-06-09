<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BusinessSettingsController extends Controller
{
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
