<?php

namespace Immera\Payment\Models;

use Illuminate\Database\Eloquent\Model;
use Exception;
use Log;

class PaymentInstance extends Model
{
    protected $casts = [
        'additional_info' => 'array'
    ]; 
    
    public static function getFromID($id)
    {
        return static::where('intent_id', $id)->first();
    }

    private function setModelFieldFromObj($mfield, $obj, $keys = [])
    {
        Log::info("Setting up $mfield in the payment response object");
        foreach($keys as $key) {
            Log::info("looking for key $key");
            if(is_object($obj) && property_exists($obj, $key)) {
                Log::info("Object has key $key");
                $this->$mfield = $obj->$key;
                break;
            }
            if(is_array($obj) && array_key_exists($key, $obj)) {
                Log::info("Object is type of array and it cantains key $key");
                $this->$mfield = $obj[$key];
                break;
            }
            try {
                $iid = $obj->$key;
                Log::info("$mfield (trying to collect using unconventional way): " . $iid);
                $this->$mfield = $iid;
                break;
            } catch (Exception $e) {
                Log::info("ERROR RAISED: " . $e->getMessage());
            }
            Log::info("$key not found any way in the object.");
        }
    }

    public function setIntentIdFromObj($obj, $keys = ['intent_id'])
    {
        $this->setModelFieldFromObj('intent_id', $obj, $keys);
    }

    public function setClientSecretFromObj($obj, $keys = ['client_secret'])
    {
        $this->setModelFieldFromObj('client_secret', $obj, $keys);
    }
    public function setStatusFromObj($obj, $keys = ['status'])
    {
        $this->setModelFieldFromObj('status', $obj, $keys);
    }

}
