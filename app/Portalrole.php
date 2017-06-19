<?php
namespace App;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
class Portalrole extends Model
{
    protected $connection = 'mysql';
    protected $primaryKey = 'id';
    protected $table = 'portal_role';
    public static function validator($input){
        $rules = array(
            'name'=>'required|string|max:150'
        );
        return Validator::make($input,$rules);
    }
    public $timestamps = false;
    protected $fillable = array(
        'name'
    );
}
