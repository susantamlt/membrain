<?php
namespace App;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
class Salaciousword extends Model
{
    protected $connection = 'mysql';
    protected $primaryKey = 'id';
    protected $table = 'salacious_word';
    public static function validator($input){
        $rules = array(
            'pattern'=>'required|string|max:255|unique:salacious_word',
            'email_address'=>'required',
            'first_name'=>'required',
            'last_name'=>'required',
            'address'=>'required',
        );
        return Validator::make($input,$rules);
    }
    public $timestamps = false;
    protected $fillable = array(
        'pattern',
        'email_address',
        'first_name',
        'last_name',
        'address'
    );
}