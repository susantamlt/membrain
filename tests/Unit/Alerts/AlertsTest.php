<?php

namespace Tests\Unit\Alerts;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Validator;

class AlertValidator {

    public static $rules = array(
		
    );
}

class AlertsTest extends TestCase
{
    public function test_if_alert_is_deleted()
    {
    	$alert = \App\Alert::find(3);
    	if( $alert->delete() ){
    		echo "Alert Deleted.";
    	} else{
    		echo "Alert Delete FAIL.";
    	}
    }
    
}
