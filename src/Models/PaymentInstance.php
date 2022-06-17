<?php

namespace Immera\Payment\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentInstance extends Model
{
    protected $casts = [
        'additional_info' => 'array'
    ]; 
    
    public static function getFromID($id)
    {
        return static::where('intent_id', $id)->first();
    }

    public function setIntentIdFromObj($obj, $keys = ['intent_id'])
    {
        foreach($keys as $key) {
            if(is_object($obj) && property_exists($obj, $key)) {
                $this->intent_id = $obj->$key;
                break;
            }
            if(is_array($obj) && array_key_exists($key, $obj)) {
                $this->intent_id = $obj[$key];
                break;
            }
        }
    }
    public function setClientSecretFromObj($obj, $keys = ['client_secret'])
    {
        foreach($keys as $key) {
            if(is_object($obj) && property_exists($obj, $key)) {
                $this->client_secret = $obj->$key;
                break;
            }
            if(is_array($obj) && array_key_exists($key, $obj)) {
                $this->client_secret = $obj[$key];
                break;
            }
        }
    }
    public function setStatusFromObj($obj, $keys = ['status'])
    {
        foreach($keys as $key) {
            if(is_object($obj) && property_exists($obj, $key)) {
                $this->status = $obj->$key;
                break;
            }
            if(is_array($obj) && array_key_exists($key, $obj)) {
                $this->status = $obj[$key];
                break;
            }
        }
    }

}
