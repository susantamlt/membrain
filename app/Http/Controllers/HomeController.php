<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Portal;
use DB;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    /*public function __construct()
    {
        $this->middleware('auth');
    }*/

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        return view('home');
    }

    public function sendmail(Request $request){
        $portal = Portal::where('username',$request->input('username'))->get(['id']);
        if(Portal::where('username','=',$request->input('username'))->exists()){
            DB::table('protal_user')->where('id', $portal[0]->id)->update(array('reset_in_progress' => 1));
            $url = url('password/reset/'.$request->input('_token').'?email='.base64_encode($request->input('username')));

            $to = $request->input('username');

            $subject = 'Reset Password Link';
            $message = 'Hi, <br/>You recently requested to reset your password for Membrain Portal. Click on the link below to reset your password.<br/> <a href="'.$url.'">Reset your Password</a>';

            $headers = 'From: mr.anupamroy@gmail.com' . "\r\n";
            $headers .= 'Reply-To: mr.anupamroy@gmail.com' . "\r\n";
            $headers .= "CC: mr.anupamroy@gmail.com\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
            mail($to, $subject, $message, $headers);
            return 1;
        } else {
            return 0;
        }
    }

    public function newpassword(Request $request){
        $username = base64_decode($request->input('username'));
        $portal = Portal::where('username',$username)->get(['id','reset_in_progress']);
        if(Portal::where('username','=',$username)->exists() && $portal[0]->reset_in_progress==1){
            DB::table('protal_user')->where('id', $portal[0]->id)->update(array('reset_in_progress' => 0,'password'=>bcrypt($request->input('password'))));        
            return 1;
        } else {
            return 0;
        }
    }

    public function getUsername(Request $request){
        if(filter_var($request->input('username'), FILTER_VALIDATE_EMAIL)){
            $portal = Portal::where('username',$request->input('username'))->get(['id','reset_in_progress']);
            if(Portal::where('username','=',$request->input('username'))->exists() && $portal[0]->reset_in_progress==1){     
                return 2;
            } else if(Portal::where('username','=',$request->input('username'))->exists()){
                return 1;
            } else {
                return 0;
            }
        }
    }
}
