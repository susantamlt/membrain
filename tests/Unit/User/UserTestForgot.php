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
        'username' => 'required|string|email|max:255|unique:protal_user',
    );
}

class UserTestForgot extends TestCase
{
    public function test_for_blank_email() {
        $data =  array('username'=>'');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        $this->assertTrue($validator->passes(),sprintf(
            "*********************************************************************\nInput: username = '%s' \nOutput: The username field is required.\nResult: FAIL\n*********************************************************************",$data['username']
        ));
	}  

    public function test_for_notfind_email(){
        $data =  array('username'=>'isusantakumardas456789@gmail.com');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        $this->assertFalse($validator->passes(),sprintf(
            "*********************************************************************\nInput: username = '%s' \nOutput: The username not Found.\nResult: FAIL\n*********************************************************************",$data['username']
        ));
    }

    public function test_for_format_email(){
        $data =  array('username'=>'isusantakumardas');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        $this->assertTrue($validator->passes(),sprintf(
            "*********************************************************************\nInput: username = '%s' \nOutput: The username must be a valid email address.\nResult: FAIL\n*********************************************************************",$data['username']
        ));
    }

    public function test_for_pass_email(){
        $data =  array('username'=>'isusantakumardas@gmail.com');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        $this->assertTrue($validator->passes(),sprintf(
            "*********************************************************************\nInput: username = '%s' \nOutput:  The username is in databade.\nResult: PASS\n*********************************************************************",$data['username']
        ));
    }
}