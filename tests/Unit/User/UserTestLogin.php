<?php
namespace Tests\Unit\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Portal;
use DB;


class Uservalidation {
    public static $rules = array(
        'username' => 'required|string|email|max:255',
        'password' => 'required|string|min:6',
    );
}

class UserTestLogin extends TestCase
{
    public function test_for_blank_usename() {
        $data =  array('username'=>'','password'=>bcrypt('123456'));
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        $this->assertTrue($validator->passes(),sprintf(
            "*********************************************************************\nInput: username = '%s' \nOutput: username field is required.\nResult: FAIL\n*********************************************************************",$data['username']
        ));
	} 

    public function test_for_format_usename(){
        $data =  array('username'=>'isusantakumardas2','password'=>bcrypt('123456'));
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        $this->assertTrue($validator->passes(),sprintf(
            "*********************************************************************\nInput: username = '%s' \nOutput: The username must be a valid email address.\nResult: FAIL\n*********************************************************************",$data['username']
        ));
    }     

    public function test_for_blank_password(){
        $data =  array('username'=>'isusantakumardas2@gmail.com','password'=>'');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);       
        $this->assertTrue($validator->passes(),sprintf(
            "*********************************************************************\nInput: password = '%s' \nOutput: The password field is required.\nResult: FAIL\n*********************************************************************",$data['password']
        ));
    }

    public function test_for_minsixlength_password(){
        $data =  array('username'=>'isusantakumardas2@gmail.com','password'=>'12345');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);        
        $this->assertTrue($validator->passes(),sprintf(
            "*********************************************************************\nInput: password = '%s' \nOutput: The password must be at least 6 characters.\nResult: FAIL\n*********************************************************************",$data['password']
        ));
    }    

    public function test_login_user_invalid(){
        $data =  array('username'=>'isusantakumardas7894561230@gmail.com','password'=>'$2y$10$RJPDpWAnVi3w4ZmBSQUFZO/76CaYLCuStvL5OuV64Vb03uRH35b1q');
        $count = Portal::where('username','=',$data['username'])
        ->count();
        $this->assertTrue($count < 0, sprintf(
            "*********************************************************************\nInput: username = '%s' \nOutput: Not found any records in database table portal user that matched attributes.\nResult: FAIL\n*********************************************************************",$data['username']
        ));
    }

    public function test_login_user_valid(){
        $data =  array('username'=>'isusantakumardas@gmail.com');
        $count = Portal::where('username','=',$data['username'])
        ->count();

        $this->assertFalse($count > 0, sprintf(
            "*********************************************************************\nInput: username = '%s' \nOutput: Found records in database table protal_user that matched attributes.\nResult: PASS\n*********************************************************************",$data['username']
        ));
    }

    public function test_login_user(){
        $data =  array('username'=>'isusantakumardas@gmail.com','password'=>'$2y$10$RJPDpWAnVi3w4ZmBSQUFZO/76CaYLCuStvL5OuV64Vb03uRH35b1q');
        $count = DB::Table('protal_user')
            ->where('username','=',$data['username'])
            ->where('password','=',$data['password'])
            ->get(['id','name']);        
        $this->assertEquals(1,count($count));
        echo 'Successfully login '.$count[0]->name.'.';
    }
}