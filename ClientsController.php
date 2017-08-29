<?php // Controller name Clients
namespace App\Http\Controllers; //name space mean where controller have
use Illuminate\Http\Request;
use App\Client; //Model name and Where model have
use App\Country; //Model name and Where model have
use App\Models\Api\LeadProcessTable; //Model name and Where model have
use DB;
use Excel; //CSV reader
class ClientsController extends Controller
{
    /* Start authentication check */
    public function __construct()  {
        $this->middleware('auth');
    }
    /* end authentication check */
    /* Start show all the list of clients */
    public function index() {
        $clients=DB::select( DB::raw("SELECT clients.*,(SELECT COUNT(*) FROM protal_user WHERE protal_user.client_id = clients.id) AS PortalUser, (SELECT COUNT(*) FROM campaigns WHERE campaigns.client_id = clients.id) AS TotalCampaigns,(SELECT COUNT(*) FROM portal_sub_client WHERE portal_sub_client.client_id = clients.id) AS sPortalUser FROM  clients  ORDER BY clients.name ASC"));
        return view('clients.list')->with(compact('clients'));
    }
    /* end show all the list of clients */
    /* Start show created page */
    public function create() {
        $countries = Country::where('active','=','Yes')->orderBy('sort','asc')->get();
        return view('clients.create')->with(compact('countries'));
    }
    /* end show created page */
    /* Start new data store in database with parameters(all request data)*/
    public function store(Request $request) {
        $result = DB::table('clients')->where('contact_email',$request->input('contact_email'))->where('contact_phone','=',$request->input('contact_phone'),'OR')->get(['id']);
        if($result->count() <= '0'){
            $client = new Client;
            $client->name = $request->input('name');
            $client->contact_email = $request->input('contact_email');
            $client->contact_name = $request->input('contact_name');
            $client->contact_phone = $request->input('contact_phone');
            $client->active = (bool)$request->input('active');        
            $client->country_code = $request->input('country_code');

            if($request->input('tmp_client_id_email')) {
                $tmp_client_id_email=$request->input('tmp_client_id_email');
                $email_suppression=1;
            }else{
                $email_suppression=0;
            }

            if($request->input('tmp_client_id_phone')) {
                $tmp_client_id_phone=$request->input('tmp_client_id_phone');
                $phone_suppression=1;
            }else{
                $phone_suppression=0;
            }

            $client->email_suppression = $email_suppression;
            $client->phone_suppression = $phone_suppression;            
            $client->lead_expiry_days = $request->input('lead_expiry_days');
            if(!$client->save()){
                return 0;
            } else {
                $insertedId = $client->id;
                if($request->input('tmp_client_id_email')!=''){
                    DB::table('client_email_suppression')
                    ->where('client_id', $request->input('tmp_client_id_email'))
                    ->update(['client_id' => $insertedId]);
                }
                if($request->input('tmp_client_id_phone')!=''){
                    DB::table('client_phone_suppression')
                    ->where('client_id', $request->input('tmp_client_id_phone'))
                    ->update(['client_id' => $insertedId]);
                }
                \Session::flash('success','Client has been created.');
                return 1;
            }
        } else {
            return 2;
        }
    }
    /* end new data store in database */
    /* Start show client details not need this project */
    public function show($client) {
        //
    }
    /* end show client details not need this project */
    /* Start show edit page with all data particuler client */
    public function edit($client) {
        $clients = DB::select(DB::raw("SELECT clients.*,(SELECT COUNT(*) FROM client_email_suppression WHERE client_email_suppression.client_id = clients.id) AS ce_suppression, (SELECT COUNT(*) FROM client_phone_suppression WHERE client_phone_suppression.client_id = clients.id) AS cp_suppression FROM  clients WHERE clients.id='".$client."'"));
        $client = $clients[0];
        $countries = Country::where('active','=','Yes')->orderBy('sort','asc')->get();
        return view('clients.edit')
            ->with(compact('client'))
            ->with(compact('countries'));
    }
    /* end show edit page with all data particuler client */
    /* Start data update in database for particular client with parameters(all request data & and client id)*/
    public function update(Request $request, $client) {
        $result = DB::table('clients')->where('contact_email',$request->input('contact_email'))->where('contact_phone','=',$request->input('contact_phone'),'OR')->get(['id']);
        if($result->count()=='1' && $result[0]->id==$client){
            $_client = Client::find($client);
            $_client->name = $request->input('name');
            $_client->contact_email = $request->input('contact_email');
            $_client->contact_name = $request->input('contact_name');
            $_client->contact_phone = $request->input('contact_phone');
            $_client->active = (bool)$request->input('active');        
            $_client->country_code = $request->input('country_code');

            if($request->input('tmp_client_id_email')) {
                $tmp_client_id_email=$request->input('tmp_client_id_email');
                $email_suppression=1;
            }else{
                $email_suppression=0;
            }

            if($request->input('tmp_client_id_phone')) {
                $tmp_client_id_phone=$request->input('tmp_client_id_phone');
                $phone_suppression=1;
            }else{
                $phone_suppression=0;
            }

            $_client->email_suppression = $email_suppression;
            $_client->phone_suppression = $phone_suppression;
            $_client->lead_expiry_days = $request->input('lead_expiry_days');
            if(!$_client->save()){
                return 0;
            } else {
                $insertedId = $client;

                if($request->input('tmp_client_id_email')!=''){
                    DB::table('client_email_suppression')
                    ->where('client_id', $request->input('tmp_client_id_email'))
                    ->update(['client_id' => $insertedId]);
                }
                if($request->input('tmp_client_id_phone')!=''){
                    DB::table('client_phone_suppression')
                    ->where('client_id', $request->input('tmp_client_id_phone'))
                    ->update(['client_id' => $insertedId]);
                }
                \Session::flash('success','Client has been updated.');
                return 1;
            }
        } else {
            return 2;
        }
    }
    /* end data update in database for particular client with parameters(all request data & and client id) */
    /* Start particular client delete from database */
    public function destroy($client) {
        $_client = Client::find($client);
        if(!$_client->delete()){
            return 0;
        } else {
            DB::table('client_email_suppression')->where('client_id',$client)->delete();
            DB::table('client_phone_suppression')->where('client_id',$client)->delete();
            return 1;
        }
    }
    /* end particular client delete from database */
    /* Start file uploaad & retrive data with store in to database */
    public function upload(Request $request) {
        $regexE = '/^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/';
        $regexP = '/[0-9]/';
        $file = $request->file('myfile');
        $type_file = $request->input('typ');
        $cunCode = $request->input('country_code');
        if($request->input('tmp_client_id_phone')!=''){
            $tmp_client_id = $request->input('tmp_client_id_phone');
        } else if($request->input('tmp_client_id_email')!=''){
            $tmp_client_id = $request->input('tmp_client_id_email');
        } else {
            $tmp_client_id = time()+rand(1000,9999);
        }
        $path = $request->file('myfile')->getRealPath();
        $CountData = Excel::load($path, function($reader) {})->get();
        $data = fopen($path,'r');
        if(!empty($CountData) && $CountData->count() > 0){
            /* Start file upload & retrive client_email_suppression data with store in to database */
            if($type_file=='email'){
                DB::table('client_email_suppression')->where('client_id','=',$tmp_client_id)->delete();
                while (($lineE = fgetcsv($data)) !== FALSE) {
                    $valueE = array('email'=>$lineE[0]);
                    if(!empty($valueE) && preg_match($regexE, $valueE['email'])){
                        $result = DB::table('client_email_suppression')->where('data',$valueE['email'])->get(['id']);
                        if($result->count() <= '0'){
                            $id = DB::table('client_email_suppression')->insertGetId(['client_id'=>$tmp_client_id,'data' => $valueE['email']]);
                            if(!$id){
                                $error[]=0;
                            } else {
                                $succe[] = 1;
                            }
                        } else {
                            $dup[]=0;
                        }
                        $st = 0;
                    } else {
                        $st = 1;
                    }
                }
                fclose($data);
            }
            /* Start file upload & retrive client_email_suppression data with store in to database */
            /* End file upload & retrive client_email_suppression data with store in to database */

            if($type_file=='phone'){
                DB::table('client_phone_suppression')->where('client_id','=',$tmp_client_id)->delete();
                while (($lineP = fgetcsv($data)) !== FALSE) {
                    $value = array('phone_number'=>$lineP[0]);
                    if(!empty($value) && preg_match($regexP, $value['phone_number'])){
                        if($cunCode!=''){
                            $LeadProcessValidation = new LeadProcessTable();
                            $pnpps = $LeadProcessValidation->phoneNumberPreProcessing($value['phone_number'],$cunCode);
                            if($pnpps['status']=='0'){
                                $valid = 0;
                                $errorsNew= 'Invalid Phone Number';
                                $st = 2;
                            } elseif ($pnpps['status']=='2') {
                                $valid = 0;
                                $errorsNew = 'Invalid Phone Number and country code';
                                $st = 2;
                            } elseif ($pnpps['status']=='2'){
                                $valid = 0;
                                $errorsNew = 'Required phone number';
                                $st = 2;
                            } else {
                                $valid = 1;
                                $newphoneNumber = $pnpps['phone'];
                            }
                        } else {
                            $valid = 0;
                            $errorsNew = 'Missing country. Please select country';
                            $st = 2;
                        }

                        if($valid == 1){
                            $result = DB::table('client_phone_suppression')->where('data',$newphoneNumber)->get(['id']);
                            if($result->count() <= '0'){
                                    $id = DB::table('client_phone_suppression')->insertGetId(['client_id'=>$tmp_client_id,'data' =>$newphoneNumber]);
                                if(!$id){
                                    $error[]=0;
                                } else {
                                    $succe[] = 1;
                                }  
                            } else {
                                $dup[]=0;
                            }
                            $st = 0;
                        } else {
                            $error[]=0;
                        }
                    }  else {
                        $st = 1;
                    }
                }
                fclose($data);
            }
            /* End file upload & retrive client_email_suppression data with store in to database */            
        } else {
            $empty=0;
        }
        
        if($st==0){
            $result = array('status'=>'1','tmp_client_id'=>$tmp_client_id,'ccode'=>$cunCode);  
        } elseif($st==2) {
            $result = array('status'=>'2','tmp_client_id'=>$tmp_client_id,'ccode'=>$cunCode,'error'=>$errorsNew);         
        } else {
            $result = array('status'=>'0','tmp_client_id'=>'','ccode'=>$cunCode);
        }

        return $result;
    }
    /* End file uploaad & retrive data with store in to database */
}