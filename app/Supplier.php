<?php
namespace App;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
class Supplier extends Model
{
    protected $connection = 'mysql';
    protected $primaryKey = 'id';
    protected $table = 'suppliers';
    public static function validator($input){
        $rules = array(
            'public_id'=>'required|string|max:255',
            'name'=>'required|string|max:255',
            'contact_email'=>'required|string|email|max:255|unique:suppliers',
            'contact_name'=>'required|string|max:255',
            'contact_phone'=>'required|string|min:10',
            'error_allowance'=>'required|integer',
            'return_csv'=>'required|boolean',
            'active'=>'required|boolean',
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
