<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\City;
use Exception;
use Illuminate\Support\Facades\Validator;

class CityController extends BaseController
{
    //

    public function index()
    {
        try {

            $city = City::all();

            if ($city->isEmpty()) {
                return Parent::sendResponse('there is no cities');
            } else {
                return Parent::sendResponse('list of cities', $city);
            }
        } catch (Exception $se) {
            $statusCode = $se->getCode() > 0 ? $se->getCode() : 500;
            return Parent::sendError($se->getMessage(), "", $statusCode);
        }
    }


        // create object of city 
        public function create(Request $request)
        {
            try {
                // Validate the request data
                $validator = Validator::make($request->all(), [
                    "name" => 'required | regex:/^[\p{Arabic}\s]+$/u',
                ]);
    
                if ($validator->fails()) {
                    return Parent::sendError($validator->errors(), "", 400);
                }
    
                $input = $request->all();

     
                // Create the city
                $city = City::create($input);
    
                return Parent::sendResponse("The city has been saved", $city);
            } catch (Exception $se) {
                $statusCode = $se->getCode() > 0 ? $se->getCode() : 500;
                return Parent::sendError($se->getMessage(), "", $statusCode);
            }
        }
    
    
        // update specific city
        public function update(Request $request, $id)
        {
            try {
                // Find the city by its ID
                $city = City::findOrFail($id);
    
                if (!$city) {
                    return Parent::sendError('this city is not found', '', 400);
                }
    
                // Validate the request data
                $validator = Validator::make($request->all(), [
                    "name" => 'required | regex:/^[\p{Arabic}\s]+$/u',
                ]);
    
                if ($validator->fails()) {
                    return Parent::sendError($validator->errors(), "", 400);
                }
    
                // Prepare input data
                $input = $request->all();
    

  
    
                // Update the city
                $city->update($input);
    
                return Parent::sendResponse("The city has been updated", $city);
            } catch (Exception $se) {
                $statusCode = $se->getCode() > 0 ? $se->getCode() : 500;
                return Parent::sendError($se->getMessage(), "", $statusCode);
            }
        }
    
        //delete function 
        public function delete($id)
        {
            try {
                // Find the city by its ID
                $city = City::findOrFail($id);
    

    
                // Delete the city from the database
                $city->delete();
    
                return Parent::sendResponse('city deleted successfully', null);
            } catch (\Exception $e) {
                $statusCode = $e->getCode() > 0 ? $e->getCode() : 500;
                return Parent::sendError($e->getMessage(), "", $statusCode);
            }
        }

}
