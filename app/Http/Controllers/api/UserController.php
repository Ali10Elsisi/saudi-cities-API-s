<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use \Exception;

class UserController extends BaseController
{

    //  return all admins and superadmins
    public function index()
    {
        try {
            return $this->sendResponse("list of admins and superAdmins ", User::all());
        } catch (Exception $se) {
            return $this->sendError($se->getMessage());
        }
    }


    //register Admin 
    public function register(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:8',
            ]);

            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors());
            }

            $input = $request->all();
            $input['password'] = bcrypt($input['password']);
            $user = User::create($input);
            $success['token'] =  $user->createToken('MyApp')->plainTextToken;
            $success['name'] =  $user->name;
            $success['role_id'] = $request->input('role_id') ?? 0;

            return $this->sendResponse('User register successfully.', $success);
        } catch (Exception $se) {
            return $se->getMessage();
        }
    }


    // login Admin
    public function login(Request $request)
    {
        try {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                $user = Auth::user();
                $success['token'] =   $user->createToken('MyApp')->plainTextToken;
                $success['name'] =  $user->name;
                $success['role_id'] = $user->role_id;

                return $this->sendResponse('User login successfully.', $success);
            }
        } catch (Exception $se) {
            return $this->sendError('Unauthorized.', ['error' => 'Unauthorized'], $se->getCode());
        }
    }


    // logout admin from the current device 
    public function logout(Request $request)
    {
        try {
            $user = Auth::user();
            $request->user()->currentAccessToken()->delete();

            return $this->sendResponse($user->name . "  Logged out successfully!");
        } catch (Exception $se) {
            return $this->sendError($se->getMessage());
        }
    }


    // logout admin from all devices 
    public function logoutAllDevices(Request $request)
    {
        try {
            $user = Auth::user();
            $user->tokens()->delete();

            return $this->sendResponse($user->name . "  Logged out successfully from all devices !");
        } catch (Exception $se) {
            return $this->sendError($se->getMessage());
        }
    }


    // delete specific admin
    public function delete($id)
    {
        try {
            User::findOrFail($id)->delete();
            $this->sendResponse('the user has been deleted');
        } catch (Exception $se) {
            $this->sendError('the operation failed');
        }
    }
}
