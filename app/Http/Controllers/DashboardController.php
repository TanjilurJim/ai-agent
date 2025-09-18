<?php

namespace App\Http\Controllers;

use App\Models\Bot;
use App\Models\BotContent;
use App\Models\Page;
use App\Models\Subscription;
use App\Models\Train;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index(){
        return view('dashboard.index');
    }


    public function make(){
        $bot = Bot::where('user_id', auth()->id())->first();
        return  view('dashboard.makebot', compact('bot'));
    }

  
    
    public function store(Request $request){
        $request->validate([
            'botName' => 'required|string|max:255',
            'botDescription' => 'required|string',
            'botToken' => 'required|string|max:255',
            'botColor' => 'required',
        ]);
        
        $bot = Bot::where('user_id', auth()->user()->id)->first()? : new Bot();
         $bot->botName = $request->botName;
         $bot->botDescription = $request->botDescription;
         $bot->botToken = $request->botToken;
         $bot->botColor = $request->botColor;
         $bot->user_id = auth()->user()->id;
    if ($request->hasFile('botAvatar')) {
        $file = $request->file('botAvatar');
        $filename = time() . '_' . $file->getClientOriginalName();
        $filePath = 'uploads';
        $file->move(public_path($filePath), $filename);
        $bot->botAvatar = $filePath . '/' . $filename;
    }
    $bot->save();
    return redirect()->route('dashboard.index')->with('success', 'Bot created successfully.');
}



public function train(){
    $content = BotContent::firstOrNew(['user_id' => auth()->id()]);
    return view(('dashboard.train') , compact('content'));
}
public function page(){
    return view('dashboard.page');
}


public function page_store(Request $request){
    $request->validate([
        'content' => 'required',
        'title' => 'required',
    ]);

    $page = new Page();
    $page->content = $request->input('content'); 
    $page->title = $request->input('title'); 
    $page->user_id = auth()->id();
    $page->save();
    return redirect('/dashboard/train-bot/page-list')->with('success', 'Page create Successfull');
}


public function page_list(){
    $user_id = auth()->id();
    $pages = Page::where('user_id', $user_id)->get();
    return view('dashboard.page_list', compact('pages')); 
}


public function page_list_edit($id){
    $page = Page::where('id', $id)->where('user_id', auth()->id())->first();

    return view('dashboard.page_edit', compact('page'));
}
public function page_list_edit_store(Request $request, $id){
    $page = Page::where('id', $id)->where('user_id', auth()->id())->first();
    $page->content = $request->input('content'); 
    $page->title = $request->input('title'); 
    $page->user_id = auth()->id();
    $page->save();
    return redirect('/dashboard/train-bot/page-list')->with('success', 'Page update Successfull');
}


public function destroyPage($id){
    $page = Page::findOrFail($id);
    $page->delete();

    return response()->json(['success' => true, 'message' => 'Page deleted successfully']);
}





public function train_store(Request $request)
{
    $request->validate([
        'content' => 'required',
    ]);

    $user_id = auth()->user()->id;
    $content = BotContent::firstOrNew(['user_id' => $user_id]);
    $content->user_id = $user_id;
    $content->content = $request->content;
    $content->save();
    return redirect()->back()->with('success', 'Content update successful');
}



public function train_edit($id){
    $train = Train::find($id);
    return view('dashboard.train_edit', compact('train'));
}


public function train_update(Request $request, $id){
    $request->validate([
        'question' => 'required|string|max:255',
        'answer' => 'required|string|max:255',
        'description' => 'required|string',
    ]);


    $train =  Train::find($id);
    $train->question = $request->input('question');
    $train->answer = $request->input('answer');
    $train->description = $request->input('description');
    $train->user_id = auth()->user()->id;
    $train->save();
    return redirect()->back()->with('success', 'Create Successfull');
}




public function user()
{
    $user = User::all();
    return view('dashboard.user', compact('user'));
}
public function destroy($id)
{
    $user = User::find($id);
    $user->delete();
   return back()->with('success', 'User Delete successfull');
}


public function subscriber(){
    $subscribers = Subscription::with('user')->get();
    return view('dashboard.subscriber', compact('subscribers'));
}

public function subscriber_store(Request $request){
    $subscription = new Subscription();
    $subscription->status = "Pending";
    $subscription->api_key = Str::random(20);
    $subscription->token =  10;
    $subscription->user_id = auth()->user()->id;
    $subscription->save();
    return redirect()->back()->with('success', 'Subscribed successfully.');
}

public function subscriber_destroy($id){
    $subscriber = Subscription::find($id);
    if (!$subscriber) {
        return redirect()->back()->with('error', 'Subscriber not found.');
    }

    $subscriber->delete();
    return redirect()->back()->with('success', 'Subscriber deleted successfully.');
}






}