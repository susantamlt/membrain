<?php
namespace Tests\Unit\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Validator;
use App\Supplier;
use DB;
class Uservalidation {
    public static $rules = array(
        'name' => 'required|regex:/^[a-zA-Z ]*$/|max:255',
        'contact_email' => 'required|string|email|max:255',
        'contact_name' => 'required|regex:/^[a-zA-Z ]*$/|max:255',
        'contact_phone' => 'required|numeric|min:11',
        'error_allowance' => 'required|numeric|max:100',
        'return_csv' => 'required|numeric|max:1',
        'active' => 'required|numeric|max:1',
    );
}

class SupplierstestUpdate extends TestCase
{
    public function test_for_blank_email() {
        $data =  array('public_id'=>hash('md5', 'MB#'.rand(1111111111,9999999999)),'name'=>'Susanta','contact_email'=>'','contact_name'=>'Susanta Kumar Das','contact_phone'=>'99887755667','error_allowance'=>'10','return_csv'=>'1','active'=>'1');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        $this->assertTrue($validator->passes(),sprintf(
            "*********************************************************************\nInput: contact_email = '' \nOutput: The contact email field is required.\nResult: FAIL\n*********************************************************************"
        ));
    }  

    public function test_for_existing_email(){
        $data =  array('public_id'=>hash('md5', 'MB#'.rand(1111111111,9999999999)),'name'=>'Susanta','contact_email'=>'isusantakumardas2@gmail.com','contact_name'=>'Susanta Kumar Das','contact_phone'=>'99887755667','error_allowance'=>'10','return_csv'=>'1','active'=>'1');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        $this->assertTrue($validator->passes(),sprintf(
            "*********************************************************************\nInput: contact_email = 'isusantakumardas2@gmail.com' \nOutput: The contact email already exist.\nResult: FAIL\n*********************************************************************"
        ));
    }

    public function test_for_format_email(){
        $data =  array('public_id'=>hash('md5', 'MB#'.rand(1111111111,9999999999)),'name'=>'Susanta','contact_email'=>'isusantakumardas2','contact_name'=>'Susanta Kumar Das','contact_phone'=>'99887755667','error_allowance'=>'10','return_csv'=>'1','active'=>'1');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        $this->assertTrue($validator->passes(),sprintf(
            "*********************************************************************\nInput: contact_email = 'isusantakumardas2' \nOutput: The contact email must be a valid email address.\nResult: FAIL\n*********************************************************************"
        ));
    }

    public function test_for_pass_email(){
        $data =  array('public_id'=>hash('md5', 'MB#'.rand(1111111111,9999999999)),'name'=>'Susanta','contact_email'=>'isusantakumardas4@gmail.com','contact_name'=>'Susanta Kumar Das','contact_phone'=>'99887755667','error_allowance'=>'10','return_csv'=>'1','active'=>'1');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        echo $validator->messages();
        $this->assertFalse($validator->passes(),sprintf(
            "*********************************************************************\nInput: contact_email = 'isusantakumardas4@gmail.com' \nOutput: The contact email is valid email address.\nResult: PASS\n*********************************************************************"
        ));
    }

    public function test_for_blank_phone() {
        $data =  array('public_id'=>hash('md5', 'MB#'.rand(1111111111,9999999999)),'name'=>'Susanta','contact_email'=>'isusantakumardas4@gmail.com','contact_name'=>'Susanta Kumar Das','contact_phone'=>'','error_allowance'=>'10','return_csv'=>'1','active'=>'1');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        $this->assertTrue($validator->passes(),sprintf(
            "*********************************************************************\nInput: contact_phone = '' \nOutput: The contact phone field is required.\nResult: FAIL\n*********************************************************************"
        ));
    }  

    public function test_for_existing_phone(){
        $data =  array('public_id'=>hash('md5', 'MB#'.rand(1111111111,9999999999)),'name'=>'Susanta','contact_email'=>'isusantakumardas4@gmail.com','contact_name'=>'Susanta Kumar Das','contact_phone'=>'1234567891','error_allowance'=>'10','return_csv'=>'1','active'=>'1');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        $this->assertTrue($validator->passes(),sprintf(
            "*********************************************************************\nInput: contact_phone = '1234567891' \nOutput: The contact phone already exist.\nResult: FAIL\n*********************************************************************"
        ));
    }

    public function test_for_format_phone(){
        $data =  array('public_id'=>hash('md5', 'MB#'.rand(1111111111,9999999999)),'name'=>'Susanta','contact_email'=>'isusantakumardas4@gmail.com','contact_name'=>'Susanta Kumar Das','contact_phone'=>'12345','error_allowance'=>'10','return_csv'=>'1','active'=>'1');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        $this->assertTrue($validator->passes(),sprintf(
            "*********************************************************************\nInput: contact_phone = '12345' \nOutput: The contact phone must be a numeric and 11 digits.\nResult: FAIL\n*********************************************************************"
        ));
    }

    public function test_for_pass_phone(){
        $data =  array('public_id'=>hash('md5', 'MB#'.rand(1111111111,9999999999)),'name'=>'Susanta','contact_email'=>'isusantakumardas4@gmail.com','contact_name'=>'Susanta Kumar Das','contact_phone'=>'99887755667','error_allowance'=>'10','return_csv'=>'1','active'=>'1');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        $this->assertFalse($validator->passes(),sprintf(
            "*********************************************************************\nInput: contact_phone = '99887755667' \nOutput: The contact phone is valid Phone.\nResult: PASS\n*********************************************************************"
        ));
    }

    public function test_for_blank_name(){
       $data =  array('public_id'=>hash('md5', 'MB#'.rand(1111111111,9999999999)),'name'=>'','contact_email'=>'isusantakumardas4@gmail.com','contact_name'=>'susanta Kumar Das','contact_phone'=>'99887755667','error_allowance'=>'10','return_csv'=>'1','active'=>'1');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        $this->assertTrue($validator->passes(),sprintf(
            "*********************************************************************\nInput: name = '' \nOutput: The name field is required.\nResult: FAIL\n*********************************************************************"
        ));
    }

    public function test_for_format_name(){
        $data =  array('public_id'=>hash('md5', 'MB#'.rand(1111111111,9999999999)),'name'=>'Susanta@123','contact_email'=>'isusantakumardas4@gmail.com','contact_name'=>'susanta Kumar Das','contact_phone'=>'99887755667','error_allowance'=>'10','return_csv'=>'1','active'=>'1');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        $this->assertTrue($validator->passes(),sprintf(
            "*********************************************************************\nInput: name = 'Susanta@123' \nOutput: Name field Special character and Number not allowed.\nResult: FAIL\n*********************************************************************"
        ));
    }

    public function test_for_pass_name(){
        $data =  array('public_id'=>hash('md5', 'MB#'.rand(1111111111,9999999999)),'name'=>'Susanta','contact_email'=>'isusantakumardas4@gmail.com','contact_name'=>'susanta Kumar Das','contact_phone'=>'99887755667','error_allowance'=>'10','return_csv'=>'1','active'=>'1');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        $this->assertFalse($validator->passes(),sprintf(
            "*********************************************************************\nInput: name = 'Susanta' \nOutput: name is valid.\nResult: PASS\n*********************************************************************"
        ));
    }

    public function test_for_blank_contact_name(){
        $data =  array('public_id'=>hash('md5', 'MB#'.rand(1111111111,9999999999)),'name'=>'Susanta','contact_email'=>'isusantakumardas4@gmail.com','contact_name'=>'','contact_phone'=>'99887755667','error_allowance'=>'10','return_csv'=>'1','active'=>'1');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);       
        $this->assertTrue($validator->passes(),sprintf(
            "*********************************************************************\nInput: contact_name = '' \nOutput: The contact name field is required.\nResult: FAIL\n*********************************************************************"
        ));
    }

    public function test_for_format_contact_name(){
        $data =  array('public_id'=>hash('md5', 'MB#'.rand(1111111111,9999999999)),'name'=>'Susanta','contact_email'=>'isusantakumardas4@gmail.com','contact_name'=>'susanta/Kumar/Das','contact_phone'=>'99887755667','error_allowance'=>'10','return_csv'=>'1','active'=>'1');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);        
        $this->assertTrue($validator->passes(),sprintf(
            "*********************************************************************\nInput: contact_name = 'susanta/Kumar/Das' \nOutput: Contact name field Special character and Number not allowed.\nResult: FAIL\n*********************************************************************"
        ));
    }

    public function test_for_pass_contact_name(){
        $data =  array('public_id'=>hash('md5', 'MB#'.rand(1111111111,9999999999)),'name'=>'Susanta','contact_email'=>'isusantakumardas4@gmail.com','contact_name'=>'susanta Kumar Das','contact_phone'=>'99887755667','error_allowance'=>'10','return_csv'=>'1','active'=>'1');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);        
        $this->assertFalse($validator->passes(),sprintf(
            "*********************************************************************\nInput: contact_name = 'susanta Kumar Das' \nOutput: name is valid.\nResult: PASS\n*********************************************************************"
        ));
    }

    public function test_for_blank_error_allowance(){
        $data =  array('public_id'=>hash('md5', 'MB#'.rand(1111111111,9999999999)),'name'=>'Susanta','contact_email'=>'isusantakumardas4@gmail.com','contact_name'=>'susanta Kumar Das','contact_phone'=>'99887755667','error_allowance'=>'','return_csv'=>'1','active'=>'1');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);        
        $this->assertTrue($validator->passes(),sprintf(
            "*********************************************************************\nInput: error_allowance = '' \nOutput: The error allowance field is required.\nResult: FAIL\n*********************************************************************"
        ));
    }

    public function test_for_format_error_allowance(){
        $data =  array('public_id'=>hash('md5', 'MB#'.rand(1111111111,9999999999)),'name'=>'Susanta','contact_email'=>'isusantakumardas4@gmail.com','contact_name'=>'susanta Kumar Das','contact_phone'=>'99887755667','error_allowance'=>'10s','return_csv'=>'1','active'=>'1');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);        
        $this->assertTrue($validator->passes(),sprintf(
            "*********************************************************************\nInput: error_allowance = '10s' \nOutput: Error allowance field Special character not allowed.\nResult: FAIL\n*********************************************************************"
        ));
    }

    public function test_for_pass_error_allowance(){
        $data =  array('public_id'=>hash('md5', 'MB#'.rand(1111111111,9999999999)),'name'=>'Susanta','contact_email'=>'isusantakumardas4@gmail.com','contact_name'=>'susanta Kumar Das','contact_phone'=>'99887755667','error_allowance'=>'10','return_csv'=>'1','active'=>'1');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);        
        $this->assertFalse($validator->passes(),sprintf(
            "*********************************************************************\nInput: error_allowance = '10' \nOutput: Error allowance is valid.\nResult: PASS\n*********************************************************************"
        ));
    }

    public function test_for_blank_return_csv(){
        $data =  array('public_id'=>hash('md5', 'MB#'.rand(1111111111,9999999999)),'name'=>'Susanta','contact_email'=>'isusantakumardas4@gmail.com','contact_name'=>'susanta Kumar Das','contact_phone'=>'99887755667','error_allowance'=>'10','return_csv'=>'','active'=>'1');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);        
        $this->assertTrue($validator->passes(),sprintf(
            "*********************************************************************\nInput: return_csv = '' \nOutput: The return csv field is required.\nResult: FAIL\n*********************************************************************"
        ));
    }

    public function test_for_format_return_csv(){
        $data =  array('public_id'=>hash('md5', 'MB#'.rand(1111111111,9999999999)),'name'=>'Susanta','contact_email'=>'isusantakumardas4@gmail.com','contact_name'=>'susanta Kumar Das','contact_phone'=>'99887755667','error_allowance'=>'10','return_csv'=>'10fg','active'=>'1');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);        
        $this->assertTrue($validator->passes(),sprintf(
            "*********************************************************************\nInput: return_csv = '10fg' \nOutput: Return csv field Special character not allowed.\nResult: FAIL\n*********************************************************************"
        ));
    }

    public function test_for_pass_return_csv(){
        $data =  array('public_id'=>hash('md5', 'MB#'.rand(1111111111,9999999999)),'name'=>'Susanta','contact_email'=>'isusantakumardas4@gmail.com','contact_name'=>'susanta Kumar Das','contact_phone'=>'99887755667','error_allowance'=>'10','return_csv'=>'1','active'=>'1');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);        
        $this->assertFalse($validator->passes(),sprintf(
            "*********************************************************************\nInput: return_csv = '1' \nOutput: The Return csv is valid.\nResult: PASS\n*********************************************************************"
        ));
    }

    public function test_for_blank_active(){
        $data =  array('public_id'=>hash('md5', 'MB#'.rand(1111111111,9999999999)),'name'=>'Susanta','contact_email'=>'isusantakumardas4@gmail.com','contact_name'=>'susanta Kumar Das','contact_phone'=>'99887755667','error_allowance'=>'10','return_csv'=>'1','active'=>'');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);        
        $this->assertTrue($validator->passes(),sprintf(
            "*********************************************************************\nInput: active = '' \nOutput: The active field is required.\nResult: FAIL\n*********************************************************************"
        ));
    }

    public function test_for_format_active(){
        $data =  array('public_id'=>hash('md5', 'MB#'.rand(1111111111,9999999999)),'name'=>'Susanta','contact_email'=>'isusantakumardas4@gmail.com','contact_name'=>'susanta Kumar Das','contact_phone'=>'99887755667','error_allowance'=>'10','return_csv'=>'1','active'=>'10ge');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);        
        $this->assertTrue($validator->passes(),sprintf(
            "*********************************************************************\nInput: active = '10ge' \nOutput: Active field Special character not allowed.\nResult: FAIL\n*********************************************************************"
        ));
    }

    public function test_for_pass_active(){
        $data =  array('public_id'=>hash('md5', 'MB#'.rand(1111111111,9999999999)),'name'=>'Susanta','contact_email'=>'isusantakumardas4@gmail.com','contact_name'=>'susanta Kumar Das','contact_phone'=>'99887755667','error_allowance'=>'10','return_csv'=>'1','active'=>'1');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);        
        $this->assertFalse($validator->passes(),sprintf(
            "*********************************************************************\nInput: active = '1' \nOutput: Active is valid.\nResult: PASS\n*********************************************************************"
        ));
    }

    public function test_create_supplier(){
        $data =  array('name'=>'Susanta','contact_email'=>'isusantakumardas2@gmail.com','contact_name'=>'Susanta Kr Das','contact_phone'=>'99887755664','error_allowance'=>'10','return_csv'=>'1','active'=>'1');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);  
        $check = $validator->passes();
        $this->assertTrue($check,sprintf(
            "*********************************************************************\nInput: data = 'NOt Empty' \nOutput[%s].\nResult: FAIL\n*********************************************************************",$validator->messages()
        ));
        if($check == 1){
            Supplier::where('id','7')->update($data);
            echo 'Successfully Update';
        }
    }
}