<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class SessionController extends Controller
{
    static public function accessCompanyName(Request $request) {
        if($request->session()->has('companyName'))
           return $request->session()->get('companyName');
        else
           return 'No data in the session';
     }

    static public function accessSessionData(Request $request) {
        if($request->session()->has('companyName'))
           echo $request->session()->get('companyName');
        else
           echo 'No data in the session';
     }
     static public function storeSessionData(Request $request, string $data) {
        $request->session()->put('companyName', $data);
     }
     public function deleteSessionData(Request $request) {
        $request->session()->forget('my_name');
        echo "Data has been removed from session.";
     }
}
