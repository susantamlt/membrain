<?php // Controller name Multipleapi
namespace App\Http\Controllers; //name space mean where controller have
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Multipleapi; //Model name and Where model have
use App\Country; //Model name and Where model have
use Excel; //csv file reader
use DB;
use Carbon\Carbon;
class MultipleapiController extends Controller
{
    /* Start authentication check */
    public function __construct()  {
        $this->middleware('auth');
    }
    /* end authentication check */
    /* Start show all the list of multipleapis */
    public function index() {
        $multipleapis = Multipleapi::leftJoin('countries', function($join) {
            $join->on('multipleapi.country_code', '=', 'countries.code');
        })->get(['multipleapi.id','multipleapi.type','multipleapi.country_code','countries.name','countries.code','multipleapi.apiurl','multipleapi.credentials_detaila','multipleapi.status','multipleapi.created_at']);
        return view('multipleapis.list')
            ->with(compact('multipleapis'));
    }
    /* Start show all the list of multipleapis */
    /* Start show created page */
    public function create() {
        $countries = Country::where('active','=','Yes')->orderBy('sort','asc')->get();
        return view('multipleapis.create')->with(compact('countries'));
    }
    /* end show created page */
    /* Start new data store in database with parameters(all request data)*/
    public function store(Request $request) {
        $multipleapi = new Multipleapi;
        $multipleapi->type = $request->input('type');
        $multipleapi->country_code = $request->input('country_code');
        $multipleapi->apiurl = $request->input('apiurl');
        $multipleapi->credentials_detaila = $request->input('credentials_detaila');
        $multipleapi->status = (bool)$request->input('status');        
        $multipleapi->created_at = Carbon::createFromFormat('Y-m-d g:i A', date('Y-m-d h:i:s a'));
        $multipleapi->updated_at = Carbon::createFromFormat('Y-m-d g:i A', date('Y-m-d h:i:s a'));
        if(!$multipleapi->save()){
            $valid = '0';
        } else {
            \Session::flash('success','Api has been created.');
            $valid = '1';
        }
        return $valid;
    }
    /* End new data store in database with parameters(all request data)*/
    /* Start show multipleapis details not need this project */
    public function show($id) {
        //
    }
    /* End show multipleapis details not need this project */
    /* Start show edit page with all data particuler multipleapis */
    public function edit($id) {
        $countries = Country::where('active','=','Yes')->orderBy('sort','asc')->get();
        $_multipleapi = Multipleapi::where('id','=',$id)->get();
        $multipleapi = $_multipleapi[0];
        return view('multipleapis.edit')->with(compact('countries'))->with(compact('multipleapi'));
    }
    /* Start show edit page with all data particuler multipleapis */
    /* Start data update in database for particular multipleapis with parameters(all request data & and multipleapis id)*/
    public function update(Request $request, $id) {
        $multipleapi = Multipleapi::find($id);
        $multipleapi->type = $request->input('type');
        $multipleapi->country_code = $request->input('country_code');
        $multipleapi->apiurl = $request->input('apiurl');
        $multipleapi->credentials_detaila = $request->input('credentials_detaila');
        $multipleapi->status = (bool)$request->input('status');        
        $multipleapi->updated_at = Carbon::createFromFormat('Y-m-d g:i A', date('Y-m-d h:i:s a'));
        if(!$multipleapi->save()){
            $valid = '0';
        } else {
            \Session::flash('success','Api has been updated.');
            $valid = '1';
        }
        return $valid;
    }
    /* End data update in database for particular multipleapis with parameters(all request data & and multipleapis id)*/
    /* Start particular multipleapis delete from database */    
    public function destroy($id) {
        $multipleapi = Multipleapi::find($id);
        if(!$multipleapi->delete()){
            return 0;
        } else {
            return 1;
        }
    }
    /* End particular multipleapis delete from database */ 
}