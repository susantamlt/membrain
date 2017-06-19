<?php
namespace App;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
class Client extends Model
{
    protected $connection = 'mysql';
    protected $primaryKey = 'id';
    protected $table = 'clients';
    public static function validator($input){
        $rules = array(
            'name'=>'required|string|max:255',
            'contact_email'=>'required|string|email|max:255|unique:clients',
            'contact_name'=>'required|string|max:255',
            'contact_phone'=>'required|string|min:10',
            'active'=>'required|boolean',
            'country_code'=>'required|string|max:50',
            'email_suppression'=>'required|boolean',
            'phone_suppression'=>'required|boolean',
            'lead_expiry_days'=>'required|integer',
        );
        return Validator::make($input,$rules);
    }
    public $timestamps = false;
    protected $fillable = array(
        'name',
        'contact_email',
        'contact_name',
        'contact_phone',
        'active'
    );
}