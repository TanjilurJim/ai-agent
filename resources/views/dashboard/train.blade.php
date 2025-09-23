@extends('dashboard.layout')

@section('content')

<style>
    .notepad-style {
    background-color: #f5f5f5; 
    border: 1px solid #ccc;
    font-family: 'Courier New', Courier, monospace; 
    font-size: 14px;
    padding: 10px;
    width: 100%;
    resize: both;
    box-sizing: border-box;
}
</style>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                    <h4 class="page-title">Bot Configaration</h4>
                    <div class="d-flex gap-3">
                        <ol class="breadcrumb mb-0 d-flex align-items-center">
                            <li class=""><a class="btn btn-outline-primary " href="/dashboard/train-bot/page-list"><i style="margin-right: 5px" class="fa-solid fa-list"></i>See All Personalities</a></li>
                        </ol>
                        <ol class="breadcrumb mb-0 d-flex align-items-center">
                            <li class=""><a class="btn btn-outline-primary " href="/dashboard/train-bot/add-page"><i  style="margin-right: 5px" class="fa-solid fa-plus"></i>Add Personality</a></li>
                        </ol>
                    </div>                            
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
        <div class="">
            <div class="card">
                <div class="card-body">
                    <form action="" method="POST">
                        @csrf
                        <label class="my-3" for="content">Describe About Bot</label>
                        <textarea placeholder="You are a kind and helpful customer service member IP Telephone services Provider. If the user asks how to get IP Number, refer them to our website at https://rafusoft.com/dir/IP-Telephony-Free-Number-in-Bangladesh  , if the user asks how to recharge , refer them our website Portal & Recharge Link , if the user says how to register refer our registration link Registration Form Link https://forms.gle/j5GyJjmrtWoYbrtd7.
If the user asks anything about Recharge, Register IP Number (09647XXXXXX), Installation One Time Charge, Incoming (All), IP Phone to IP Phone, IP Phone to Cell Phone/GSM, IP Phone to BTCL, Concurrent Calls Outgoing, Concurrent Receiving Calls Incoming, Minimum Recharge Amount, Internet Connection, input in the following page suggest options:" name="content" id="content" class="form-control notepad-style" rows="10">{{$content->content}}</textarea>
                    <button class="btn btn-primary mt-3 px-3 shadow">Save</button>
                    </form>                    
                </div>
            </div>
        </div>
        </div>
</div>

@endsection


