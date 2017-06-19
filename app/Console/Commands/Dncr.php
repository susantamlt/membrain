<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\clearDncrHistory;
use DB;
class Dncr extends Command
{
    protected $signature = 'dncr:clear';
    protected $description = 'Clear DNCR History';
    protected $clearDncrHistory;

    public function __construct(clearDncrHistory $clearDncrHistory) {
        parent::__construct();
        $this->clearDncrHistory = $clearDncrHistory;
        set_time_limit(0);
    }

    /* when we run signature like 'dncr:clear' then always hit handle function we fetch data and cler data from database as per requirement document */
    public function handle() {
        $date = date('Y-m-d');
        $validationdate = date('Y-m-d', strtotime($date .' -28 day'));
        $dncrhistorys = DB::table('dncr_history as dncrhistory')->select('dncrhistory.id','dncrhistory.on_dncr')
            ->where('dncrhistory.on_dncr','=','0')
            ->where('dncrhistory.validation_date','<',$validationdate)
            ->get(); //fatch all data from database

        if(!empty($dncrhistorys)){
            foreach ($dncrhistorys as $key => $_dncrhistory) {  
                DB::table('dncr_history')
                    ->where('id','=', $_dncrhistory->id)
                    ->delete();
            }
        }
    }
}