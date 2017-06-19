<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\clearEmailhistory;
use DB;
class Emailhistory extends Command
{
    protected $signature = 'emailhistory:clear';
    protected $description = 'Clear Email History';
    protected $clearEmailhistory;
    
    public function __construct(clearEmailhistory $clearEmailhistory) {
        parent::__construct();
        $this->clearEmailhistory = $clearEmailhistory;
        set_time_limit(0);
    }

    /* when we run signature like 'emailhistory:clear' then always hit handle function we fetch data and cler data from database as per requirement document */
    public function handle() {
        $date = date('Y-m-d');
        $validationdate = date('Y-m-d', strtotime($date .' -180 day'));
        $emailhistorys = DB::table('email_history as emailhistory')->select('emailhistory.id','emailhistory.is_valid')
            ->where('emailhistory.validation_date','<',$validationdate)
            ->get(); //fatch all data from database

        if(!empty($emailhistorys)){
            foreach ($emailhistorys as $key => $_emailhistory) {  
                DB::table('email_history')
                    ->where('id','=', $_emailhistory->id)
                    ->delete();
            }
        }
    }
}