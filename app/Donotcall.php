<?php
namespace App;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
class Donotcall extends Model
{
    protected $connection = 'mysql';
    protected $primaryKey = 'id';
    protected $table = 'membrain_global_do_not_call';
    public static function validator($input){
        $rules = array(
            'country_code'=>'required|string|max:50',
            'phone_number'=>'required|numeric|max:50|unique:membrain_global_do_not_call',
            'reason_code'=>'required|string|max:255',
        );
        return Validator::make($input,$rules);
    }
    public $timestamps = false;
    protected $fillable = array(
        'country_code',
        'phone_number',
        'reason_code'
    );
}