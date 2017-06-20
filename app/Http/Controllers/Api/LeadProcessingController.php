<?php
namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Api\LeadProcessTable;
//use Leadprocessinghelper;
class LeadProcessingController extends Controller
{
    public function index() {
       return 'susanta Kumar Das';
    } 

    public function create() {
        //
    } 
    public function store(Request $request) {
        print_r($request);
    }

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