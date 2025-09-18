@extends('dashboard.layout')



@section('content')

<style>
    .notepad-style {
    font-family: monospace;
    white-space: pre-wrap;
    line-height: 1.5;
    border: 1px solid #ccc;
    padding: 10px;
    outline: none;
    resize: none;
    width: 100%;
    background: #fffefe;
}

.notepad-style::placeholder {
    color: #aaa;
}

.notepad-container {
    display: flex;
    flex-direction: column;
    border: 1px solid #ccc;
}

.notepad-line {
    display: flex;
    align-items: center;
    border-bottom: 1px solid #ddd;
    padding: 5px;
    font-family: monospace;
    white-space: pre-wrap;
}

</style>
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                    <h4 class="page-title">Dashboard</h4>
                    <div class="">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item active"><a class="btn btn-sm btn-primary text-white" href="/dashboard/train-bot/page-list"><i  style="margin-right: 5px" class="fa-solid fa-list"></i> Page List</a></li>
                        </ol>
                    </div>                            
                </div>
            </div>
        </div>


        <div class="card">
            <form method="POST"  class="card-body">
                @csrf
                <div class="form-group">
                    <label class="my-2" for="title">Title</label>
                    <input type="text" name="title" placeholder="Ip Telephone page Deatails" id="title" class="form-control">
                </div>
                <div class="form-group">
                    <label class="my-2" for="content">Content</label>
                    <textarea 
                    placeholder="Register IP Number - 09647XXXXXX
Installation One Time Charge - Free
Recharge - Pay as you go
Incoming (All) - Free 
IP Phone to IP Phone - Free
IP Phone to Cell Phone/GSM - 0.37 paisa per minute with a 1-second pulse
IP Phone to BTCL - 0.37 paisa per minute with a 1-second pulse
Concurrent Calls Outgoing - 4 Channels
Concurrent Receiving Calls Incoming - 1 Channel
Minimum Recharge Amount - 20 Taka
Internet Connection - Any wifi or Robi Internet
google form link - https://forms.gle/j5GyJjmrtWoYbrtd7"
                     name="content" id="content" class="form-control notepad-style" rows="10"></textarea>
                </div>

                <button class="btn btn-primary my-2 px-3">Save</button>
            </form>
        </div>
</div>


<script>
    document.addEventListener("DOMContentLoaded", function () {
    const textarea = document.getElementById("content");

    textarea.addEventListener("keydown", function (event) {
        if (event.key === "Enter") {
            let start = textarea.selectionStart;
            let value = textarea.value;
            textarea.value = value.substring(0, start) + "\n- " + value.substring(start);
            textarea.selectionStart = textarea.selectionEnd = start + 3;
            event.preventDefault();
        }
    });
});
</script>

@endsection