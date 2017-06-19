<?php
namespace App;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
class Portalsubclient extends Model
{
    protected $connection = 'mysql';
    protected $primaryKey = 'id';
    protected $table = 'portal_sub_client';
    public static function validator($input){
        $rules = array(
            'portal_user_id'=>'required|integer',
            'client_id'=>'required|integer'
        );
        return Validator::make($input,$rules);
    }
    public $timestamps = false;
    protected $fillable = array(
        'portal_user_id',
        'client_id'
    );
}