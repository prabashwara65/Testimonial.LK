{{ Form::open(array('url' => route('vendor.action-log.update', $log['id']), 'class'=>'text-form', 'method' => 'put', 'onsubmit' => 'return false;')) }}
<div class="modal-header">
    <h4 class="modal-title c-grey-900 ">Action Details : ID #{{$log->id}}</h4>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>
<div class="modal-body">
    <div class='col-lg-10 offset-lg-1'>
        <div class="form-group row">
            <label for="name" class="col-sm-2 col-form-label">Log ID</label>
            <div class="col-sm-4">
                <input class="form-control" readonly value="{{$log->id}}" />
            </div>

            <label for="name" class="col-sm-2 col-form-label">User</label>
            <div class="col-sm-4">
                <input class="form-control" readonly value="{{$log->user}}" />
            </div>
        </div>

        <div class="form-group row">
            <label for="name" class="col-sm-2 col-form-label">IP Address</label>
            <div class="col-sm-4">
                <input class="form-control" readonly value="{{$log->ip}}" />
            </div>

            <label for="name" class="col-sm-2 col-form-label">Date</label>
            <div class="col-sm-4">
                <input class="form-control" readonly value="{{$log->created_at}}" />
            </div>
        </div>

        <div class="form-group row">
            <label for="name" class="col-sm-2 col-form-label">Action</label>
            <div class="col-sm-10">
                <input class="form-control" readonly value="{{$log->action}}" />
            </div>
        </div>


        <div class="form-group row">
            <label for="name" class="col-sm-2 col-form-label">Subject</label>
            <div class="col-sm-10">
                <input class="form-control" readonly value="{{$log->subject}}" />
            </div>
        </div>
        <hr>
        <div class="form-group row">
            <label for="name" class="col-sm-2 col-form-label">Parameters</label>
            <pre class="col-sm-10 json-beautify">
                {{$log->parameters}}
            </pre>
        </div>
        <hr>
        <div class="form-group row">
            <label for="name" class="col-sm-2 col-form-label">Response</label>
            <pre class="col-sm-10 json-beautify">
                {{$log->response}}
            </pre>
        </div>
        <hr>
        <div class="form-group row">
            <label for="name" class="col-sm-2 col-form-label">Comments</label>
            <div class="col-sm-10">
                <textarea class="form-control" id="comments" name="comments">{{$log->comments}}</textarea>
            </div>
        </div>

    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default cancel-btn-color" data-dismiss="modal">Close</button>
    {{ Form::submit('Save Comment', array('class' => 'text-form-submit btn btn-primary')) }}
</div>
{{ Form::close() }}