<?php
namespace App;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
class Portal extends Model
{
    protected $connection = 'mysql';
    protected $primaryKey = 'id';
    protected $table = 'protal_user';
    public static function validator($input){
        $rules = array(
            'username'=>'required|string|email|max:255|unique:clients',
            'name'=>'required|string|max:255',
            'password'=>'required|string|max:255|min:6',
            'role_id'=>'required|integer',
            'client_id'=>'required|integer',
            'supplier_id'=>'required|integer',
            'reset_in_progress'=>'required|boolean',
            'active'=>'required|boolean',
        );
        return Validator::make($input,$rules);
    }
    public $timestamps = true;
    protected $fillable = array(
        'username',
        'name',
        'active'
    );
}