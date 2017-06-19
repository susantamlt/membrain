<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Leadaudit;
use App\Client;
use App\Campaign;
use DB;
use Excel;

class ProcessleadcsvController extends Controller
{
    /* Start: authentication check */
    public function __construct()  {
        $this->middleware('auth');
    }
    /* End: authentication check */
    /* Start: Shows the process lead csv form */
    public function index()
    {
        //$leads = DB::table('lead_audit')->get();
        if(Auth::user()->role_id==1 || Auth::user()->role_id==2)
        {
            $clients = Client::all();
            return view('processleadcsv.list', ['clients'=>$clients]);
        }
        else if(Auth::user()->role_id==5)
        {
            $user_id = Auth::user()->id;

            $clients = DB::table('portal_sub_client AS ps')
                        ->join('clients AS cl', 'ps.client_id', '=', 'cl.id')
                        ->select('cl.*')
                        ->where('ps.portal_user_id', '=', $user_id)
                        ->get();
            return view('processleadcsv.list', ['clients'=>$clients]);
        }
        else if(Auth::user()->role_id==4)
        {
            $client_id = Auth::user()->client_id;

            $campaigns = DB::table('campaigns')
                         ->where('client_id', '=', $client_id)
                         ->where('active', '=', '1')
                         ->get();   

            $clients = DB::table('clients')
                        ->where('id', '=', $client_id)
                        ->get();

            return view('processleadcsv.list', ['campaigns' => $campaigns, 'clients'=>$clients]);
           /* $data = DB::table('campaigns AS ca')
                    ->leftJoin('clients AS cl', 'ca.client_id', '=', 'cl.id')  
                    ->select('ca.*', 'cl.name AS client_name') 
                    ->where('cl.id', '=', $client_id)
                    ->where('ca.client_id', '=', $client_id)
                    ->get();*/
        }
        else
        {
            return abort(404); //redirect user to resources/views/errors/404.blade.php
        }
    }
    /* End: Shows the process lead csv form */
    
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $data = $request->all();
        
        dd($request->all());  
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
    /* Start: process the uploaded CSV file after user uploads the file */
    public function processcsv(Request $request)
    {
        $error = array();
        $succe = array();
        $dup = array();
        $empty = 1;
        $delimiter = 1;
        $total_records = 0;
        $status=1;
      
        if($request->hasFile('myfile'))
        {
            $path = $request->file('myfile')->getRealPath();
            $filename = $request->file('myfile')->getClientOriginalName();
            $arr = explode(".", $filename, 2);
            $newfile_name = $arr[0];
            //return array('filename' => $filename);

           
            $public_id = Campaign::where('id','=',$request->input('id'))->get(['public_id']);            

            /*print_r($public_id);
            echo $public_id[0]->public_id;  */
            
            //dd(DB::getQueryLog());exit;              
            //$name_matches = Campaign::where('public_id','LIKE',"%{$newfile_name}%")->get();
            if($public_id[0]->public_id != $newfile_name){
                //echo "filename doesn't matches";
                $name_accept = 0;
                $status=0;
            }
            else
            {
                $name_accept = 1;

                $path = $request->file('myfile')->getRealPath();
                $data = Excel::load($path, function($reader) { 
                    $reader->setDelimiter('\t'); 
                })->get();
                /*echo "<pre>";
                print_r($data);
                echo $data->count();
                echo "</pre>";*/


                if( !empty($data) && $data->count() > 0 )
                {

                    //if($file_as_str = file_get_contents($_FILES['myfile']['tmp_name']) !== false )
                    $fh = fopen($path, 'r');
                    if($data1=fgetcsv($fh, 1000, "\t") !== FALSE)
                    {
                      
                        $total_records = count($data);
                        $delimiter = 1;
                        $status=1;
                        
                    }
                    else
                    {
                        //echo "else";exit;
                        $delimiter = 0;
                        $status=0;
                    }
                   
                } 
                else
                {
                    $empty=0;
                    $status=0;
                }
                
            }
        }
       
        return array('filename' => $name_accept,'delimiter' => $delimiter, 'status'=>$status,'total'=>$total_records,'empty'=>$empty,'cid'=>$request->input('id'));
    }
    /* End: process the uploaded CSV file after user uploads the file */
}
