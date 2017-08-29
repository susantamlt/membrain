<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Campaign;
use DB;
class CampaignController extends Controller
{
    /* Start authentication check */
    public function __construct()  {
        $this->middleware('auth');
    }
    /* end authentication check */
    /* Start show all the list of campaign */
    public function index() {
        $campaigns = DB::table('campaigns AS ca')
            ->leftJoin('clients AS cl', 'ca.client_id', '=', 'cl.id')  
            ->select('ca.*', 'cl.name AS client_name') 
            ->orderBy('ca.name', 'asc')
            ->get();      
        return view('campaign.list', ['campaigns' => $campaigns]);
    }
    /* End show all the list of campaign */
    /* Start: show campaign create page */
    public function create() {
        $clients = DB::table('clients')->orderBy('name', 'asc')->get();
        return view('campaign.create', ['clients' => $clients]);
    }
    /* End: show campaign create page */
    /* Start: new campaign record stored into database with all parameters(all request data)*/
    public function store(Request $request) {
        $data = $request->all();
        if(isset($data['csv']) && $data['cpmethod']=='CSV'){
            $dataCsv = $data['csv'];
        } else {
            $dataCsv = array();
        }

        if(isset($data['api']) && $data['cpmethod']=='API'){
            $dataApi = $data['api'];
        } else {
            $dataApi = array();
        }

        if($data['cpmethod']=='CSV'){
            $campaignCSV = new Campaign;
            $_requiredField = $campaignCSV->campaignCsvRequiredField($dataCsv);
            $requiredField = json_encode(array('csv'=>$_requiredField));
        } elseif ($data['cpmethod']=='API') {
            $campaignAPI = new Campaign;
            $_requiredField = $campaignAPI->campaignApiRequiredField($dataApi);
            $requiredField = json_encode(array('api'=>$_requiredField));
        } else {
            $requiredField = '';
        }

        $campaign = new Campaign;
        $campaign->public_id = hash('md5', 'MB#'.str_random(42));
        $campaign->client_id = $data['client_name'];
        $campaign->name = $data['name'];
        $campaign->parameter_required = $requiredField;
        if(isset($data['age_criteria']) && $data['age_criteria'] == 'on') {
            $campaign->criteria_age = $data['start_age'].'-'.$data['end_age'];
        } else {
            $campaign->criteria_age = '';
        }

        if(isset($data['state_criteria']) && $data['state_criteria'] == 'on') {
            $campaign->criteria_state = $data['state_list'];
        } else {
            $campaign->criteria_state = '';
        }
        
        if(isset($data['postcode_criteria']) && $data['postcode_criteria'] == 'on') {
            $campaign->criteria_postcode = $data['postcode_list'];
        } else {
            $campaign->criteria_postcode = '';
        }
        
        if(isset($request->dncr_required)){
            $campaign->dncr_required = $data['dncr_required'];
        }else{
            $campaign->dncr_required = '';
        }
        
        if(isset($request->active) && $request->active == 1) {
            $campaign->active = 1;
        } else {
            $campaign->active = 0;
        }
        
        $campaign->method = $data['cpmethod'];
        
        if($data['cpmethod'] == 'API') {
            $sp_json_array = array(
                'type'          =>  $request->type,
                'endpoint'  =>  $request->endpoint,
                'user'  =>  $request->user,
                'password'  =>  $request->password,
                'port'  =>  $request->port
            );
            $campaign->server_parameters = json_encode($sp_json_array);
            $json_array = array(
                'email' =>  $request->email,
                'phone' =>  $request->phone,
                'title' =>  $request->title,
                'firstName' =>  $request->firstName,
                'lastName' =>  $request->lastName,
                'birthdate' =>  $request->birthdate,
                'age' =>  $request->age,
                'ageRange' =>  $request->ageRange,
                'gender' =>  $request->gender,
                'address1' =>  $request->address1,
                'address2' =>  $request->address2,
                'city' =>  $request->city,
                'state' =>  $request->state,
                'postcode' =>  $request->postcode,
                'countryCode' =>  $request->countryCode
            );
            $campaign->parameter_mapping = json_encode($json_array);
        } else if($data['cpmethod'] == 'CSV') {
            $sp_json_array = array(
                'type'  =>  $request->server_type,
                'server'  =>  $request->csv_server,
                'directory'  =>  $request->directory,
                'user'  =>  $request->csv_user,
                'password'  =>  $request->csv_password
            );
            $campaign->server_parameters = json_encode($sp_json_array);
            $json_array = array(
                'email' =>  $request->csv_email,
                'phone' =>  $request->csv_phone,
                'title' =>  $request->csv_title,
                'firstName' =>  $request->csv_firstName,
                'lastName' =>  $request->csv_lastName,
                'birthdate' =>  $request->csv_birthdate,
                'age' =>  $request->csv_age,
                'ageRange' =>  $request->csv_ageRange,
                'gender' =>  $request->csv_gender,
                'address1' =>  $request->csv_address1,
                'address2' =>  $request->csv_address2,
                'city' =>  $request->csv_city,
                'state' =>  $request->csv_state,
                'postcode' =>  $request->csv_postcode,
                'countryCode' =>  $request->csv_countryCode
            );
            $campaign->parameter_mapping = json_encode($json_array);
        } else {
            $campaign->server_parameters = '';
            $campaign->parameter_mapping = '';
        }
        if(!$campaign->save()){            
            return 0;
        } else{
            \Session::flash('success','Campaign has been created.');
            return 1;
        }
    }
    /* End: new campaign data stored into database with all parameters(all request data)*/

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /* Start: Loads campaign edit form with selected campaign data */
    public function edit($id) {
        $campaign = DB::table('campaigns AS ca')
            ->leftJoin('clients AS cl', 'ca.client_id', '=', 'cl.id')  
            ->select('ca.*', 'cl.name AS client_name', 'cl.id AS client_id') 
            ->where('ca.id', '=', $id)
            ->get();
        return view('campaign.edit', ['campaign' => $campaign]);            
    }
    /* End: Loads campaign edit form with selected campaign data */
    /* Start: Update campaign data into database*/
    public function update(Request $request, $id) {
        $data = $request->all();
        if(isset($data['csv']) && $data['cpmethod']=='CSV'){
            $dataCsv = $data['csv'];
        } else {
            $dataCsv = array();
        }

        if(isset($data['api']) && $data['cpmethod']=='API'){
            $dataApi = $data['api'];
        } else {
            $dataApi = array();
        }

        if($data['cpmethod']=='CSV'){
            $campaignCSV = new Campaign;
            $_requiredField = $campaignCSV->campaignCsvRequiredField($dataCsv);
            $requiredField = json_encode(array('csv'=>$_requiredField));
        } elseif ($data['cpmethod']=='API') {
            $campaignAPI = new Campaign;
            $_requiredField = $campaignAPI->campaignApiRequiredField($dataApi);
            $requiredField = json_encode(array('api'=>$_requiredField));
        } else {
            $requiredField = '';
        }

        $campaign = Campaign::find($id);
        $campaign->public_id = $data['public_id'];
        $campaign->client_id = $data['client_name'];
        $campaign->name = $data['name'];
        $campaign->parameter_required = $requiredField;
        if(isset($data['age_criteria']) && $data['age_criteria'] == 'on') {
            $campaign->criteria_age = $data['start_age'].'-'.$data['end_age'];
        } else {
            $campaign->criteria_age = '';
        }

        if(isset($data['state_criteria']) && $data['state_criteria'] == 'on') {
            $campaign->criteria_state = $data['state_list'];
        } else {
            $campaign->criteria_state = '';
        }
        
        if(isset($data['postcode_criteria']) && $data['postcode_criteria'] == 'on') {
            $campaign->criteria_postcode = $data['postcode_list'];
        } else {
            $campaign->criteria_postcode = '';
        }
        
        $campaign->dncr_required = $data['dncr_required'];

        if(isset($data['active']) && $data['active'] == 1) {
            $campaign->active = 1;
        } else {
            $campaign->active = 0;
        }
        
        $campaign->method = $data['cpmethod'];
        if($data['cpmethod'] == 'API') {
            $sp_json_array = array(
                'type'          =>  $request->type,
                'endpoint'  =>  $request->endpoint,
                'user'  =>  $request->user,
                'password'  =>  $request->password,
                'port'  =>  $request->port
            );
            $campaign->server_parameters = json_encode($sp_json_array);
            $json_array = array(
                'email' =>  $request->email,
                'phone' =>  $request->phone,
                'title' =>  $request->title,
                'firstName' =>  $request->firstName,
                'lastName' =>  $request->lastName,
                'birthdate' =>  $request->birthdate,
                'age' =>  $request->age,
                'ageRange' =>  $request->ageRange,
                'gender' =>  $request->gender,
                'address1' =>  $request->address1,
                'address2' =>  $request->address2,
                'city' =>  $request->city,
                'state' =>  $request->state,
                'postcode' =>  $request->postcode,
                'countryCode' =>  $request->countryCode
            );
            $campaign->parameter_mapping = json_encode($json_array);
        } else if($data['cpmethod'] == 'CSV') {
            $sp_json_array = array(
                'type'  =>  $request->server_type,
                'server'  =>  $request->csv_server,
                'directory'  =>  $request->directory,
                'user'  =>  $request->csv_user,
                'password'  =>  $request->csv_password
            );
            $campaign->server_parameters = json_encode($sp_json_array);
            $json_array = array(
                'email' =>  $request->csv_email,
                'phone' =>  $request->csv_phone,
                'title' =>  $request->csv_title,
                'firstName' =>  $request->csv_firstName,
                'lastName' =>  $request->csv_lastName,
                'birthdate' =>  $request->csv_birthdate,
                'age' =>  $request->csv_age,
                'ageRange' =>  $request->csv_ageRange,
                'gender' =>  $request->csv_gender,
                'address1' =>  $request->csv_address1,
                'address2' =>  $request->csv_address2,
                'city' =>  $request->csv_city,
                'state' =>  $request->csv_state,
                'postcode' =>  $request->csv_postcode,
                'countryCode' =>  $request->csv_countryCode
            );
            $campaign->parameter_mapping = json_encode($json_array);
        } else {
            $campaign->server_parameters = '';
            $campaign->parameter_mapping = '';
        }

        // $task = Campaign::findOrFail($id);
        // $task->fill($campaign)->save();

        if(!$campaign->save()){
            return 0; 
        } else{
            \Session::flash('success','Campaign has been updated.');
            return 1;
        }
    }
    /* END: Update campaign into database*/
    /* Start: Delete campaign from database */
    public function destroy($campaign) {
        $_campaign = Campaign::find($campaign);
        if(!$_campaign->delete()){
            return 0;
        } else {
            return 1;
        }
    }
    /* End: delete campaign from database */
    // Start: get the compaign list by Client ID, if status=1 ? 'active':'' 
    public function getCampaignsByClient(Request $request) {
        $campaigns = Campaign::where('client_id', '=' ,$request->input('id'))
            ->where('active', '=' ,'1')
            ->orderBy('name', 'asc')
            ->get();
        return json_encode(array('status'=>1, 'campaigns'=>$campaigns));
    }
    /** END **/
}