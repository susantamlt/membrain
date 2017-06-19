<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class fraudDetection extends Model
{
    protected $connection = 'mysql';
    protected $primaryKey = 'id';
    protected $table = 'fraud_detection';
    public static function validator($input){
        $rules = array(
            'email_address'=>'required|string|email|max:255|unique:clients',
            'phone'=>'required|string|min:10',
            'first_name'=>'required|string|max:255',
            'last_name'=>'required|string|max:255',
            'postcode'=>'required|string|max:255',
        );
        return Validator::make($input,$rules);
    }
    public $timestamps = false;
    protected $fillable = array(
        'id',
        'email_address',
        'phone',
        'first_name',
        'last_name',
        'postcode',
        'received',
    );
}