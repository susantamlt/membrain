<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\fraudDetection;
use App\Models\fraudAlert;
use App\Supplier;
use DB;
class FraudDetections extends Command
{
    protected $signature = 'frauddetections:clear';
    protected $description = 'Fraud Detection';
    protected $fraudDetection;

    public function __construct(fraudDetection $fraudDetection) {
        parent::__construct();
        $this->fraudDetection = $fraudDetection;
        set_time_limit(0);
    }

    /* when we run signature like 'frauddetections:clear' then always hit handle function we fetch data and cler data from database as per requirement document */
    public function handle() {
        $_results = $this->jsonData(); // call json data
        $results = json_decode($_results); // decode json data
        if(!empty($results)){
            foreach ($results as $key => $_result) {
                $output = array();
                /* Start make Directory */
                $_path = public_path(). '/frauddetections/'.$_result->supplierID.'/';
                \File::makeDirectory($_path, $mode = 0777, true, true);
                \File::cleanDirectory($_path);
                $file = 'frauddetections_'.$_result->supplierID.'.txt';
                /* End make Directory */

                if(!empty($_result->consumerData)){ //check consumer Data empty or not

                	/* start phone Multiple Email */
                    $phoneMultipleEmail = $this->phoneMultipleEmail($_result->consumerData); // call phoneMultipleEmail function
                    if(!empty($phoneMultipleEmail)){
                        $_phoneMultipleEmail = "Phone Number with Multiple Email Addresses \r\n\r\n".implode("\r\n", $phoneMultipleEmail);                        
                        foreach ($phoneMultipleEmail as $pme => $_pmeresult) {
                            $pmeresult = explode(' , ',$_pmeresult);
                            $pmes[] = array('phone'=>$pmeresult[0],'email'=>$pmeresult[1]);
                        }
                        $output[] = array('type'=>'Phone Number with Multiple Email Addresses','data'=>$pmes);
                        unset($pmes);
                        $pemText = 'Phone Number with Multiple Email Addresses , ';
                    } else {
                        $_phoneMultipleEmail = '';
                        $pemText = '';
                    }
                    /* End phone Multiple Email */
                    /* start phone Multiple Names */
                    $phoneMultipleNames = $this->phoneMultipleNames($_result->consumerData);
                    if(!empty($phoneMultipleNames)){
                        $_phoneMultipleNames = "\r\n\r\nPhone Numbers with Multiple Names \r\n\r\n".implode("\r\n", $phoneMultipleNames);
                        foreach ($phoneMultipleNames as $pmn => $_pmnresult) {
                            $pmnresult = explode(' , ',$_pmnresult);
                            $pmns[] = array('phone'=>$pmnresult[0],'name'=>$pmnresult[1]);
                        }
                        $output[] = array('type'=>'Phone Numbers with Multiple Names','data'=>$pmns);
                        unset($pmns);
                        $pmnText = 'Phone Numbers with Multiple Names , ';
                    } else {
                        $_phoneMultipleNames = '';
                        $pmnText = '';
                    }
                    /* End phone Multiple names */
                    /* start phone Multiple Postcode */
                    $phoneMultiplePostcodes = $this->phoneMultiplePostcodes($_result->consumerData);
                    if(!empty($phoneMultiplePostcodes)){
                        $_phoneMultiplePostcodes = "\r\n\r\nPhone Numbers with Multiple Postcodes \r\n\r\n".implode("\r\n", $phoneMultiplePostcodes);
                        foreach ($phoneMultiplePostcodes as $pmpc => $_pmpcresult) {
                            $pmpcresult = explode(' , ',$_pmpcresult);
                            $pmpcs[] = array('phone'=>$pmpcresult[0],'postcode'=>$pmpcresult[1]);
                        }
                        $output[] = array('type'=>'Phone Numbers with Multiple Postcodes','data'=>$pmpcs);
                        unset($pmpcs);
                        $pmpcText = 'Phone Numbers with Multiple Postcodes , ';
                    } else {
                        $_phoneMultiplePostcodes = '';
                        $pmpcText = '';
                    }
                    /* End phone Multiple post Code */
                    /* start email Multiple Phone */
                    $emailMultiplePhone = $this->emailMultiplePhone($_result->consumerData);
                    if(!empty($emailMultiplePhone)){
                        $_emailMultiplePhone = "\r\n\r\nEmail Address with Multiple Phone Numbers \r\n\r\n".implode("\r\n", $emailMultiplePhone);
                        foreach ($emailMultiplePhone as $emp => $_empresult) {
                            $empresult = explode(' , ',$_empresult);
                            $emps[] = array('email'=>$empresult[0],'phone'=>$empresult[1]);
                        }
                        $output[] = array('type'=>'Email Address with Multiple Phone Numbers','data'=>$emps);
                        unset($emps);
                        $empText = 'Email Address with Multiple Phone Numbers , ';
                    } else {
                        $_emailMultiplePhone = '';
                        $empText = '';
                    }
                    /* end email Multiple Phone */
                    /* start email Multiple Names */
                    $emailMultipleNames = $this->emailMultipleNames($_result->consumerData);
                    if(!empty($emailMultipleNames)){
                        $_emailMultipleNames = "\r\n\r\nEmail Address with Multiple Names \r\n\r\n".implode("\r\n", $emailMultipleNames);
                        foreach ($emailMultipleNames as $emn => $_emnresult) {
                            $emnresult = explode(' , ',$_emnresult);
                            $emns[] = array('email'=>$emnresult[0],'name'=>$emnresult[1]);
                        }
                        $output[] = array('type'=>'Email Address with Multiple Names','data'=>$emns);
                        unset($emns);
                        $emnText = 'Email Address with Multiple Names , ';
                    } else {
                        $_emailMultipleNames = '';
                        $emnText = '';
                    }
                    /* end email Multiple names */
                    /* start email Multiple postcode */
                    $emailMultiplePostcodes = $this->emailMultiplePostcodes($_result->consumerData);
                    if(!empty($emailMultiplePostcodes)){
                        $_emailMultiplePostcodes = "\r\n\r\nEmail Address with Multiple Postcodes \r\n\r\n".implode("\r\n", $emailMultiplePostcodes);
                        foreach ($emailMultiplePostcodes as $empc => $_empcresult) {
                            $empcresult = explode(' , ',$_empcresult);                            
                            $empcs[] = array('email'=>$empcresult[0],'postcode'=>$empcresult[1]);
                        }
                        $output[] = array('type'=>'Email Address with Multiple Postcodes','data'=>$empcs);
                        unset($empcs);
                        $empcText = 'Email Address with Multiple Postcodes , ';
                    } else {
                        $_emailMultiplePostcodes = '';
                        $empcText = '';
                    }
                    /* end email Multiple Phone */
                    /* start suspicious Email */
                    $suspiciousEmail = $this->suspiciousEmail($_result->consumerData);
                    if(!empty($suspiciousEmail)){
                        $_suspiciousEmail = "\r\n\r\nSuspicious Email Addresses \r\n\r\n".implode("\r\n", $suspiciousEmail);
                        foreach ($suspiciousEmail as $se => $_seresult) { 
                            $seresult = explode(' , ',$_seresult);                             
                            $ses[] = array('email'=>$seresult[2],'name'=>$seresult[0].' '.$seresult[1]);
                        }
                        $output[] = array('type'=>'Suspicious Email Addresse','data'=>$ses);
                        unset($ses);
                        $seText = 'Suspicious Email Addresse , ';
                    } else {
                        $_suspiciousEmail = '';
                        $seText = '';
                    }
                    /* start suspicious Email */
                }

                if(!empty($output)){
                    $outputdata = array('supplierID'=>$_result->supplierID,'fraud'=>'true','patterns'=>$output);                    
                } else {
                    $outputdata = array('supplierID'=>$_result->supplierID,'fraud'=>'false','patterns'=>$output);
                }

                $mycontent = $_phoneMultipleEmail.$_phoneMultipleNames.$_phoneMultiplePostcodes.$_emailMultiplePhone.$_emailMultipleNames.$_emailMultiplePostcodes.$_suspiciousEmail;
                /* start save alert */
                if($mycontent != ''){
                    \File::put($_path.$file,$mycontent);
                    $supplierName = $this->supplierName($_result->supplierID);
                    $fraudAlert = new fraudAlert;
                    $fraudAlert->supplier_id = $_result->supplierID;
                    $fraudAlert->subject = 'Fraud Alert ('.$supplierName.')';
                    $fraudAlert->body = $pemText.$pmnText.$pmpcText.$empText.$emnText.$empcText.$seText;
                    $fraudAlert->filename = $file;
                    $fraudAlert->acknowledged = 1;
                    $fraudAlert->acknowledged = 1;                    
                    $fraudAlert->created = date('Y-m-d h:i:s');
                    $fraudAlert->save();
                }
                /* End save alert */
                //echo json_encode($outputdata);                
            }
        }
    }
    /*Start Json data createtion */
    public function jsonData(){
        $data = array();
        $results = fraudDetection::groupBy('supplier_id')->get(['supplier_id']);
        if(!empty($results)){
            foreach ($results as $fd => $_result) {
                $sid = $_result->supplier_id;
                $data[$fd]['supplierID'] = $sid;
                $consumerData = fraudDetection::where('supplier_id','=',$sid)->get(['phone','email_address as email','first_name as firstName','last_name as lastName','postcode']);
                if(!empty($consumerData)){
                    foreach ($consumerData as $cd => $value) {
                        $data[$fd]['consumerData'][$cd] = $value;
                    }
                }
            }
        }
        return json_encode($data);
    }
    /*End Json data createtion */
    /*Start getting supplier name */
    public function supplierName($id){
        $suppliers = DB::table('suppliers')->where('id','=',$id)->get(['contact_name']);
        return $suppliers[0]->contact_name;
    }
    /*End getting supplier name */
    /* Start checking phone Multiple Email */
    public function phoneMultipleEmail($results){
        $data = array();
        $fistdata = array();
        $newData = array();
        $i = 0;
        $persentage = (10/100) * count($results);
        if(count($results) > 1){
            foreach ($results as $pme => $_result) {
                if(in_array($_result->phone, $data)){
                    $fistdata[$_result->phone][]=$pme;
                } else {
                    $data[]=$_result->phone;
                    $fistdata[$_result->phone][]=$pme;
                }
            }

            foreach ($fistdata as $fd => $_fistdata) {
                if(count($_fistdata) > 2){
                    foreach ($_fistdata as $key => $value) {
                        $newData[$i] = $results[$value]->phone.' , '.$results[$value]->email;
                        $i++;
                    }
                    break;
                } elseif(count($_fistdata) > 1 && count($_fistdata) >= $persentage){
                    foreach ($_fistdata as $key => $value) {
                        $newData[$i]= $results[$value]->phone.' , '.$results[$value]->email;
                        $i++;
                    }
                }
            }
        }
        return $newData;
    }
    /* End checking phone Multiple Email */
    /* Start checking phone Multiple names */
    public function phoneMultipleNames($results){
        $data = array();
        $fistdata = array();
        $newData = array();
        $i = 0;
        $persentage = (10/100) * count($results);
        foreach ($results as $pme => $_result) {
            if(in_array($_result->phone, $data)){
                $fistdata[$_result->phone][]=$pme;
            } else {
                $data[]=$_result->phone;
                $fistdata[$_result->phone][]=$pme;
            }
        }

        foreach ($fistdata as $fd => $_fistdata) {
            if(count($_fistdata) > 2){
                foreach ($_fistdata as $key => $value) {
                    $newData[$i]= $results[$value]->phone.' , '.$results[$value]->firstName.' '.$results[$value]->lastName;
                    $i++;
                }
                break;
            } elseif(count($_fistdata) > 1 && count($_fistdata) >= $persentage){
                foreach ($_fistdata as $key => $value) {
                    $newData[$i] = $results[$value]->phone.' , '.$results[$value]->firstName.' '.$results[$value]->lastName;
                    $i++;
                }
            }
        }
        return $newData;
    }
    /* End checking phone Multiple names */
    /* Start checking phone Multiple postcode */
    public function phoneMultiplePostcodes($results){
        $data = array();
        $fistdata = array();
        $newData = array();
        $i = 0;
        $persentage = (10/100) * count($results);
        foreach ($results as $pme => $_result) {
            if(in_array($_result->phone, $data)){
                $fistdata[$_result->phone][]=$pme;
            } else {
                $data[]=$_result->phone;
                $fistdata[$_result->phone][]=$pme;
            }
        }

        foreach ($fistdata as $fd => $_fistdata) {
            if(count($_fistdata) > 2){
                foreach ($_fistdata as $key => $value) {
                    $newData[$i]= $results[$value]->phone.' , '.$results[$value]->postcode;
                    $i++;
                }
                break;
            } elseif(count($_fistdata) > 1 && count($_fistdata) >= $persentage){
                foreach ($_fistdata as $key => $value) {
                    $newData[$i]= $results[$value]->phone.' , '.$results[$value]->postcode;
                    $i++;
                }
            }
        }
        return $newData;
    }
    /* End checking phone Multiple postcode */
    /* Start checking email Multiple Phone */
    public function emailMultiplePhone($results){
        $data = array();
        $fistdata = array();
        $newData = array();
        $i = 0;
        $persentage = (10/100) * count($results);
        foreach ($results as $pme => $_result) {
            if(in_array($_result->email, $data)){
                $fistdata[$_result->email][]=$pme;
            } else {
                $data[]=$_result->email;
                $fistdata[$_result->email][]=$pme;
            }
        }

        foreach ($fistdata as $fd => $_fistdata) {
            if(count($_fistdata) > 2){
                foreach ($_fistdata as $key => $value) {
                    $newData[$i] = $results[$value]->email.' , '.$results[$value]->phone;
                    $i++;
                }
                break;
            } elseif(count($_fistdata) > 1 && count($_fistdata) >= $persentage){
                foreach ($_fistdata as $key => $value) {
                    $newData[$i]= $results[$value]->email.' , '.$results[$value]->phone;
                    $i++;
                }
            }
        }
        return $newData;
    }
    /* end checking email Multiple Phone */
    /* Start checking email Multiple name */
    public function emailMultipleNames($results){
        $data = array();
        $fistdata = array();
        $newData = array();
        $i = 0;
        $persentage = (10/100) * count($results);
        foreach ($results as $pme => $_result) {
            if(in_array($_result->email, $data)){
                $fistdata[$_result->email][]=$pme;
            } else {
                $data[]=$_result->email;
                $fistdata[$_result->email][]=$pme;
            }
        }

        foreach ($fistdata as $fd => $_fistdata) {
            if(count($_fistdata) > 2){
                foreach ($_fistdata as $key => $value) {
                    $newData[$i] = $results[$value]->email.' , '.$results[$value]->firstName.' '.$results[$value]->lastName;
                    $i++;
                }
                break;
            } elseif(count($_fistdata) > 1 && count($_fistdata) >= $persentage){
                foreach ($_fistdata as $key => $value) {
                    $newData[$i] = $results[$value]->email.' , '.$results[$value]->firstName.' '.$results[$value]->lastName;
                    $i++;
                }
            }
        }
        return $newData;
    }
    /* end checking email Multiple names */
    /* Start checking email Multiple postcode */
    public function emailMultiplePostcodes($results){
        $data = array();
        $fistdata = array();
        $newData = array();
        $i = 0;
        $persentage = (10/100) * count($results);
        foreach ($results as $pme => $_result) {
            if(in_array($_result->email, $data)){
                $fistdata[$_result->email][]=$pme;
            } else {
                $data[]=$_result->email;
                $fistdata[$_result->email][]=$pme;
            }
        }

        foreach ($fistdata as $fd => $_fistdata) {
            if(count($_fistdata) > 2){
                foreach ($_fistdata as $key => $value) {
                    $newData[$i] = $results[$value]->email.' , '.$results[$value]->postcode;
                    $i++;
                }
                break;
            } elseif(count($_fistdata) > 1 &&  count($_fistdata) >= $persentage){
                foreach ($_fistdata as $key => $value) {
                    $newData[$i] = $results[$value]->email.' , '.$results[$value]->postcode;
                    $i++;
                }
            }
        }
        return $newData;
    }
    /* end checking email Multiple postcode */
    /* start checking suspicious Email */
    public function suspiciousEmail($results){
        $data = array();
        $fistdata = array();
        $newData = array();
        $vowels = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0", " ");
        $i = 0;
        $persentage = (10/100) * count($results);
        if(count($results) > 1){
            foreach ($results as $pme => $_result) {
                $name =strtolower($_result->firstName.$_result->lastName);
                $_plusemails = explode('@', $_result->email);
                if(strpos($_result->email,'+') != false){
                    $_plusemail = explode('+', $_plusemails[0]);
                    $stringemmail = $_plusemail[0].'@'. $_plusemails[1];
                } else {
                    $stringemmail = str_replace($vowels,'',strtolower(str_replace('.','',strtolower($_result->email))));
                }

                if(!in_array($stringemmail, $data)){
                   $data[] = $stringemmail; 
                }  

                if(strpos($_result->email, strtolower($name)) != false && in_array($stringemmail, $data)){
                    $fistdata[$_result->email][]=$pme;
                }                

                if (strpos($_result->email,strtolower($_result->firstName)) != false && in_array($stringemmail, $data)) {
                    $fistdata[$_result->email][]=$pme;
                }

                if (strpos($_result->email,'+') !=false && in_array($stringemmail, $data))  {
                    $fistdata[$stringemmail][]=$pme;
                }

                if(strpos(strtolower($_plusemails[0]),'.') != false && in_array($stringemmail, $data)){
                    $fistdata[$_result->email][]=$pme;
                }                              
            }

            foreach ($fistdata as $fd => $_fistdata) {
                if(count($_fistdata) >= $persentage){
                    foreach ($_fistdata as $key => $value) {
                        $newData[$i] = $results[$value]->firstName.' , '.$results[$value]->lastName.' , '.$results[$value]->email;
                        $i++;
                    }
                }
            }
        }
        return $newData;
    }
    /* end checking suspicious Email */
}