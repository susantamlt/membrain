<?php // Controller name Quarantines
namespace App\Http\Controllers; //name space mean where controller have
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Storage; //file storage system
use Illuminate\Http\Response;
use App\Quarantine; //Model name and Where model have
use DB;
class QuarantinesController extends Controller
{
     /* Start authentication check */
    public function __construct()  {
        $this->middleware('auth');
    }
    /* end authentication check */
    /* Start show all the list of quarantines */
    public function index() {
        $quarantines = DB::table('quarantines as qrtine')
            ->select('qrtine.*','clients.contact_name as cname','suppliers.contact_name as sname','campaigns.name as cmpname')
            ->leftjoin('clients', 'qrtine.client_id', '=', 'clients.id')
            ->leftjoin('suppliers', 'qrtine.supplier_id', '=', 'suppliers.id')
            ->leftjoin('campaigns', 'qrtine.campaign_id', '=', 'campaigns.id')
            ->orderBy('suppliers.name', 'ASC')
            ->get();
        return view('quarantines.list')->with(compact('quarantines'));
    }
    /* end show all the list of quarantines */
    /* Start show created page not needed*/
    public function create() {
        //
    }
    /* End show created page not needed*/
    /* Start new data store in database with parameters(all request data)*/
    public function store(Request $request) {
        $path = public_path().'/qurantine-file/' .$request->input('id');
        \File::makeDirectory($path, $mode = 0777, true, true);
        \File::cleanDirectory($path);
        $file = $request->file('myfile');
        $imageFileName = time() . '.' . $file->getClientOriginalExtension();
        if(!$request->myfile->move($path, $imageFileName)){
            $result = array('status'=>'0','fname'=>$imageFileName);
        } else {
            $result = array('status'=>'1','fname'=>$imageFileName);
        }

        //\Storage::move('old/file1.jpg', 'new/file1.jpg');
        return $result;
    }
    /* end new data store in database */
    /* Start show quarantines details parameters(id)*/
    public function show($quarantines) {
        $quarantine = DB::table('quarantines')
            ->select('quarantines.*','clients.contact_name as cname','suppliers.contact_name as sname','campaigns.name as cmpname')
            ->leftjoin('clients', 'quarantines.client_id', '=', 'clients.id')
            ->leftjoin('suppliers', 'quarantines.supplier_id', '=', 'suppliers.id')
            ->leftjoin('campaigns', 'quarantines.campaign_id', '=', 'campaigns.id')
            ->where('quarantines.id',$quarantines)
            ->orderBy('suppliers.name', 'ASC')
            ->get();
        return view('quarantines.show')->with(compact('quarantine'));
    }
    /* End show quarantines details parameters(id)*/
    /* Start show quarantines edit page not need this project */
    public function edit($quarantines) {
        //
    }
    /* Start show quarantines edit page not need this project */
    public function update(Request $request, $quarantines) {
        $quarantine = Quarantine::find($quarantines);
        if($request->input('active')=='Yes'){
            $quarantine->filename = $request->input('filename');
            if(!$quarantine->save()){
                return 0;
            } else {
                return 1;
            }
        } else {
            return 0;
        }
    }
    /* End show quarantines details not need this project */
    /* Start delete file for particular id */
    public function destroy($quarantines) {
        $id = $quarantines;
        $quarantine = Quarantine::find($id);
        $quarantine->filename = '';
        if(!$quarantine->save()){
            return 0;
        } else {
            $path = public_path('qurantine-file').'/'.$id;
            \File::cleanDirectory($path);
            //$deletedQuarantine = Quarantine::destroy($id);
            return 1;
        }
    }
    /* End delete file for particular id */
    /* Start particular quarantines delete from database */
    public function delete($id) {
        if(Quarantine::destroy($id))
            return redirect('quarantines')->with('status', 'Deleted successfully.');
        else
            return redirect('quarantines')->with('delete_error', 'Some problem occurred, please try again.');
    }
    /* End particular quarantines delete from database */
    /* Start particular quarantines csv file */
    public function downloadExcelFile($quarantines){
        $quarantine = Quarantine::find($quarantines);
        if($quarantine->filename !=''){
            $file= public_path(). '/qurantine-file/'.$quarantine->id.'/'.$quarantine->filename;
            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=file.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );
            return response()->download($file,$quarantine->filename,$headers);
        }
    }
    /* End particular quarantines csv file */
}