<form method="POST"  class="card-body" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
        <label for="avatar" class="d-block">
           <p>Logo <small>62*62 Pixel Required</small></p>
            <img height="82px" id="avatar_preview" i width="82px" style="cursor: pointer;" class="border rounded" src="{{$widget->avatar ?? asset('assets/images/upload-placeholder.jpg')}}" alt="">
        </label>
        <input type="file" style="display: none;" name="avatar" id="avatar">
    </div>
    <div class="form-group mt-2">
        <label for="widgetName">Widget Name</label>
        <input type="text" value="{{$widget->widgetName ?? ''}}" name="widgetName" id="widgetName" class="form-control mt-1">
    </div>
    <div class="form-group mt-2">
        <label for="name">Bot Name</label>
        <input type="text" value="{{$widget->name ?? ''}}" name="name" id="name" class="form-control mt-1">
    </div>
    <div class="form-group mt-2">
        <label for="welcomeMessage">Welcome Message</label>
        <input type="text" value="{{$widget->welcomeMessage ?? ''}}"  name="welcomeMessage" id="welcomeMessage" class="form-control mt-1">
    </div>
    <div class="form-group mt-2">
        <label for="color">Color</label>
        <input class="form-control text-white mt-1" name="color" id="color" value="{{$widget->color ?? '#0A5'}}" data-huebee />
    </div>
    <button class="btn btn-primary mt-3">Save</button>
</form>


