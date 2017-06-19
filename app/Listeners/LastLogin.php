<?php
namespace App\Listeners;
use App\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Login;
use DB;
class LastLogin {
    public function handle(Login $event) {
        DB::table('protal_user') ->where('id', $event->user->id) ->update(['last_login' => Carbon::now()]);
    }
}