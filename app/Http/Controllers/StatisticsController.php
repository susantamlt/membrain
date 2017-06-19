<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use App\Leadaudit;
use App\Client;
use App\Campaign;
use DB;
use Excel;

class StatisticsController extends Controller
{
    /* Start: authentication check */
    public function __construct()  {
        $this->middleware('auth');
    }
    /* End: authentication check */
    public function index() {
        // 
    }

    /* Start: Loads statistics page initially */
    public function create() {
        return view('statistics.create');
    }
    /* End: Loads statistics page initially */
    /*** Start: creates 'statistics' report into csv file by taking inputs: start_date, end_date, suppliers, source, clients & campaign ***/
    public function store(Request $request) {
       /*print_r($request->all());exit;*/
       $startDateArr = array();
       $endDateArr = array();
       $startDateArr = explode('/', $request->startdate);
       $start = $startDateArr[2].'-'.$startDateArr[1].'-'.$startDateArr[0];
       $endDateArr = explode('/', $request->enddate);
       $end = $endDateArr[2].'-'.$endDateArr[1].'-'.$endDateArr[0];
       /*echo $start;
       echo $end;exit;*/

       $user_id = Auth::user()->id;
       //DB::enableQueryLog();
       $results = DB::table('lead_audit AS l');
       
        if((Auth::user()->role_id==1))      //if user_role is 'membrain administrator' that is GLOBALSTATS
        {
            if(isset($request->suppliers) && $request->suppliers == 'suppliers'){
                $results->leftjoin('suppliers AS s', 's.id', '=', 'l.supplier_id')
                        ->addSelect('s.name AS Supplier')
                        ->groupBy('s.name')
                        ->orderBy('s.name');
            }
            if(isset($request->source) && $request->source == 'source'){
                $results->addSelect('l.source AS Source')
                        ->groupBy('l.source')
                        ->orderBy('l.source');
                //$results->leftjoin('source AS so', 'so.id', '=', 'l.source_id')
            } 
            if(isset($request->campaign) && $request->campaign == 'campaign'){
                $results->leftjoin('campaigns AS c', 'c.id', '=', 'l.campaign_id')
                        ->addSelect('c.name AS Campaign')
                        ->groupBy('c.name')
                        ->orderBy('c.name');
            }    
            if(isset($request->clients) && $request->clients == 'clients'){
                $results->leftjoin('clients AS cl', 'cl.id', '=', 'l.client_id')
                        ->addSelect('cl.name AS Client')
                        ->groupBy('cl.name')
                        ->orderBy('cl.name');
            }                   
                                
            $queryResults = $results->addSelect('l.disposition AS Disposition')
                    ->selectRaw('COUNT(*) as Count')
                    ->whereBetween('l.received', [$start, $end])
                    ->groupBy('l.disposition')
                    ->orderBy('l.disposition')
                    ->get()->toArray();
                    
            $count = count($queryResults);
        }
        else if((Auth::user()->role_id==3))     // else if user_role is 'Supplier'
        {
            $supplier_id = Auth::user()->supplier_id;
            if(isset($request->source) && $request->source == 'source'){
                $results->addSelect('l.source AS Source')
                        ->groupBy('l.source')
                        ->orderBy('l.source');
                //$results->leftjoin('source AS so', 'so.id', '=', 'l.source_id');
            } 
            if(isset($request->clients) && $request->clients == 'clients'){
                $results->leftjoin('clients AS cl', 'cl.id', '=', 'l.client_id')
                        ->addSelect('cl.name AS Client')
                        ->groupBy('cl.name')
                        ->orderBy('cl.name');
            }
            if(isset($request->campaign) && $request->campaign == 'campaign'){
                $results->leftjoin('campaigns AS c', 'c.id', '=', 'l.campaign_id')
                        ->addSelect('c.name AS Campaign')
                        ->groupBy('c.name')
                        ->orderBy('c.name');
            }    
            $queryResults = $results->addSelect('l.disposition AS Disposition')
                    ->selectRaw('COUNT(*) as Count')
                    ->where('l.supplier_id', '=', $supplier_id)
                    ->whereBetween('l.received', [$start, $end])
                    ->groupBy('l.disposition')
                    ->orderBy('l.disposition')
                    ->get()->toArray();
                    
                $count = count($queryResults);
            
        }
        else if((Auth::user()->role_id==4))     // else if user_role is 'Client'
        {
            $client_id = Auth::user()->client_id;
            if(isset($request->suppliers) && $request->suppliers == 'suppliers'){
                $results->leftjoin('suppliers AS s', 's.id', '=', 'l.supplier_id')
                        ->addSelect('s.name AS Supplier')
                        ->groupBy('s.name')
                        ->orderBy('s.name');
            }
            if(isset($request->source) && $request->source == 'source'){
                $results->addSelect('l.source AS Source')
                        ->groupBy('l.source')
                        ->orderBy('l.source');
                //$results->leftjoin('source AS so', 'so.id', '=', 'l.source_id');
            } 
            if(isset($request->campaign) && $request->campaign == 'campaign'){
                $results->leftjoin('campaigns AS c', 'c.id', '=', 'l.campaign_id')
                        ->addSelect('c.name AS Campaign')
                        ->groupBy('c.name')
                        ->orderBy('c.name');
            }    
            $queryResults = $results->addSelect('l.disposition AS Disposition')
                    ->selectRaw('COUNT(*) as Count')
                    ->where('l.client_id', '=', $client_id)
                    ->whereBetween('l.received', [$start, $end])
                    ->groupBy('l.disposition')
                    ->orderBy('l.disposition')
                    ->get()->toArray();
                    
                $count = count($queryResults);
        }
        else if(Auth::user()->role_id==5)       // else if user_role is 'Super Client'
        {
            $clients = DB::table('portal_sub_client AS ps')
                        ->join('clients AS cl', 'ps.client_id', '=', 'cl.id')
                        ->select('cl.id AS client_id')
                        ->where('ps.portal_user_id', '=', $user_id)
                        ->get();
            /*echo "<pre>";            
            print_r($clients); 
            echo "</pre>";*/

            $client_ids = array();
            foreach($clients as $key=>$val)
            {
                $client_ids[] = $val->client_id;
            }
            /*echo "<pre>";            
            print_r($client_ids); 
            echo "</pre>"; */
            if(isset($request->suppliers) && $request->suppliers == 'suppliers'){
                $results->leftjoin('suppliers AS s', 's.id', '=', 'l.supplier_id')
                        ->addSelect('s.name AS Supplier')
                        ->groupBy('s.name')
                        ->orderBy('s.name');
            }
            if(isset($request->source) && $request->source == 'source'){
                $results->addSelect('l.source AS Source')
                        ->groupBy('l.source')
                        ->orderBy('l.source');
                //$results->leftjoin('source AS so', 'so.id', '=', 'l.source_id');
            } 
            if(isset($request->campaign) && $request->campaign == 'campaign'){
                $results->leftjoin('campaigns AS c', 'c.id', '=', 'l.campaign_id')
                        ->addSelect('c.name AS Campaign')
                        ->groupBy('c.name')
                        ->orderBy('c.name');
            }    
            if(isset($request->clients) && $request->clients == 'clients'){
                $results->leftjoin('clients AS cl', 'cl.id', '=', 'l.client_id')
                        ->addSelect('cl.name AS Client')
                        ->groupBy('cl.name')
                        ->orderBy('cl.name');
            }                   
                                
            $queryResults = $results->addSelect('l.disposition AS Disposition')
                    ->selectRaw('COUNT(*) as Count')
                    ->whereBetween('l.received', [$start, $end])
                    ->whereIn('l.client_id', $client_ids)
                    ->groupBy('l.disposition')
                    ->orderBy('l.disposition')
                    ->get()->toArray();
                    
            $count = count($queryResults);
        }
        else
        {
            if(isset($request->suppliers) && $request->suppliers == 'suppliers'){
                $results->leftjoin('suppliers AS s', 's.id', '=', 'l.supplier_id')
                        ->addSelect('s.name AS Supplier')
                        ->groupBy('s.name')
                        ->orderBy('s.name');
            }
            if(isset($request->source) && $request->source == 'source'){
                $results->addSelect('l.source AS Source')
                        ->groupBy('l.source')
                        ->orderBy('l.source');
                //$results->leftjoin('source AS so', 'so.id', '=', 'l.source_id');
            } 
            if(isset($request->campaign) && $request->campaign == 'campaign'){
                $results->leftjoin('campaigns AS c', 'c.id', '=', 'l.campaign_id')
                        ->addSelect('c.name AS Campaign')
                        ->groupBy('c.name')
                        ->orderBy('c.name');
            }    
            if(isset($request->clients) && $request->clients == 'clients'){
                $results->leftjoin('clients AS cl', 'cl.id', '=', 'l.client_id')
                        ->addSelect('cl.name AS Client')
                        ->groupBy('cl.name')
                        ->orderBy('cl.name');
            }                   
                                
            $queryResults = $results->addSelect('l.disposition AS Disposition')
                    ->selectRaw('COUNT(*) as Count')
                    ->whereBetween('l.received', [$start, $end])
                    ->groupBy('l.disposition')
                    ->orderBy('l.disposition')
                    ->get()->toArray();
                    
            $count = count($queryResults);
        }

       
        if($count > 0)
        {
            /*dd(
                DB::getQueryLog()
            );
            echo $count;exit;*/
            
            $startDateArr2 = array();
            $endDateArr2 = array();
            $startDateArr2 = explode('/', $request->startdate);
            $startYearFormat = substr($startDateArr2[2], 2);
            $startDateFormat = $startDateArr2[0].''.$startDateArr2[1].''.$startYearFormat;
            //$start2 = implode('',$startDateArr2);

            $endDateArr2 = explode('/', $request->enddate);
            $endYearFormat = substr($endDateArr2[2], 2);
            $endDateFormat = $endDateArr2[0].''.$endDateArr2[1].''.$endYearFormat;
            //$end2 = implode('',$endDateArr2);

            $filename = 'Membrain_Statistics_'.$startDateFormat.'_'.$endDateFormat;

            $results = array_map(function($item) {
                return (array)$item;
            }, $queryResults); 

            return Excel::create('Membrain_Statistics', function($excel) use ($results) {
                $excel->sheet('Membrain_Statistics', function($sheet) use ($results) {
                    $sheet->fromArray($results);
                });
            })->setFilename($filename)
            ->download('csv');
        }
       
    }
    /* End: creates 'statistics' report into csv file by taking inputs: start_date, end_date, suppliers, source, clients & campaign */

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
}
