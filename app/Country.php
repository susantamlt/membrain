<?php
namespace App;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
class Country extends Model
{
    protected $connection = 'mysql';
    protected $primaryKey = 'id';
    protected $table = 'countries';
    public static function validator($input){
        $rules = array(
            'name'=>'required|string|max:255|unique:countries',
            'code'=>'required|string|max:50|unique:countries',
        );
        return Validator::make($input,$rules);
    }
    public $timestamps = false;
    protected $fillable = array(
        'name',
        'code'
    );
}