<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Leadaudit;
use App\Client as MClient;
use App\Campaign;
use App\ProcessCSV;
use App\Models\Api\LeadProcessTable;
use DB;
use Excel;
use File;
use App\Alert;
use App\Quarantine; //Model name and Where model present
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Response;

class ProcessleadcsvController extends Controller
{
    /* Start: authentication check */
    public function __construct()  {
        $this->middleware('auth');
    }
    /* End: authentication check */
    /* Start: Shows the process lead csv form */
    public function index() {
        if(Auth::user()->role_id==1 || Auth::user()->role_id==2) {
            $clients = DB::table('clients')->orderBy('name', 'asc')->get();
            return view('processleadcsv.list', ['clients'=>$clients]);
        } else if(Auth::user()->role_id==5) {
            $user_id = Auth::user()->id;
            $clients = DB::table('portal_sub_client AS ps')
                ->join('clients AS cl', 'ps.client_id', '=', 'cl.id')
                ->select('cl.*')
                ->where('ps.portal_user_id', '=', $user_id)
                ->get();
            return view('processleadcsv.list', ['clients'=>$clients]);
        } else if(Auth::user()->role_id==4) {
            $client_id = Auth::user()->client_id;
            $campaigns = DB::table('campaigns')
                ->where('client_id', '=', $client_id)
                ->where('active', '=', '1')
                ->get();
            $clients = DB::table('clients')
                ->where('id', '=', $client_id)
                ->get();
            return view('processleadcsv.list', ['campaigns' => $campaigns, 'clients'=>$clients]);
        } else {
            return abort(404); //redirect user to resources/views/errors/404.blade.php
        }
    }
    /* End: Shows the process lead csv form */    
    public function create() {
        //
    }

    public function store(Request $request) {
        $data = $request->all();        
        dd($request->all());  
    }

    public function show($id) {
        //
    }

    public function edit($id) {
        //
    }

    public function update(Request $request, $id) {
        //
    }

    public function destroy($id) {
        //
    }
    /* Start: process the uploaded CSV file after user uploads the file */
    public function processcsv(Request $request) {
        $error = array();
        $succe = array();
        $dup = array();
        $empty = 0;
        $delimiter = 0;
        $total_records = 0;
        $status=0;
        if($request->hasFile('myfile')){
            $file_path = $request->file('myfile')->getRealPath();
            $filename = $request->file('myfile')->getClientOriginalName();
            $file_temp_name = $_FILES['myfile']['tmp_name'];
            $file_ext_removed = preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename);
            $arr = explode("_", $file_ext_removed, 2);
            $newfile_name = $arr[0];
            $public_id = Campaign::where('id','=',$request->input('id'))->get(['public_id']); 
            if($public_id[0]->public_id != $newfile_name){
                $name_accept = 0;
                $status=0;
            }else{
                $name_accept = 1;
                if(filesize($file_path)) {
                    $empty=1;
                    $a = file_get_contents($_FILES['myfile']['tmp_name']);
                    $lines = explode( "\r\n" , $a );
                    $each_record = array();
                    foreach ($lines as $key => $value) {
                        if($value != ''){
                            if (substr_count($value,"\t") > 0) {
                                $delimiter = 1;
                            }
                            $total_records++;
                        }
                    }
                    /* checking if Tab delimiter is present in every Line then  */
                    if($delimiter == '1'){
                        $status=1;
                        $temp_path = storage_path("supplier_files/portal_csv_upload/upload/");
                        if(!File::exists($temp_path)) {
                            File::makeDirectory($temp_path, $mode = 0777, true, true);
                            File::cleanDirectory($temp_path);
                        }
                        if(move_uploaded_file($_FILES['myfile']['tmp_name'], $temp_path. $_FILES["myfile"]['name'])) {
                        } else {
                        }
                    }else{
                        $delimiter = 0;
                        $status=0;
                    }
                } 
                else{
                    $empty=0;
                    $status=0;
                }
            }
        }
        return array('filename' => $name_accept,'delimiter' => $delimiter, 'status'=>$status,'total'=>$total_records,'empty'=>$empty,'cid'=>$request->input('id'));
    }
    /* End: process the uploaded CSV file after user uploads the file */
    public function csvProcessingFromPortal(Request $request){
        set_time_limit(0);
        $Response = "";
        $current_sup_id = 0; //beacuse there is no supplier id from portal
        $campaign_id = $request->input('camp_id');
        $query = Campaign::where('id','=',$campaign_id)->get(['public_id']);
        $public_id = $query[0]->public_id;
        $temp_path = storage_path("supplier_files/portal_csv_upload/upload");
        $path = storage_path("process_dir/portal_csv_upload");
        if(!File::exists($path)) {
            File::makeDirectory($path, $mode = 0777, true, true);
        }
        /* START: checking for uploaded files in temp directory */
        $latest_ctime = 0;
        $latest_filename = '';    
        $client_csv = array();
        $return_csv = array();
        $ret_csv_original_rec = array();
        $ret_csv_original_rec2 = array();
        $d = dir($temp_path);
        while (false !== ($entry = $d->read())) {
          $filepath = "{$temp_path}/{$entry}";
          // could do also other checks than just checking whether the entry is a file
          if (is_file($filepath) && filectime($filepath) > $latest_ctime) {
            $latest_ctime = filectime($filepath);
            $latest_filename = $entry;
          }
        } // while
        $file_ext_removed = preg_replace('/\\.[^.\\s]{3,4}$/', '', $latest_filename);
        $arr = explode("_", $file_ext_removed, 2);
        $filename_campaign_id = $arr[0];
        /* END: checking for uploaded files */
        /* Moving csv file to process_dir/portal_csv_upload/) */
        if (strpos($filename_campaign_id, $public_id) !== false) {
            $move = File::move($temp_path.'/'.$latest_filename, $path.'/'.$latest_filename);
            $campaign = DB::table('campaigns AS ca')
                ->leftJoin('clients AS cl', 'ca.client_id', '=', 'cl.id')  
                ->select('ca.*', 'cl.name AS client_name') 
                ->where('ca.id', '=', $campaign_id)
                ->get();

                $current_sup_name = "Supplier name:empty(because no supplier_id)";
                $current_camp_id = $campaign[0]->id;
                $current_client_id = $campaign[0]->client_id;
                $current_camp_name = $campaign[0]->name;
                $current_client_name = $campaign[0]->client_name;
                $current_camp_server_param = json_decode($campaign[0]->server_parameters);
                    $curr_camp_param_map = $campaign[0]->parameter_mapping;
                    $curr_camp_public_id = $campaign[0]->public_id;
                    if($filename_campaign_id == $campaign[0]->public_id){
                        $Response .= "\tFile found is: ".$latest_filename;
                        $delimiter = $this->getFileDelimiter($path, $latest_filename, 5);
                        $Response .= "\nDelimiter is: ".$delimiter;
                        if($delimiter == '\t' && $delimiter!='0'){
                            $Response .= "\tFile is Tab Delimited.\n";
                            $camp_csv_data = json_decode($curr_camp_param_map);
                            $mycampcsvdata = (array)$camp_csv_data;
                            $csv_keys = array_keys((array)$camp_csv_data);
                            $standard_csv_keys = array_keys((array)$camp_csv_data);
                            
                            $file_path = $path.'/'.$latest_filename;
                            $fp = fopen($file_path, "r"); //open the file 
                            $a = file_get_contents( $file_path );
                            fclose($fp); //close file  
                            $lines = explode( "\r\n" , $a );
                            $each_record = array();
                            foreach ($lines as $key => $value) {
                                if($value != ''){
                                    $each_record[] = explode("\t", $value);
                                }
                            } 
                            $mappings = array(); 
                            $mappings_issue = array();
                            $total_records = 0;

                            $originalKeyBeforeMapping = $each_record[0];
                            unset($each_record[0]);
                            $originalKeyBeforeMapping[$mycampcsvdata['email']] = 'email';
                            $originalKeyBeforeMapping[$mycampcsvdata['phone']] = 'phone';
                            $originalKeyBeforeMapping[$mycampcsvdata['title']] = 'title';
                            $originalKeyBeforeMapping[$mycampcsvdata['firstName']] = 'firstName';
                            $originalKeyBeforeMapping[$mycampcsvdata['lastName']] = 'lastName';
                            $originalKeyBeforeMapping[$mycampcsvdata['birthdate']] = 'birthdate';
                            $originalKeyBeforeMapping[$mycampcsvdata['age']] = 'age';
                            $originalKeyBeforeMapping[$mycampcsvdata['ageRange']] = 'ageRange';
                            $originalKeyBeforeMapping[$mycampcsvdata['gender']] = 'gender';
                            $originalKeyBeforeMapping[$mycampcsvdata['address1']] = 'address1';
                            $originalKeyBeforeMapping[$mycampcsvdata['address2']] = 'address2';
                            $originalKeyBeforeMapping[$mycampcsvdata['city']] = 'city';
                            $originalKeyBeforeMapping[$mycampcsvdata['state']] = 'state';
                            $originalKeyBeforeMapping[$mycampcsvdata['postcode']] = 'postcode';
                            $originalKeyBeforeMapping[$mycampcsvdata['countryCode']] = 'countryCode';
                            $originalKeyAfterMapping = $originalKeyBeforeMapping;
                            array_push($originalKeyAfterMapping,"supplier_id","client_id","campaign_id");                            
                            foreach ($each_record as $key => $value) {
                                $result=$value;
                                if(count($result)==count($originalKeyBeforeMapping)){
                                    $result[] = '0';
                                    $result[] = $current_client_id;
                                    $result[] = $current_camp_id;
                                    $f_csvdata_array = array_values($result);
                                    $mappings[] = array_combine((array)$originalKeyAfterMapping, $f_csvdata_array);
                                }else {
                                    $result[] = '0';
                                    $result[] = $current_client_id;
                                    $result[] = $current_camp_id;
                                    $result[]='Miss match mapping';
                                    $f_csvdata_array = array_values($result);
                                    $mappings_issue[] = $f_csvdata_array;
                                }
                                $total_records++;
                            }
                            $total_records = count($each_record);
                            $Response .= "\ncount of mappings_issue: ".count($mappings_issue);
                            $Response .= "\ncount of mappings: ".count($mappings);
                            $successful_lead=0;
                            $invalid_lead=0;
                            $duplicate_lead=0;
                            $arr_res=array();
                            foreach ($mappings as $key => $each_mapping) {
                                $_Model = new LeadProcessTable();
                                $result = $_Model->apiData($each_mapping);
                                $datanew = array();
                                if($result['lead_disposition']['success'] == 'true'){                                    
                                    unset($result['lead_disposition']);
                                    unset($result['audi_flag']);
                                    $datanew = [array_values($result),'Accepted'];
                                    $client_csv[] = $datanew;
                                    $NewResult= $result;
                                    $NewResult['lead_status']='Accepted';
                                    $NewResult['lead_errors']='';
                                    $cli_csv_original_rec[] = $NewResult;
                                    $ret_csv_original_rec[] = $NewResult;
                                    $return_csv[] = array_values($datanew);
                                    $successful_lead++;                            
                                }else{
                                    if(is_array($result['lead_disposition']['error'])){
                                        $error_arr=array();
                                        $error_arr=implode(' ,',$result['lead_disposition']['error']);
                                        unset($result['lead_disposition']);
                                        unset($result['audi_flag']);
                                        $returndata = [array_values($result),$error_arr];  
                                        $return_csv[] = array_values($returndata);
                                        $each_mapping['lead_status']='Invalid';
                                        $each_mapping['lead_errors']=$error_arr;
                                        $ret_csv_original_rec[] = $each_mapping;
                                        $invalid_lead++;
                                    }else{
                                        unset($result['lead_disposition']);
                                        unset($result['audi_flag']);
                                        $returndata = [array_values($result)]; 
                                        $each_mapping['lead_status']='Duplicate Consumer';
                                        $each_mapping['lead_errors']='';
                                        $ret_csv_original_rec[] = $each_mapping;
                                        $return_csv[] = array_values($returndata);
                                        $duplicate_lead++;
                                    }
                                }
                                $current_sup_id = 0; //because there is no supplier_id  
                                $f_detection_data = array("supplier_id"=>$current_sup_id,"source"=>$latest_filename,"client_id"=>$current_client_id,"campaign_id"=>$current_camp_id,"email_address"=>$result['email'],"phone"=>$result['phone'],"first_name"=>$result['firstName'],"last_name"=>$result['lastName'],"postcode"=>$result['postcode'],"received"=>date('Y-m-d H:i:s'));
                                $result = DB::table('fraud_detection')->insert(array($f_detection_data));
                            }
                            /* Creating Return-CSV Excel sheet */
                            $return_csv_filename = $curr_camp_public_id.'_Return-CSV';
                            $client_csv_filename = $curr_camp_public_id.'_Client-CSV';
                            $camp_id_ret_csv_path = 'process_dir/portal_csv_upload/temp/'.$return_csv_filename;
                            $camp_id_cli_csv_path = 'process_dir/portal_csv_upload/temp/'.$client_csv_filename;
                            $ret_csv_file_path = storage_path($camp_id_ret_csv_path);
                            $cli_csv_file_path = storage_path($camp_id_cli_csv_path);
                            if(!empty($mappings_issue)){
                                foreach ($mappings_issue as $key => $_mappings_issue) {
                                    $ret_csv_original_rec[]=$_mappings_issue;
                                }
                            }

                            if(empty($return_csv) && !empty($ret_csv_original_rec)){
                                $return_csv[] = $ret_csv_original_rec;
                            }

                            if(!empty($return_csv)){
                                if(!File::exists($ret_csv_file_path)){
                                    Excel::create($return_csv_filename, function($excel) use ($ret_csv_original_rec){
                                        $excel->sheet('Return-CSV', function($sheet) use ($ret_csv_original_rec) {
                                            $sheet->fromArray($ret_csv_original_rec);
                                        });
                                    })->store('csv', storage_path('process_dir/portal_csv_upload/temp'));
                                }
                            }
                            /* Creating Client-CSV Excel sheet */
                            if(!empty($client_csv)){
                                Excel::create($client_csv_filename, function($excel) use ($cli_csv_original_rec){
                                    $excel->sheet('Client-CSV',function($sheet) use ($cli_csv_original_rec) {
                                        $sheet->fromArray($cli_csv_original_rec);
                                    });
                                })->store('csv', storage_path('process_dir/portal_csv_upload/temp'));
                            }//if
                            $total_error = ($invalid_lead+$duplicate_lead);;
                            $percent_result = ($total_error/$total_records)*100;
                            $current_sup_error_count = 80; //Declared statically for now because there is no supplier_id
                            if($percent_result > $current_sup_error_count){
                                $Response .= "\nThe ratio of invalid leads exceeds the “error_allowance” threshold (defined in the “supplier” table)";
                                $return_csv_filename = $curr_camp_public_id.'_Return-CSV.csv';
                                $alert = new Alert;
                                $alert->supplier_id = $current_sup_id;
                                $alert->subject = "File Quarantined – Excessive Errors";
                                $alert->body = "Supplier name - ".$current_sup_name."Campaign name - ".$current_camp_name."Client name - ".$current_client_name;
                                $alert->filename = $return_csv_filename;
                                $alert->login_usernme = '';
                                $alert->acknowledged = '1';
                                $alert->created = date('Y-m-d H:i:s');
                                if($alert->save()){
                                    $Response .= "\nAlert record generated.";
                                }
                                /* END: generating alert */
                                /* START: adding a quarantine record */
                                $quarantine = new Quarantine;
                                $quarantine->supplier_id = $current_sup_id;
                                $quarantine->client_id = $current_client_id;
                                $quarantine->campaign_id = $current_camp_id;
                                $quarantine->reason = 'Excessive Errors';
                                //$quarantine->filename = $latest_filename;
                                $quarantine->filename = $return_csv_filename;
                                $quarantine->created = date('Y-m-d H:i:s');
                                if($quarantine->save()){
                                    $Response .= "\nQuarantine record generated, File Quarantined.";
                                }
                                /* END: adding a quarantine record */
                                /* START: Return CSV is moved to the quarantine directory for manual quarantine processing */
                                $ret_csv_quarantine_path = storage_path().'/qurantine-file/'.$quarantine->id;
                                $return_csv_filename_qua = $curr_camp_public_id.'_Return-CSV.csv';
                                $camp_id_ret_csv_path_qua = 'process_dir/portal_csv_upload/temp/'.$return_csv_filename_qua;
                                $ret_csv_file_path_qua = storage_path($camp_id_ret_csv_path_qua);
                                File::makeDirectory($ret_csv_quarantine_path, $mode = 0777, true, true);
                                File::cleanDirectory($ret_csv_quarantine_path);
                                File::move($ret_csv_file_path_qua, $ret_csv_quarantine_path.'/'.$return_csv_filename_qua);
                                /* END: Return CSV is moved to the quarantine directory for manual quarantine processing */
                                /* START: Sending Email to supplier */
                                //$emailid = $current_sup_email;
                                $emailid = 'mr.anupamroy@gmail.com';
                                \Mail::send('mailtemplate.sendFileProcessInfo',['name'=>$current_sup_name], function ($message) use ($emailid, $current_sup_name){
                                    $message->from('isusantakumardas@gmail.com', 'Susanta');
                                    $message->to($emailid,$current_sup_name);
                                    $message->subject('CSV File Quarantined');
                                });
                                if( count(\Mail::failures()) > 0 ) {
                                    $Response .= "\nSome problem occurred. Email has not been sent to Supplier.";
                                } else {
                                    //echo "\nEmail has been sent to Supplier.";
                                    $Response .= "\nEmail has been sent to Supplier.";
                                }
                                /* END: Sending Email to supplier */
                                /* Return CSV is delivered back to the client (i.e. moved into the Supplier’s ‘/download’ directory on the SFTP server) if the Supplier has the “return_csv” flag enabled in their “supplier” record. */
                                $new_ret_csv_filename = $curr_camp_public_id.'_Return-CSV_out.csv';
                                $current_sup_return_csv = 1; //Declared statically for now because there is no supplier_id
                                if($current_sup_return_csv == 1){                                    
                                    $dest_path = storage_path().'/supplier_files/portal_csv_upload/download';
                                    File::makeDirectory($dest_path, $mode = 0777, true, true);
                                    File::cleanDirectory($dest_path);
                                    File::copy($ret_csv_quarantine_path.'/'.$return_csv_filename_qua, $dest_path.'/'.$new_ret_csv_filename);
                                }
                                return json_encode(array('status'=>1, 'responseData'=>$Response));                 
                            }else{
                                $Response .= "\tThe error rate does not exceed the threshold";
                                if(isset($current_camp_server_param->server) && $current_camp_server_param->server!='' && isset($current_camp_server_param->user) && $current_camp_server_param->user!='' && isset($current_camp_server_param->password) && $current_camp_server_param->password!=''){
                                    if(isset($current_camp_server_param->directory) && $current_camp_server_param->directory != ''){
                                        $client_csv_filename = $curr_camp_public_id.'_Client-CSV.csv';
                                        $cli_csv_path = 'process_dir/portal_csv_upload/temp/'.$client_csv_filename;
                                        $cli_csv_file_path = storage_path($cli_csv_path);
                                        $host = $current_camp_server_param->server;
                                        $usr = $current_camp_server_param->user;
                                        $pwd = $current_camp_server_param->password;
                                        $local_file = $cli_csv_file_path;
                                        $ftp_path = $current_camp_server_param->directory;
                                        if(File::exists($cli_csv_file_path)){
                                            $conn_id = ftp_connect($host, 21);
                                            if(!$conn_id){
                                                $Response .="\nFTP connection failed.";
                                            }
                                            $login = ftp_login($conn_id,$usr,$pwd);
                                            if ((!$conn_id) || (!$login)) {
                                                $Response .="\n FTP connection has failed!";
                                            } else {
                                                $fff = ftp_pasv($conn_id, true);
                                                $upload = ftp_put($conn_id, $ftp_path.$client_csv_filename, $local_file, FTP_ASCII); 
                                                if($upload == '1'){
                                                    $Response .="\n Client-CSV hasbeen uploaded successfully";
                                                } else {
                                                    $Response .="\n Client-CSV upload Failed";
                                                }
                                            }
                                            ftp_close($conn_id);
                                        }
                                    }else{
                                        $Response .="\npath missmatch, File cannot be uploaded. Directory not set.";
                                    }
                                }
                                /* END: Send file via ftp to server */
                                /* Return CSV is delivered back to the client (i.e. moved into the Supplier’s ‘/download’ directory on the SFTP server) if the Supplier has the “return_csv” flag enabled in their “supplier” record. */
                                $return_csv_filename_qua = $curr_camp_public_id.'_Return-CSV.csv';
                                $camp_id_ret_csv_path_qua = 'process_dir/portal_csv_upload/temp/'.$return_csv_filename_qua;
                                $ret_csv_file_path_qua = storage_path($camp_id_ret_csv_path_qua);
                                $new_ret_csv_filename = $curr_camp_public_id.'_Return-CSV_out.csv';
                                $current_sup_return_csv = 1; //Declared statically for now because there is no supplier_id
                                if($current_sup_return_csv == 1){
                                    $dest_path = storage_path().'/supplier_files/portal_csv_upload/download';
                                    File::makeDirectory($dest_path, $mode = 0777, true, true);
                                    File::cleanDirectory($dest_path);
                                    File::copy($ret_csv_file_path_qua, $dest_path.'/'.$new_ret_csv_filename);
                                } 
                                /* START: Sending Email to supplier */
                                $emailid = 'mr.anupamroy@gmail.com';
                                \Mail::send('mailtemplate.sendSuccessProcessCSVInfo',['name'=>$current_sup_name,'successful'=>$successful_lead,'duplicate'=>$duplicate_lead,'invalid'=>$invalid_lead], function ($message) use ($emailid, $current_sup_name){
                                    $message->from('isusantakumardas@gmail.com', 'Susanta');
                                    $message->to($emailid,$current_sup_name);
                                    $message->subject('CSV file has been processed');
                                });
                                if( count(\Mail::failures()) > 0 ) {
                                    //echo "\nSome problem occurred. Email has not been sent to Supplier.";
                                    $Response .= "\nSome problem occurred. Email has not been sent to Supplier.";
                                } else {
                                    //echo "\nEmail has been sent to Supplier.";
                                    $Response .= "\nEmail has been sent to Supplier.";
                                }
                                /* END: Sending Email to supplier */  
                                 return json_encode(array('status'=>1, 'responseData'=>$Response));                
                            }
                        }else{
                            $Response .= "\nFile is not Tab delimited.";
                            /* generating alert */
                            $alert = new Alert;
                            $alert->subject = "File Quarantined – Invalid Delimiters";
                            $alert->body = "File Quarantined – Invalid Delimiters";
                            $alert->filename = $latest_filename;
                            $alert->login_usernme = '';
                            $alert->acknowledged = '1';
                            $alert->created = date('Y-m-d H:i:s');
                            if($alert->save()){                                
                                $Response .= "\nAlert record generated.";
                            }

                            /* adding a quarantine record */
                            $quarantine = new Quarantine;
                            $quarantine->client_id = $current_client_id;
                            $quarantine->campaign_id = $current_camp_id;
                            $quarantine->reason = 'Invalid Delimiters';
                            $quarantine->filename = $latest_filename;
                            $quarantine->created = date('Y-m-d H:i:s');
                            if($quarantine->save()){
                                $Response .= "\nQuarantine record generated, File Quarantined.";
                            }

                            $source_path = $path;
                            $dest_path = storage_path().'/qurantine-file/' .$quarantine->id;
                            File::makeDirectory($dest_path, $mode = 0777, true, true);
                            File::cleanDirectory($dest_path);
                            File::move($source_path.'/'.$latest_filename, $dest_path.'/'.$latest_filename);                            
                            /* Sending Mail to supplier */
                            //$emailid = $current_sup_email;
                            $emailid = 'mr.anupamroy@gmail.com';
                            
                            \Mail::send('mailtemplate.sendFileProcessInfo',['name'=>$current_sup_name], function ($message) use ($emailid, $current_sup_name){
                                $message->from('isusantakumardas@gmail.com', 'Susanta');
                                $message->to($emailid,$current_sup_name);
                                $message->subject('CSV File Quarantined');
                            });
                            if( count(\Mail::failures()) > 0 ) {
                                $Response .= "\nSome problem occurred. Email has not been sent to Supplier.";
                            } else {
                                $Response .= "\nEmail has been sent to Supplier.";
                            }
                            return json_encode(array('status'=>0, 'responseData'=>$Response)); 
                        }
                    }
                //}//foreach($campaigns)
        }
        
    }
    /*START: It processes the CSV file which is called from CRON job after downloading csv file */
    public function csvprocessing(){
        set_time_limit(0);
        $suppliers = DB::table('suppliers')->select('id','public_id','name','contact_email','error_allowance','return_csv')->get();
        $dir_counter = 0;
        foreach($suppliers as $each_sup){
            $current_sup_id = $each_sup->id;
            $current_sup_email = $each_sup->contact_email;
            $current_sup_name = $each_sup->name;
            $current_sup_error_count = $each_sup->error_allowance;
            $current_sup_return_csv = $each_sup->return_csv;
            $current_sup_pub_id = $each_sup->public_id;
            $sup_id_added_path = 'process_dir/'.$current_sup_pub_id;
            $path = storage_path($sup_id_added_path);
            if(is_dir($path) && ! $this->is_dir_empty($path)) {
                $latest_ctime = 0;
                $latest_filename = '';    
                $client_csv = array();
                $return_csv = array();
                $ret_csv_original_rec = array();
                $ret_csv_original_rec2 = array();
                $d = dir($path);
                while (false !== ($entry = $d->read())) {
                  $filepath = "{$path}/{$entry}";
                  if (is_file($filepath) && filectime($filepath) > $latest_ctime) {
                    $latest_ctime = filectime($filepath);
                    $latest_filename = $entry;
                  }
                } // while
                $file_ext_removed = preg_replace('/\\.[^.\\s]{3,4}$/', '', $latest_filename);
                $arr = explode("_", $file_ext_removed, 2);
                $filename_campaign_id = $arr[0];
                $campaigns = DB::table('campaigns AS ca')
                    ->leftJoin('clients AS cl', 'ca.client_id', '=', 'cl.id')  
                    ->select('ca.*', 'cl.name AS client_name') 
                    ->orderBy('ca.name', 'asc')
                    ->get();

                foreach($campaigns as $each_cam){
                    $csv_process = new ProcessCSV;
                    $current_camp_id = $each_cam->id;
                    $current_client_id = $each_cam->client_id;
                    $current_camp_name = $each_cam->name;
                    $current_client_name = $each_cam->client_name;
                    $current_camp_server_param = json_decode($each_cam->server_parameters);
                    $curr_camp_param_map = $each_cam->parameter_mapping;
                    $curr_camp_public_id = $each_cam->public_id;
                    if($filename_campaign_id == $each_cam->public_id){                        
                        $tab_delimited = $csv_process->check_if_file_is_tab_delimited($path, $latest_filename, 5);
                        if($tab_delimited == '1'){
                            echo "\tFile is Tab Delimited.\n";
                            $camp_csv_data = json_decode($curr_camp_param_map);
                            $mycampcsvdata = (array)$camp_csv_data;
                            $csv_keys = array_keys((array)$camp_csv_data);
                            $standard_csv_keys = array_keys((array)$camp_csv_data);

                            $sup_id_added_path = 'process_dir/'.$current_sup_pub_id;
                            $path = storage_path($sup_id_added_path);
                            $file_path = $path.'/'.$latest_filename;
                            $a = file_get_contents( $file_path ); 
                            $lines = explode( "\r\n" , $a );

                            $each_record = array();
                            foreach ($lines as $key => $value) {
                                if($value != ''){
                                    $each_record[] = explode("\t", $value);
                                }
                            } 

                            $mappings = array(); 
                            $mappings_issue = array(); 
                            $total_records = 0;

                            $originalKeyBeforeMapping = $each_record[0];
                            unset($each_record[0]);
                            $originalKeyBeforeMapping[$mycampcsvdata['email']] = 'email';
                            $originalKeyBeforeMapping[$mycampcsvdata['phone']] = 'phone';
                            $originalKeyBeforeMapping[$mycampcsvdata['title']] = 'title';
                            $originalKeyBeforeMapping[$mycampcsvdata['firstName']] = 'firstName';
                            $originalKeyBeforeMapping[$mycampcsvdata['lastName']] = 'lastName';
                            $originalKeyBeforeMapping[$mycampcsvdata['birthdate']] = 'birthdate';
                            $originalKeyBeforeMapping[$mycampcsvdata['age']] = 'age';
                            $originalKeyBeforeMapping[$mycampcsvdata['ageRange']] = 'ageRange';
                            $originalKeyBeforeMapping[$mycampcsvdata['gender']] = 'gender';
                            $originalKeyBeforeMapping[$mycampcsvdata['address1']] = 'address1';
                            $originalKeyBeforeMapping[$mycampcsvdata['address2']] = 'address2';
                            $originalKeyBeforeMapping[$mycampcsvdata['city']] = 'city';
                            $originalKeyBeforeMapping[$mycampcsvdata['state']] = 'state';
                            $originalKeyBeforeMapping[$mycampcsvdata['postcode']] = 'postcode';
                            $originalKeyBeforeMapping[$mycampcsvdata['countryCode']] = 'countryCode';
                            $originalKeyAfterMapping = $originalKeyBeforeMapping;
                            array_push($originalKeyAfterMapping,"supplier_id","client_id","campaign_id");
                            foreach ($each_record as $key => $value) {
                                $result=$value;
                                if(count($result)==count($originalKeyBeforeMapping)){
                                    $result[] = '0';
                                    $result[] = $current_client_id;
                                    $result[] = $current_camp_id;
                                    $f_csvdata_array = array_values($result);
                                    $mappings[] = array_combine((array)$originalKeyAfterMapping, $f_csvdata_array);
                                }else {
                                    $result[] = '0';
                                    $result[] = $current_client_id;
                                    $result[] = $current_camp_id;
                                    $result[]='Miss match mapping';
                                    $f_csvdata_array = array_values($result);
                                    $mappings_issue[] = $f_csvdata_array;
                                }
                                $total_records++;
                            }
                            $total_records = count($each_record);
                            echo "\ncount of mappings_issue: ".count($mappings_issue);
                            echo "\ncount of mappings: ".count($mappings);
                            $successful_lead=0;
                            $invalid_lead=0;
                            $duplicate_lead=0;
                            $arr_res=array();
                            $datanew = array();
                            foreach ($mappings as $key => $each_mapping) {
                                $_Model = new LeadProcessTable();
                                $result = $_Model->apiData($each_mapping);
                                $datanew = array();
                                if($result['lead_disposition']['success'] == 'true'){                                    
                                    unset($result['lead_disposition']);
                                    unset($result['audi_flag']);
                                    $datanew = [array_values($result),'Accepted'];
                                    $client_csv[] = $datanew;
                                    $NewResult= $result;
                                    $NewResult['lead_status']='Accepted';
                                    $NewResult['lead_errors']='';
                                    $cli_csv_original_rec[] = $NewResult;
                                    $ret_csv_original_rec[] = $NewResult;
                                    $return_csv[] = array_values($datanew);
                                    $successful_lead++;                            
                                }else{
                                    if(is_array($result['lead_disposition']['error'])){
                                        $error_arr=array();
                                        $error_arr=implode(' ,',$result['lead_disposition']['error']);
                                        unset($result['lead_disposition']);
                                        unset($result['audi_flag']);
                                        $returndata = [array_values($result),$error_arr];  
                                        $return_csv[] = array_values($returndata);
                                        $each_mapping['lead_status']='Invalid';
                                        $each_mapping['lead_errors']=$error_arr;
                                        $ret_csv_original_rec[] = $each_mapping;
                                        $invalid_lead++;
                                    }else{
                                        unset($result['lead_disposition']);
                                        unset($result['audi_flag']);
                                        $returndata = [array_values($result)]; 
                                        $each_mapping['lead_status']='Duplicate Consumer';
                                        $each_mapping['lead_errors']='';
                                        $ret_csv_original_rec[] = $each_mapping;
                                        $return_csv[] = array_values($returndata);
                                        $duplicate_lead++;
                                    }
                                }
                                //$current_sup_id = 0; //because there is no supplier_id  
                                $f_detection_data = array("supplier_id"=>$current_sup_id,"source"=>$latest_filename,"client_id"=>$current_client_id,"campaign_id"=>$current_camp_id,"email_address"=>$result['email'],"phone"=>$result['phone'],"first_name"=>$result['firstName'],"last_name"=>$result['lastName'],"postcode"=>$result['postcode'],"received"=>date('Y-m-d H:i:s'));
                                $result = DB::table('fraud_detection')->insert(array($f_detection_data));
                            }
                            /* Creating Return-CSV Excel sheet */
                            $return_csv_filename = $curr_camp_public_id.'_Return-CSV';
                            $client_csv_filename = $curr_camp_public_id.'_Client-CSV';
                            $ret_csv_path = 'process_dir/temp/'.$return_csv_filename;
                            $cli_csv_path = 'process_dir/temp/'.$client_csv_filename;
                            $ret_csv_file_path = storage_path($ret_csv_path);
                            $cli_csv_file_path = storage_path($cli_csv_path);
                            if(!empty($mappings_issue)){
                                foreach ($mappings_issue as $key => $_mappings_issue) {
                                    $ret_csv_original_rec[]=$_mappings_issue;
                                }
                            }

                            if(empty($return_csv) && !empty($ret_csv_original_rec)){
                                $return_csv[] = $ret_csv_original_rec;
                            }
                            
                            if(!empty($return_csv)){
                                if(!File::exists($ret_csv_file_path)){
                                    Excel::create($return_csv_filename, function($excel) use ($ret_csv_original_rec){
                                        $excel->sheet('Return-CSV', function($sheet) use ($ret_csv_original_rec) {
                                            $sheet->fromArray($ret_csv_original_rec);
                                        });
                                    })->store('csv', storage_path('process_dir/temp'));
                                }
                            }//if
                            /* Creating Client-CSV Excel sheet */
                            if(!empty($client_csv)){
                                Excel::create($client_csv_filename, function($excel) use ($cli_csv_original_rec){
                                    $excel->sheet('Client-CSV',function($sheet) use ($cli_csv_original_rec) {
                                        $sheet->fromArray($cli_csv_original_rec);
                                    });
                                })->store('csv', storage_path('process_dir/temp'));
                            }//if
                            $total_error = ($invalid_lead+$duplicate_lead);                           
                            $percent_result = ($total_error/$total_records)*100;
                            echo "\ntotal_records: ".$total_records;
                            echo "\npercent result: ".$percent_result;
                            $_mydestinationPath = 'supplier_files/'.$current_sup_pub_id.'/download/'.$return_csv_filename.'.csv';
                            $mydestinationPath = storage_path($_mydestinationPath);
                            $MYCOPYFILE = File::copy($ret_csv_file_path.'.csv',$mydestinationPath);
                            if($percent_result > $current_sup_error_count){
                                echo "\nThe ratio of invalid leads exceeds the “error_allowance” threshold (defined in the “supplier” table)";
                                /* START: generating alert */
                                /* Initially, Return-CSV file moved to alerts */
                                $dest_path =  storage_path('frauddetections/'.$current_sup_id);
                                $result = File::makeDirectory($dest_path, $mode = 0777, true, true);
                                $return_csv_filename = $curr_camp_public_id.'_Return-CSV.csv';
                                $camp_id_ret_csv_path_qua = 'process_dir/temp';
                                $source_path = storage_path($camp_id_ret_csv_path_qua);
                                $move = File::copy($source_path.'/'.$return_csv_filename, $dest_path.'/'.$return_csv_filename);
                                
                                $alert = new Alert;
                                $alert->supplier_id = $current_sup_id;
                                $alert->subject = "File Quarantined – Excessive Errors";
                                $alert->body = "Supplier name - ".$current_sup_name."Campaign name - ".$current_camp_name."Client name - ".$current_client_name;
                                //$alert->filename = $latest_filename;
                                $alert->filename = $return_csv_filename;
                                $alert->login_usernme = '';
                                $alert->acknowledged = '1';
                                $alert->created = date('Y-m-d H:i:s');
                                if($alert->save()){
                                    echo "\nAlert record generated.";
                                }
                                /* END: generating alert */
                                /* START: adding a quarantine record */
                                $quarantine = new Quarantine;
                                $quarantine->supplier_id = $current_sup_id;
                                $quarantine->client_id = $current_client_id;
                                $quarantine->campaign_id = $current_camp_id;
                                $quarantine->reason = 'Excessive Errors';
                                //$quarantine->filename = $latest_filename;
                                $quarantine->filename = $return_csv_filename;
                                $quarantine->created = date('Y-m-d H:i:s');
                                if($quarantine->save()){
                                    echo "\nQuarantine record generated, File Quarantined.";
                                }
                                /* END: adding a quarantine record */
                                /* START: Return CSV is moved to the quarantine directory for manual quarantine processing */
                                $ret_csv_quarantine_path = storage_path().'/qurantine-file/' .$quarantine->id;
                                $camp_id_ret_csv_path_qua = 'process_dir/temp/'.$return_csv_filename;
                                $ret_csv_file_path_qua = storage_path($camp_id_ret_csv_path_qua);
                                File::makeDirectory($ret_csv_quarantine_path, $mode = 0777, true, true);
                                File::cleanDirectory($ret_csv_quarantine_path);
                                File::move($ret_csv_file_path_qua, $ret_csv_quarantine_path.'/'.$return_csv_filename);
                                /* END: Return CSV is moved to the quarantine directory for manual quarantine processing */
                                /* START: Sending Email to supplier */
                                //$emailid = $current_sup_email;
                                $emailid = 'chinthala.prasad@mindtechlabs.com';
                                \Mail::send('mailtemplate.sendFileProcessInfo',['name'=>$current_sup_name], function ($message) use ($emailid, $current_sup_name){
                                    $message->from('isusantakumardas@gmail.com', 'Susanta');
                                    $message->to($emailid,$current_sup_name);
                                    $message->subject('CSV File Quarantined');
                                });
                                if( count(\Mail::failures()) > 0 ) {
                                    echo "\nSome problem occurred. Email has not been sent to Supplier.";
                                   /*echo "There was one or more failures. They were: <br />";*/
                                } else {
                                    echo "\nEmail has been sent to Supplier.";
                                }
                                /* END: Sending Email to supplier */
                                /* Return CSV is delivered back to the client (i.e. moved into the Supplier’s ‘/download’ directory on the SFTP server) if the Supplier has the “return_csv” flag enabled in their “supplier” record. */
                                $new_ret_csv_filename = $curr_camp_public_id.'_Return-CSV_out.csv';
                                if($current_sup_return_csv == 1){
                                    $dest_path = storage_path().'/supplier_files/'.$current_sup_pub_id.'/download';
                                    File::makeDirectory($dest_path, $mode = 0777, true, true);
                                    File::cleanDirectory($dest_path);
                                    File::copy($ret_csv_quarantine_path.'/'.$return_csv_filename, $dest_path.'/'.$new_ret_csv_filename);
                                }                
                            }else{
                                echo "\tThe error rate does not exceed the threshold";
                                /* START: Client-CSV is delivered to the Client, using the server information stored in the “server_parameters” field in the “campaign” table */
                                /* START: Send file via ftp to server */                                
                                if(isset($current_camp_server_param->server) && $current_camp_server_param->server!='' && isset($current_camp_server_param->user) && $current_camp_server_param->user!='' && isset($current_camp_server_param->password) && $current_camp_server_param->password!=''){
                                    if(isset($current_camp_server_param->directory) && $current_camp_server_param->directory != ''){
                                        $client_csv_filename = $curr_camp_public_id.'_Client-CSV.csv';
                                        $camp_id_cli_csv_path = 'process_dir/temp/'.$client_csv_filename;
                                        $cli_csv_file_path = storage_path($camp_id_cli_csv_path);
                                        if(File::exists($cli_csv_file_path)){
                                            $host = $current_camp_server_param->server;
                                            $usr = $current_camp_server_param->user;
                                            $pwd = $current_camp_server_param->password;
                                            $local_file = $cli_csv_file_path;
                                            $ftp_path = $current_camp_server_param->directory;

                                            $conn_id = ftp_connect($host, 21);
                                            if(!$conn_id){
                                                echo "\nFTP connection failed.";
                                            } else {
                                                $login = ftp_login($conn_id,$usr,$pwd);
                                                if ((!$conn_id) || (!$login)) {
                                                    echo "\n FTP connection has failed!";
                                                } else {
                                                    $fff = ftp_pasv($conn_id, true);
                                                    $upload = ftp_put($conn_id, $ftp_path.$client_csv_filename, $local_file, FTP_ASCII); 
                                                    if($upload == '1'){
                                                        echo "\n Client-CSV hasbeen uploaded successfully";
                                                    } else {
                                                        echo "\n Client-CSV upload Failed";
                                                    }
                                                }
                                                ftp_close($conn_id);
                                            }
                                        }
                                    }else{
                                        echo "\npath missmatch, File cannot be uploaded. Directory not set.";
                                    }
                                }
                                /* END: Send file via ftp to server */
                                /* Return CSV is delivered back to the client (i.e. moved into the Supplier’s ‘/download’ directory on the SFTP server) if the Supplier has the “return_csv” flag enabled in their “supplier” record. */
                                $return_csv_filename = $curr_camp_public_id.'_Return-CSV.csv';
                                $camp_id_ret_csv_path = 'process_dir/temp/'.$return_csv_filename;
                                $ret_csv_file_path_qua = storage_path($camp_id_ret_csv_path);
                                $new_ret_csv_filename = $curr_camp_public_id.'_Return-CSV_out.csv';
                                if($current_sup_return_csv == 1){
                                    $dest_path = storage_path().'/supplier_files/'.$current_sup_pub_id.'/download';
                                    File::makeDirectory($dest_path, $mode = 0777, true, true);
                                    File::cleanDirectory($dest_path);
                                    File::copy($ret_csv_file_path_qua, $dest_path.'/'.$new_ret_csv_filename);
                                } 
                                /* START: Sending Email to supplier */
                                $emailid = 'chinthala.prasad@mindtechlabs.com';
                                \Mail::send('mailtemplate.sendSuccessProcessCSVInfo',['name'=>$current_sup_name,'successful'=>$successful_lead,'invalid'=>$invalid_lead,'duplicate'=>$duplicate_lead], function ($message) use ($emailid, $current_sup_name){
                                    $message->from('isusantakumardas@gmail.com', 'Susanta');
                                    $message->to($emailid,$current_sup_name);
                                    $message->subject('CSV file has been processed');
                                });
                                if( count(\Mail::failures()) > 0 ) {
                                    echo "\nSome problem occurred. Email has not been sent to Supplier.";
                                } else {
                                    echo "\nEmail has been sent to Supplier.";
                                }
                                /* END: Sending Email to supplier */               
                            }
                        }else{
                            echo "\nFile is not Tab delimited.";
                            /* generating alert */
                            $dest_path =  storage_path('frauddetections/'.$current_sup_id);
                            $result = File::makeDirectory($dest_path, $mode = 0777, true, true);
                            if($result == 1){
                                echo "\nfrauddetections directory created with supplier id.";
                            }
                            $sup_id_added_path = 'process_dir/'.$current_sup_pub_id;
                            $path = storage_path($sup_id_added_path);
                            $source_path =  $path;
                            $move = File::copy($source_path.'/'.$latest_filename, $dest_path.'/'.$latest_filename);
                            $alert = new Alert;
                            $alert->supplier_id = $current_sup_id;
                            $alert->subject = "File Quarantined – Invalid Delimiters";
                            $alert->body = "File Quarantined – Invalid Delimiters";
                            $alert->filename = $latest_filename;
                            $alert->login_usernme = '';
                            $alert->acknowledged = '1';
                            $alert->created = date('Y-m-d H:i:s');
                            if($alert->save()){
                                echo "\nAlert record generated.";
                            }
                            /* adding a quarantine record */
                            $quarantine = new Quarantine;
                            $quarantine->supplier_id = $current_sup_id;
                            $quarantine->client_id = $current_client_id;
                            $quarantine->campaign_id = $current_camp_id;
                            $quarantine->reason = 'Invalid Delimiters';
                            $quarantine->filename = $latest_filename;
                            $quarantine->created = date('Y-m-d H:i:s');
                            if($quarantine->save()){
                                echo "\nQuarantine record generated, File Quarantined.";
                            }
                            $source_path = $path;
                            $dest_path = storage_path().'/qurantine-file/' .$quarantine->id;
                            File::makeDirectory($dest_path, $mode = 0777, true, true);
                            File::cleanDirectory($dest_path);
                            File::move($source_path.'/'.$latest_filename, $dest_path.'/'.$latest_filename);                            
                            $emailid = 'chinthala.prasad@mindtechlabs.com';
                            \Mail::send('mailtemplate.sendFileProcessInfo',['name'=>$current_sup_name], function ($message) use ($emailid, $current_sup_name){
                                $message->from('isusantakumardas@gmail.com', 'Susanta');
                                $message->to($emailid,$current_sup_name);
                                $message->subject('CSV File Quarantined');
                            });
                            if( count(\Mail::failures()) > 0 ) {
                                echo "\nSome problem occurred. Email has not been sent to Supplier.";
                            } else {
                                echo "\nEmail has been sent to Supplier.";
                            }
                        }
                        $delete_path = $path.'/'.$latest_filename;
                        $delete = File::delete($delete_path);
                        if($delete == 1){
                            echo "\nFile deleted.";
                        }
                    }
                }//foreach($campaigns)
            }//if(is_dir())
            //echo "\thello";exit;
        }//foreach($suppliers)
    }
    /*END: It processes the CSV file which is called from CRON job after downloading csv file */
    public function is_dir_empty($dir) 
    {
      if (!is_readable($dir)) return NULL; 
      $handle = opendir($dir);
      while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != "..") {
          return FALSE;
        }
      }
      return TRUE;
    } // is_dir_empty();
    public function getFileDelimiter($newpath, $file, $checkLines = 2){
        $filepath = $newpath.'/'.$file;
        $file = new \SplFileObject($filepath);
        $delimiters = array(
          '\t',
          ';',
          '|',
          ':'
        );
        $results = array();
        $i = 0;
         while($file->valid() && $i <= $checkLines){
            $line = $file->fgets();
            foreach ($delimiters as $delimiter){
                $regExp = '/['.$delimiter.']/';
                $fields = preg_split($regExp, $line);
                if(count($fields) > 1){
                    if(!empty($results[$delimiter])){
                        $results[$delimiter]++;
                    } else {
                        $results[$delimiter] = 1;
                    }   
                }
            }
           $i++;
        }
        if(!empty($results)){
            $results = array_keys($results, max($results));
            //print_r($results);
            return $results[0];
        }else{
            return 0;
        }
        
    }// getFileDelimiter();

    public function delIndex($limit,$array){
        $limit_reached=false;
        foreach($array as $ind=>$val){
            if($limit_reached==true){
                unset($array[$ind]);
            }
            if($ind==$limit){
                $limit_reached=true;
            }
        }
        return $array;
    }
}
