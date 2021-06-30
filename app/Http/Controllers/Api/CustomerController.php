<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helper\LibHelper;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    /**
     * Function to register for new customers
     * Inputs: customer_name, phone_number, customer_email
     * Outputs: @array status:1, 0
     * @structlooper at 2021
     * */
    public function store(Request $request): array
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'full_name' => 'required',
            'phone' => 'required|numeric|digits_between:10,10|unique:customers,phone',
            'email' => 'required|email|regex:/^[a-zA-Z]{1}/|unique:customers,email',
        ]);

        if ($validator->fails()) {
            return ['status' => 0,'message' => $validator->errors()->first(),'result' => []];
        }
        $input['otp'] = mt_rand(100000, 999999);
        $input['status'] = 1;
        $input['created_at'] = date('Y-m-d H:i:s');
        Customer::create($input);
        if(LibHelper::sendOtpFunction($input['phone'],$input['otp'])){
            return ['status'=> 1,'message' => 'Verification otp sent on your phone','result' => []];
        }else{
            return ['status' => 0,'message' => 'Otp not sent','result' => []];
        }

    }
    /**
     * Function to verify otp sent on mobile
     * Inputs:  phone_number, otp
     * Outputs: @array status:1, 0
     * @structlooper at 2021
     * */
    public function otp(Request $request): array
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'phone' => 'required|numeric|digits_between:10,10',
            'otp' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return ['status' => 0,'message' => $validator->errors()->first(),'data' => []];
        }
        $customer = Customer::where('phone',$input['phone'])->first();
        if ($customer->otp === $input['otp']){
//            $token = JWTAuth::fromUser($customer);
            if (is_object($customer)) {
//                $customer->membership = DB::table('memberships')->select('title')->where('id',$customer->membership)->first();
//                $customer->default_address = DB::table('addresses')->select('id','address','latitude','longitude')->where('id',$customer->default_address)->first();
                return [
                    "result" => $customer,
                    "message" => 'Otp verified Successfully',
                    "status" => 1
                ];
            } else {
                return [
                    "message" => 'Sorry, something went wrong !',
                    "status" => 0,
                    'result' => []
                ];
            }
        }else{
            return ['status' => 0,'message' => 'Wrong Otp','result' => []];
        }

    }

    /**
     * Function to Login for customers
     * Inputs: phone_number
     * Outputs: @array status:1, 0
     * @structlooper at 2021
     * */
    public function login(Request $request): array
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'phone' => 'required|numeric|digits_between:9,20',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }

        $credentials = request(['phone']);
        $customer = Customer::where('phone',$credentials['phone'])->first();

        if (!($customer)) {
            return ['status' => 0, 'message' => 'customer not found!','result' => []];
        }
        $input['otp'] = mt_rand(100000, 999999);
        if($customer->status == 1){
            Customer::where('id',$customer->id)->update(['otp' =>  $input['otp']]);
            if(LibHelper::sendOtpFunction($input['phone'],$input['otp'])){
                return ['status'=> 1,'message' => 'Verification otp sent on your phone','result' => []];
            }else{
                return ['status' => 0,'message' => 'Otp not sent','result' => []];
            }
        }else{
            return ['status' => 0, 'message' =>  'Your account has been blocked','result' => []];
        }
    }
}
