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
        'name' => 'required|regex:/^[a-zA-Z ]*$/|max:255',
        'username' => 'required|string|email|max:255|unique:protal_user',
        'password' => 'required|string|min:6',
        'role_id' => 'required|numeric|max:1',
        'active' => 'required|numeric|max:1',
    );
}

class UserTest extends TestCase
{
    public function test_for_blank_usename() {
        $data =  array('username'=>'','name'=>'Susanta Kumar Das','password'=>bcrypt('123456'),'role_id'=>'1','active'=>'1');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        $this->assertTrue($validator->passes(),sprintf(
            "*********************************************************************\nInput: username = '%s' \nOutput: username field is required.\nResult: FAIL\n*********************************************************************",$data['username']
        ));
	}  

    public function test_for_existing_usename(){
        $data =  array('username'=>'isusantakumardas@gmail.com','name'=>'Susanta Kumar Das','password'=>bcrypt('123456'),'role_id'=>'1','active'=>'1');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        $this->assertTrue($validator->passes(),sprintf(
            "*********************************************************************\nInput: username = '%s' \nOutput: The username already exist.\nResult: FAIL\n*********************************************************************",$data['username']
        ));
    }

    public function test_for_format_usename(){
        $data =  array('username'=>'isusantakumardas2','name'=>'Susanta Kumar Das','password'=>bcrypt('123456'),'role_id'=>'1','active'=>'1');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        $this->assertTrue($validator->passes(),sprintf(
            "*********************************************************************\nInput: username = '%s' \nOutput: The username must be a valid email address.\nResult: FAIL\n*********************************************************************",$data['username']
        ));
    }

    public function test_for_pass_usename(){
        $data =  array('username'=>'isusantakumardas3@gmail.com','name'=>'Susanta Kumar Das','password'=>bcrypt('123456'),'role_id'=>'1','active'=>'1');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        $this->assertFalse($validator->passes(),sprintf(
            "*********************************************************************\nInput: username = '%s' \nOutput: The username is valid.\nResult: PASS\n*********************************************************************",$data['username']
        ));
    }

    public function test_for_blank_password(){
        $data =  array('username'=>'isusantakumardas3@gmail.com','name'=>'Susanta Kumar Das','password'=>'','role_id'=>'1','active'=>'1');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        $this->assertTrue($validator->passes(),sprintf(
            "*********************************************************************\nInput: password = '%s' \nOutput: The Password field is required.\nResult: FAIL\n*********************************************************************",$data['password']
        ));
    }

    public function test_for_minsixlength_password(){
        $data =  array('username'=>'isusantakumardas3@gmail.com','name'=>'Susanta Kumar Das','password'=>'12345','role_id'=>'1','active'=>'1');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        $this->assertTrue($validator->passes(),sprintf(
            "*********************************************************************\nInput: password = '%s' \nOutput: The Password field is required min 6 digits.\nResult: FAIL\n*********************************************************************",$data['password']
        ));
    }

    public function test_for_pass_password(){
        $data =  array('username'=>'isusantakumardas3@gmail.com','name'=>'Susanta Kumar Das','password'=>'123456','role_id'=>'1','active'=>'1');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        $this->assertFalse($validator->passes(),sprintf(
            "*********************************************************************\nInput: password = '%s' \nOutput: The Password is valid.\nResult: PASS\n*********************************************************************",$data['password']
        ));
    }

    public function test_for_blank_name(){
        $data =  array('username'=>'isusantakumardas3@gmail.com','name'=>'','password'=>bcrypt('123456'),'role_id'=>'1','active'=>'1');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        $this->assertTrue($validator->passes(),sprintf(
            "*********************************************************************\nInput: name = '%s' \nOutput: The name field is required.\nResult: FAIL\n*********************************************************************",$data['name']
        ));
    }
    public function test_for_format_name(){
        $data =  array('username'=>'isusantakumardas3@gmail.com','name'=>'Susanta/Kumar Das','password'=>bcrypt('123456'),'role_id'=>'1','active'=>'1');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        $this->assertTrue($validator->passes(),sprintf(
            "*********************************************************************\nInput: name = '%s' \nOutput: Name field Special character and Number not allowed.\nResult: FAIL\n*********************************************************************",$data['name']
        ));
    }

    public function test_for_Pass_name(){
        $data =  array('username'=>'isusantakumardas3@gmail.com','name'=>'Susanta/Kumar Das','password'=>bcrypt('123456'),'role_id'=>'1','active'=>'1');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        $this->assertFalse($validator->passes(),sprintf(
            "*********************************************************************\nInput: name = '%s' \nOutput: Name field valid.\nResult: PASS\n*********************************************************************",$data['name']
        ));
    }

    public function test_for_blank_roleid(){
        $data =  array('username'=>'isusantakumardas3@gmail.com','name'=>'Susanta Kumar Das','password'=>bcrypt('123456'),'role_id'=>'1','active'=>'1');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        $this->assertTrue($validator->passes(),sprintf(
            "*********************************************************************\nInput: role_id = '%s' \nOutput: The role_id field is required.\nResult: FAIL\n*********************************************************************",$data['role_id']
        ));
    }

    public function test_for_blank_active(){
        $data =  array('username'=>'isusantakumardas3@gmail.com','name'=>'Susanta Kumar Das','password'=>bcrypt('123456'),'role_id'=>'1','active'=>'');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        $this->assertTrue($validator->passes(),sprintf(
            "*********************************************************************\nInput: active = '%s' \nOutput: The active field is required.\nResult: FAIL\n*********************************************************************",$data['active']
        ));
    }

    public function test_create_user(){
        $data =  array('username'=>'isusantakumardas3@gmail.com','name'=>'Susanta Kumar Das','password'=>bcrypt('123456'),'role_id'=>'1','active'=>'1');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        $check = $validator->passes();
        $this->assertTrue($validator->passes(),sprintf(
            "*********************************************************************\nInput: data = True \nOutput: %s\nResult: FAIL\n*********************************************************************",$validator->messages()
        ));
        if($check == 1){
            factory(\App\Portal::class)->create($data);
            $this->assertDatabaseHas('protal_user', $data);
            echo 'Successfully Created';
        }
    }
}