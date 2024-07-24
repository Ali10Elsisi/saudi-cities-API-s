<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Zone;
use Exception;
use Illuminate\Support\Facades\Validator;

class ZoneController extends BaseController
{
    //

    public function index()
    {
        try {

            $zone = Zone::all();

            if ($zone->isEmpty()) {
                return Parent::sendResponse('there is no zones');
            } else {
                return Parent::sendResponse('list of zones', $zone);
            }
        } catch (Exception $se) {
            $statusCode = $se->getCode() > 0 ? $se->getCode() : 500;
            return Parent::sendError($se->getMessage(), "", $statusCode);
        }
    }


        // create object of zone 
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

     
                // Create the zone
                $zone = Zone::create($input);
    
                return Parent::sendResponse("The zone has been saved", $zone);
            } catch (Exception $se) {
                $statusCode = $se->getCode() > 0 ? $se->getCode() : 500;
                return Parent::sendError($se->getMessage(), "", $statusCode);
            }
        }
    
    
        // update specific zone
        public function update(Request $request, $id)
        {
            try {
                // Find the zone by its ID
                $zone = Zone::findOrFail($id);
    
                if (!$zone) {
                    return Parent::sendError('this zone is not found', '', 400);
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
    

  
    
                // Update the zone
                $zone->update($input);
    
                return Parent::sendResponse("The zone has been updated", $zone);
            } catch (Exception $se) {
                $statusCode = $se->getCode() > 0 ? $se->getCode() : 500;
                return Parent::sendError($se->getMessage(), "", $statusCode);
            }
        }
    
        //delete function 
        public function delete($id)
        {
            try {
                // Find the zone by its ID
                $zone = Zone::findOrFail($id);
    

    
                // Delete the zone from the database
                $zone->delete();
    
                return Parent::sendResponse('zone deleted successfully', null);
            } catch (\Exception $e) {
                $statusCode = $e->getCode() > 0 ? $e->getCode() : 500;
                return Parent::sendError($e->getMessage(), "", $statusCode);
            }
        }

}
