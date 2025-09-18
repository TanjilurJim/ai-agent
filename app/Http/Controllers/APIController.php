<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;
class APIController extends Controller
{
    public function api(){
        $subscription = Subscription::where('user_id', auth()->user()->id)->first();
        return view('dashboard.api', compact('subscription'));
    }


    public function update_subscriber(Request $request, $id){
        $subscriber = Subscription::find($id);
        $subscriber->status = $request->status;
        $subscriber->save();
        return redirect()->back()->with('success', 'Update Successfull');
    }
}
