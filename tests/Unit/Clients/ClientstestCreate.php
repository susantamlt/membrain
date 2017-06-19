<?php
namespace Tests\Unit\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Validator;
use App\Client;
use DB;
class Uservalidation {
    public static $rules = array(
        'name' => 'required|regex:/^[a-zA-Z ]*$/|max:255',
        'contact_email' => 'required|string|email|max:255|unique:clients',
        'contact_name' => 'required|regex:/^[a-zA-Z ]*$/|max:255',
        'contact_phone' => 'required|numeric|min:9|unique:clients',
        'country_code' => 'required|regex:/^[a-zA-Z]*$/|max:2',
        'lead_expiry_days' => 'required|numeric|digits_between:1,3',
        'active' => 'required|numeric|max:1',
    );
}

class ClientstestCreate extends TestCase
{
    public function test_for_blank_email() {
        $data =  array('name'=>'Susanta','contact_email'=>'','contact_name'=>'Susanta Kumar Das','contact_phone'=>'99887755667','country_code'=>'AU','lead_expiry_days'=>'90','active'=>'1','email_suppression'=>'0','phone_suppression'=>'0');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        $this->assertTrue($validator->passes(),sprintf(
            "*********************************************************************\nInput: contact_email = '%s' \nOutput: The contact email field is required.\nResult: FAIL\n*********************************************************************",$data['contact_email']
        ));
	}  

    public function test_for_existing_email(){
        $data =  array('name'=>'Susanta','contact_email'=>'isusantakumardas123@gmail.com','contact_name'=>'Susanta Kumar Das','contact_phone'=>'99887755667','country_code'=>'AU','lead_expiry_days'=>'90','active'=>'1','email_suppression'=>'0','phone_suppression'=>'0');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        $this->assertTrue($validator->passes(),sprintf(
            "*********************************************************************\nInput: contact_email = '%s' \nOutput: The contact email already exist.\nResult: FAIL\n*********************************************************************",$data['contact_email']
        ));
    }

    public function test_for_format_email(){
        $data =  array('name'=>'Susanta','contact_email'=>'isusantakumardas123','contact_name'=>'Susanta Kumar Das','contact_phone'=>'99887755667','country_code'=>'AU','lead_expiry_days'=>'90','active'=>'1','email_suppression'=>'0','phone_suppression'=>'0');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        $this->assertTrue($validator->passes(),sprintf(
            "*********************************************************************\nInput: contact_email = '%s' \nOutput: The contact email must be a valid email address.\nResult: FAIL\n*********************************************************************",$data['contact_email']
        ));
    }

    public function test_for_pass_email(){
        $data =  array('name'=>'Susanta','contact_email'=>'isusantakumardas456@gmail.com','contact_name'=>'Susanta Kumar Das','contact_phone'=>'99887755667','country_code'=>'AU','lead_expiry_days'=>'90','active'=>'1','email_suppression'=>'0','phone_suppression'=>'0');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        $this->assertFalse($validator->passes(),sprintf(
            "*********************************************************************\nInput: contact_email = '%s' \nOutput: The contact email is valid email address.\nResult: PASS\n*********************************************************************",$data['contact_email']
        ));
    }

    public function test_for_blank_phone() {
        $data =  array('name'=>'Susanta','contact_email'=>'isusantakumardas456@gmail.com','contact_name'=>'Susanta Kumar Das','contact_phone'=>'','country_code'=>'AU','lead_expiry_days'=>'90','active'=>'1','email_suppression'=>'0','phone_suppression'=>'0');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        $this->assertTrue($validator->passes(),sprintf(
            "*********************************************************************\nInput: contact_phone = '%s' \nOutput: The contact phone field is required.\nResult: FAIL\n*********************************************************************",$data['contact_phone']

        ));
    }  

    public function test_for_existing_phone(){
        $data =  array('name'=>'Susanta','contact_email'=>'isusantakumardas456@gmail.com','contact_name'=>'Susanta Kumar Das','contact_phone'=>'1234567891','country_code'=>'AU','lead_expiry_days'=>'90','active'=>'1','email_suppression'=>'0','phone_suppression'=>'0');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        $this->assertTrue($validator->passes(),sprintf(
            "*********************************************************************\nInput: contact_phone = '%s' \nOutput: The contact phone already exist.\nResult: FAIL\n*********************************************************************",$data['contact_phone']
        ));
    }

    public function test_for_format_phone(){
        $data =  array('name'=>'Susanta','contact_email'=>'isusantakumardas456@gmail.com','contact_name'=>'Susanta Kumar Das','contact_phone'=>'12345','country_code'=>'AU','lead_expiry_days'=>'90','active'=>'1','email_suppression'=>'0','phone_suppression'=>'0');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        $this->assertTrue($validator->passes(),sprintf(
            "*********************************************************************\nInput: contact_phone = '%s' \nOutput: The contact phone must be a numeric and min 9 digits.\nResult: FAIL\n*********************************************************************",$data['contact_phone']
        ));
    }

    public function test_for_pass_phone(){
        $data =  array('name'=>'Susanta','contact_email'=>'isusantakumardas456@gmail.com','contact_name'=>'Susanta Kumar Das','contact_phone'=>'99887755667','country_code'=>'AU','lead_expiry_days'=>'90','active'=>'1','email_suppression'=>'0','phone_suppression'=>'0');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        $this->assertFalse($validator->passes(),sprintf(
            "*********************************************************************\nInput: contact_phone = '%s' \nOutput: The contact phone is valid phone.\nResult: PASS\n*********************************************************************",$data['contact_phone']
        ));
    }

    public function test_for_blank_name(){
        $data =  array('name'=>'','contact_email'=>'isusantakumardas456@gmail.com','contact_name'=>'Susanta Kumar Das','contact_phone'=>'99887755667','country_code'=>'AU','lead_expiry_days'=>'90','active'=>'1','email_suppression'=>'0','phone_suppression'=>'0');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        $this->assertTrue($validator->passes(),sprintf(
            "*********************************************************************\nInput: name = '%s' \nOutput: The name field is required.\nResult: FAIL\n*********************************************************************",$data['name']
        ));
    }

    public function test_for_format_name(){
        $data =  array('name'=>'susanta@fg','contact_email'=>'isusantakumardas456@gmail.com','contact_name'=>'Susanta Kumar Das','contact_phone'=>'99887755667','country_code'=>'AU','lead_expiry_days'=>'90','active'=>'1','email_suppression'=>'0','phone_suppression'=>'0');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        $this->assertTrue($validator->passes(),sprintf(
            "*********************************************************************\nInput: name = '%s' \nOutput: Name field Special character and Number not allowed.\nResult: FAIL\n*********************************************************************",$data['name']
        ));
    }

    public function test_for_pass_name(){
        $data =  array('name'=>'Susanta','contact_email'=>'isusantakumardas456@gmail.com','contact_name'=>'Susanta Kumar Das','contact_phone'=>'99887755667','country_code'=>'AU','lead_expiry_days'=>'90','active'=>'1','email_suppression'=>'0','phone_suppression'=>'0');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        $this->assertFalse($validator->passes(),sprintf(
            "*********************************************************************\nInput: name = '%s' \nOutput: The Name Is valid.\nResult: PASS\n*********************************************************************",$data['name']
        ));
    }

    public function test_for_blank_contact_name(){
        $data =  array('name'=>'Susanta','contact_email'=>'isusantakumardas456@gmail.com','contact_name'=>'','contact_phone'=>'99887755667','country_code'=>'AU','lead_expiry_days'=>'90','active'=>'1','email_suppression'=>'0','phone_suppression'=>'0');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        $this->assertTrue($validator->passes(),sprintf(
            "*********************************************************************\nInput: contact_name = '%s' \nOutput: The name field is required.\nResult: FAIL\n*********************************************************************",$data['contact_name']
        ));
    }

    public function test_for_format_contact_name(){
        $data =  array('name'=>'Susanta','contact_email'=>'isusantakumardas456@gmail.com','contact_name'=>'Susanta/Kumar/Das','contact_phone'=>'99887755667','country_code'=>'AU','lead_expiry_days'=>'90','active'=>'1','email_suppression'=>'0','phone_suppression'=>'0');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        $this->assertTrue($validator->passes(),sprintf(
            "*********************************************************************\nInput: contact_name = '%s' \nOutput: Name field Special character and Number not allowed.\nResult: FAIL\n*********************************************************************",$data['contact_name']
        ));
    }

    public function test_for_pass_contact_name(){
        $data =  array('name'=>'Susanta','contact_email'=>'isusantakumardas456@gmail.com','contact_name'=>'Susanta Kumar Das','contact_phone'=>'99887755667','country_code'=>'AU','lead_expiry_days'=>'90','active'=>'1','email_suppression'=>'0','phone_suppression'=>'0');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);
        $this->assertFalse($validator->passes(),sprintf(
            "*********************************************************************\nInput: contact_name = '%s' \nOutput: The Name Is valid.\nResult: PASS\n*********************************************************************",$data['contact_name']
        ));
    }

    public function test_for_blank_country_code(){
        $data =  array('name'=>'Susanta','contact_email'=>'isusantakumardas456@gmail.com','contact_name'=>'Susanta Kumar Das','contact_phone'=>'99887755667','country_code'=>'','lead_expiry_days'=>'90','active'=>'1','email_suppression'=>'0','phone_suppression'=>'0');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);        
        $this->assertTrue($validator->passes(),sprintf(
            "*********************************************************************\nInput: country_code = '%s' \nOutput: The country code field is required.\nResult: FAIL\n*********************************************************************",$data['country_code']
        ));
    }

    public function test_for_format_country_code(){
        $data =  array('name'=>'Susanta','contact_email'=>'isusantakumardas456@gmail.com','contact_name'=>'Susanta Kumar Das','contact_phone'=>'99887755667','country_code'=>'AU/','lead_expiry_days'=>'90','active'=>'1','email_suppression'=>'0','phone_suppression'=>'0');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);        
        $this->assertTrue($validator->passes(),sprintf(
            "*********************************************************************\nInput: country_code = '%s' \nOutput: The country code field Special character not allowed.\nResult: FAIL\n*********************************************************************",$data['country_code']
        ));
    }

    public function test_for_pass_country_code(){
        $data =  array('name'=>'Susanta','contact_email'=>'isusantakumardas456@gmail.com','contact_name'=>'Susanta Kumar Das','contact_phone'=>'99887755667','country_code'=>'AU','lead_expiry_days'=>'90','active'=>'1','email_suppression'=>'0','phone_suppression'=>'0');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);        
        $this->assertFalse($validator->passes(),sprintf(
            "*********************************************************************\nInput: country_code = '%s' \nOutput: The country is valid.\nResult: PASS\n*********************************************************************",$data['country_code']
        ));
    }

    public function test_for_blank_lead_expiry_days(){
        $data =  array('name'=>'Susanta','contact_email'=>'isusantakumardas456@gmail.com','contact_name'=>'Susanta Kumar Das','contact_phone'=>'99887755667','country_code'=>'AU','lead_expiry_days'=>'','active'=>'1','email_suppression'=>'0','phone_suppression'=>'0');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);        
        $this->assertTrue($validator->passes(),sprintf(
            "*********************************************************************\nInput: lead_expiry_days = '%s' \nOutput: The lead expiry days field is required.\nResult: FAIL\n*********************************************************************",$data['lead_expiry_days']
        ));
    }

    public function test_for_format_lead_expiry_days(){
        $data =  array('name'=>'Susanta','contact_email'=>'isusantakumardas456@gmail.com','contact_name'=>'Susanta Kumar Das','contact_phone'=>'99887755667','country_code'=>'AU','lead_expiry_days'=>'10fg','active'=>'1','email_suppression'=>'0','phone_suppression'=>'0');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);        
        $this->assertTrue($validator->passes(),sprintf(
            "*********************************************************************\nInput: lead_expiry_days = '%s' \nOutput: The lead expiry days field Special character not allowed.\nResult: FAIL\n*********************************************************************",$data['lead_expiry_days']
        ));
    }

    public function test_for_pass_lead_expiry_days(){
        $data =  array('name'=>'Susanta','contact_email'=>'isusantakumardas456@gmail.com','contact_name'=>'Susanta Kumar Das','contact_phone'=>'99887755667','country_code'=>'AU','lead_expiry_days'=>'90','active'=>'1','email_suppression'=>'0','phone_suppression'=>'0');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);        
        $this->assertFalse($validator->passes(),sprintf(
            "*********************************************************************\nInput: lead_expiry_days = '%s' \nOutput: The lead expiry days field Special character not allowed.\nResult: PASS\n*********************************************************************",$data['lead_expiry_days']
        ));
    }

    public function test_for_blank_active(){
        $data =  array('name'=>'Susanta','contact_email'=>'isusantakumardas456@gmail.com','contact_name'=>'Susanta Kumar Das','contact_phone'=>'99887755667','country_code'=>'AU','lead_expiry_days'=>'90','active'=>'','email_suppression'=>'0','phone_suppression'=>'0');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);        
        $this->assertTrue($validator->passes(),sprintf(
            "*********************************************************************\nInput: active = '%s' \nOutput: The active field is required.\nResult: FAIL\n*********************************************************************",$data['active']
        ));
    }

    public function test_for_format_active(){
        $data =  array('name'=>'Susanta','contact_email'=>'isusantakumardas456@gmail.com','contact_name'=>'Susanta Kumar Das','contact_phone'=>'99887755667','country_code'=>'AU','lead_expiry_days'=>'90','active'=>'10/DF','email_suppression'=>'0','phone_suppression'=>'0');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);        
        $this->assertTrue($validator->passes(),sprintf(
            "*********************************************************************\nInput: active = '%s' \nOutput: Active field Special character not allowed.\nResult: FAIL\n*********************************************************************",$data['active']
        ));
    }

    public function test_for_pass_active(){
        $data =  array('name'=>'Susanta','contact_email'=>'isusantakumardas456@gmail.com','contact_name'=>'Susanta Kumar Das','contact_phone'=>'99887755667','country_code'=>'AU','lead_expiry_days'=>'90','active'=>'10','email_suppression'=>'0','phone_suppression'=>'0');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules);        
        $this->assertTrue($validator->passes(),sprintf(
            "*********************************************************************\nInput: active = '%s' \nOutput: Active is valid.\nResult: PASS\n*********************************************************************",$data['active']
        ));
    }

    public function test_create_clients(){
        $data =  array('name'=>'Susanta','contact_email'=>'isusantakumardas456@gmail.com','contact_name'=>'Susanta Kumar Das','contact_phone'=>'99887755667','country_code'=>'AU','lead_expiry_days'=>'90','active'=>'1','email_suppression'=>'0','phone_suppression'=>'0');
        $uservalidation = new Uservalidation;
        $validator = Validator::make($data, Uservalidation::$rules); 
        $check = $validator->passes();
        $this->assertTrue($check,sprintf(
            "*********************************************************************\nInput: data = 'true' \nOutput %s.\nResult: FAIL\n*********************************************************************",$validator->messages()
        ));
        if($check == 1){
            factory(\App\Client::class)->create($data);
            $this->assertDatabaseHas('clients', $data);
            echo 'Successfully Created';
        }
    }
}