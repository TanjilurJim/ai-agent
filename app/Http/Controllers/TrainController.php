<?php

namespace App\Http\Controllers;

use App\Models\Train;
use Illuminate\Http\Request;

class TrainController extends Controller
{
    public function update(Request $request, $id)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'description' => 'required|string',
        ]);

        $item = Train::findOrFail($id);
        $item->question = $request->question;
        $item->answer = $request->answer;
        $item->description = $request->description;
        $item->save();

        return redirect()->back()->with('success', 'Item updated successfully.');
    }

    public function destroy($id)
    {
        $item = Train::findOrFail($id);
        $item->delete();
        return redirect()->back()->with('success', 'Item deleted successfully.');
    }



    public function subscriber(){
        return view('dashboard.subscriber');
    }

}
