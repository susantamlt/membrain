<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\clearRapportPhoneHistory;
use DB;
class RapportPhone extends Command
{
    protected $signature = 'rapportphone:clear';
    protected $description = 'clear Rapport Phone History';
    protected $clearRapportPhoneHistory;

    public function __construct(clearRapportPhoneHistory $clearRapportPhoneHistory) {
        parent::__construct();
        $this->clearRapportPhoneHistory = $clearRapportPhoneHistory;
        set_time_limit(0);
    }

    /* when we run signature like 'rapportphone:clear' then always hit handle function we fetch data and cler data from database as per requirement document */
    public function handle() {
        $date = date('Y-m-d');
        $validationdate = date('Y-m-d', strtotime($date .' -180 day'));
        $rapportphonehistorys = DB::table('rapport_phone_history as rapportphonehistory')->select('rapportphonehistory.id','rapportphonehistory.status_code')
            ->where('rapportphonehistory.validation_date','<',$validationdate)
            ->get(); //fatch all data from database

        if(!empty($rapportphonehistorys)){
            foreach ($rapportphonehistorys as $key => $_rapportphonehistory) {  
                DB::table('rapport_phone_history')
                    ->where('id','=', $_rapportphonehistory->id)
                    ->delete();
            }
        }
    }
}