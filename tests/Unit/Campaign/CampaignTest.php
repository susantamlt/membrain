<?php

namespace Tests\Unit\Campaign;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Validator;

class CampaignValidator {
    public static $rules = array(
		'public_id'=>'string',
        'client_id'=>'required|integer',
        'name'=>'required|alpha|string|max:255',
        'criteria_age'=>'string',
        'criteria_state'=>'string',
        'criteria_postcode'=>'string',
        'dncr_required'=>'required|boolean',
        'criteria_state'=>'string',
        'active'=>'boolean',
        'method'=>'required|string',
        'server_parameters'=>'string',
        'parameter_mapping'=>'string',
    );
}

class CampaignTest extends TestCase {

    public function test_for_blank_campaign_name(){
    	$data = array('name' => '');
        $campaign = new CampaignValidator;
        $validator = Validator::make($data, CampaignValidator::$rules);
        $this->assertTrue($validator->passes(),sprintf(
            "*********************************************************************\nInput: campaign_name = '' \nOutput: The campaign_name field is required.\nResult: FAIL\n*********************************************************************"
        ));
    }
    public function test_for_valid_campaign_name(){
    	$data = array('name' => 'abcdef','public_id'=>'ajgdajgagdgd','client_id'=>'1','criteria_age'=>'1-10','criteria_state'=>'state','criteria_postcode'=>'123','dncr_required'=>'1','active'=>'1','method'=>'API','server_parameters'=>'','parameter_mapping'=>'');
        $campaign = new CampaignValidator;
        $validator = Validator::make($data, CampaignValidator::$rules);
        $this->assertFalse($validator->passes(),sprintf(
            "*********************************************************************\nInput: campaign_name = 'abcdef' \nOutput: The campaign_name is valid.\nResult: PASS\n*********************************************************************"
        ));
    }
    public function test_for_invalid_campaign_name(){
    	$data = array('name' => 'abcdef1');
        $campaign = new CampaignValidator;
        $validator = Validator::make($data, CampaignValidator::$rules);
        $this->assertTrue($validator->passes(),sprintf(
            "*********************************************************************\nInput: campaign_name = 'abcdef1' \nOutput: The campaign_name must be a valid.\nResult: FAIL\n*********************************************************************"
        ));
    }
    public function test_for_blank_client_id(){
    	$data = array('client_id'=>'');
        $campaign = new CampaignValidator;
        $validator = Validator::make($data, CampaignValidator::$rules);
        $this->assertTrue($validator->passes(),sprintf(
            "*********************************************************************\nInput: client_id = '' \nOutput: The client_id field is required.\nResult: FAIL\n*********************************************************************"
        ));
    }
    public function test_for_valid_client_id(){
    	$data = array('name' => 'abcdef','public_id'=>'ajgdajgagdgd','client_id'=>'1','criteria_age'=>'1-10','criteria_state'=>'state','criteria_postcode'=>'123','dncr_required'=>'1','active'=>'1','method'=>'API','server_parameters'=>'','parameter_mapping'=>'');
        $campaign = new CampaignValidator;
        $validator = Validator::make($data, CampaignValidator::$rules);
        $this->assertFalse($validator->passes(),sprintf(
            "*********************************************************************\nInput: client_id = '1' \nOutput: The client_id is valid.\nResult: PASS\n*********************************************************************"
        ));
    }
    public function test_for_blank_campaign_method(){
    	$data = array('method'=>'');
        $campaign = new CampaignValidator;
        $validator = Validator::make($data, CampaignValidator::$rules);
        $this->assertTrue($validator->passes(),sprintf(
            "*********************************************************************\nInput: method = '' \nOutput: The campaign 'method' field is required.\nResult: FAIL\n*********************************************************************"
        ));
    }
    public function test_for_valid_campaign_method(){
    	$data = array('name' => 'abcdef','public_id'=>'ajgdajgagdgd','client_id'=>'1','criteria_age'=>'1-10','criteria_state'=>'state','criteria_postcode'=>'123','dncr_required'=>'1','active'=>'1','method'=>'API','server_parameters'=>'','parameter_mapping'=>'');
        $campaign = new CampaignValidator;
        $validator = Validator::make($data, CampaignValidator::$rules);
        $this->assertFalse($validator->passes(),sprintf(
            "*********************************************************************\nInput: method = 'API' \nOutput: The campaign 'method' is valid.\nResult: PASS\n*********************************************************************"
        ));
    }
    public function test_for_dncr_required_is_not_checked(){
    	$data = array('dncr_required' => '');
        $campaign = new CampaignValidator;
        $validator = Validator::make($data, CampaignValidator::$rules);
        $this->assertTrue($validator->passes(),sprintf(
            "*********************************************************************\nInput: dncr_required = '' \nOutput: The 'dncr_required' field is required.\nResult: FAIL\n*********************************************************************"
        ));
    }
    public function test_for_dncr_required_is_checked(){
    	$data = array('name' => 'abcdef','public_id'=>'ajgdajgagdgd','client_id'=>'1','criteria_age'=>'1-10','criteria_state'=>'state','criteria_postcode'=>'123','dncr_required'=>'1','active'=>'1','method'=>'API','server_parameters'=>'','parameter_mapping'=>'');
        $campaign = new CampaignValidator;
        $validator = Validator::make($data, CampaignValidator::$rules);
        $this->assertFalse($validator->passes(),sprintf(
            "*********************************************************************\nInput: dncr_required = '1' \nOutput: The 'dncr_required' field is valid.\nResult: PASS\n*********************************************************************"
        ));
    }
    public function test_for_active_field_is_not_checked(){
    	$data = array('active' => '');
        $campaign = new CampaignValidator;
        $validator = Validator::make($data, CampaignValidator::$rules);
        $this->assertTrue($validator->passes(),sprintf(
            "*********************************************************************\nInput: active = '' \nOutput: The 'active' field is required.\nResult: FAIL\n*********************************************************************"
        ));
    }
    public function test_for_active_field_is_checked(){
    	$data = array('name' => 'abcdef','public_id'=>'ajgdajgagdgd','client_id'=>'1','criteria_age'=>'1-10','criteria_state'=>'state','criteria_postcode'=>'123','dncr_required'=>'1','active'=>'1','method'=>'API','server_parameters'=>'','parameter_mapping'=>'');
        $campaign = new CampaignValidator;
        $validator = Validator::make($data, CampaignValidator::$rules);
        $this->assertFalse($validator->passes(),sprintf(
            "*********************************************************************\nInput: active = '1' \nOutput: The 'active' field is valid.\nResult: PASS\n*********************************************************************"
        ));
    }
    public function test_if_campaign_is_created(){
    	$data = array('name' => 'xxx',
      	'public_id'=>'abcdefghijklmno',
      	'client_id'=>'1',
      	'criteria_age'=>'1-10',
      	'criteria_state'=>'state',
      	'criteria_postcode'=>'123',
      	'dncr_required'=>'1',
      	'active'=>'1',
      	'method'=>'API',
      	'server_parameters'=>'',
      	'parameter_mapping'=>'');
        $campaign = new CampaignValidator;
        $validator = Validator::make($data, CampaignValidator::$rules);
        //echo $validator->messages();
        $check = $validator->passes();
        $this->assertTrue($validator->passes(),sprintf("Campaign has been created."));
        if($check == 1){
        	factory(\App\Campaign::class)->create($data);
        	$this->assertDatabaseHas('campaigns', $data);
        }
    }
    public function test_if_campaign_is_updated(){
    	$data = array('name' => 'zzz',
      	'public_id'=>'zyxwvutsrqp',
      	'client_id'=>'1',
      	'criteria_age'=>'1-100',
      	'criteria_state'=>'criteria_state',
      	'criteria_postcode'=>'123456',
      	'dncr_required'=>'0',
      	'active'=>'1',
      	'method'=>'CSV',
      	'server_parameters'=>'',
      	'parameter_mapping'=>'');

        $validator = Validator::make($data, CampaignValidator::$rules);
        //echo $validator->messages();
        $check = $validator->passes();
        $this->assertTrue($validator->passes(),sprintf(
        	"*********************************************************************\nInput: active = '1' \nOutput: Campaign has been updated.\nResult: PASS\n*********************************************************************"
        	));
        if($check == 1){
        	$campaign = \App\Campaign::find(80);
        	$campaign->client_id = $data['client_id'];
        	$campaign->name = $data['name'];
        	$campaign->criteria_age = $data['criteria_age'];
        	$campaign->criteria_state = $data['criteria_state'];
        	$campaign->criteria_postcode = $data['criteria_postcode'];
        	$campaign->dncr_required = $data['dncr_required'];
        	$campaign->active = $data['active'];
        	$campaign->method = $data['method'];
        	$campaign->server_parameters = $data['server_parameters'];
        	$campaign->parameter_mapping = $data['parameter_mapping'];
        	if( $campaign->save() ){
        		echo "Campaign has been updated.";
        	} else{
        		echo "Campaign Update FAIL.";
        	}
        	//factory(\App\Campaign::class)->create($data);
        	//$this->assertDatabaseHas('campaigns', ['name' => 'xxy']);
        }
    }
    /*public function test_if_campaign_is_deleted(){
    	$campaign = \App\Campaign::find(111);
    	if( $campaign->delete() ){
    		echo "Campaign Deleted.";
    	} else{
    		echo "Campaign Delete FAIL.";
    	}
    }*/

}


