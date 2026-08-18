<div class="row">
    <div class="col-md-12">
        <form action="" class="filter-form">
            <div class="form-group row">
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="row">
                        <label for="role" class="col-md-4 col-form-label">User</label>
                        <div class="col-md-8">
                            <select name="user" id="user" class="form-control" data-live-search="true">
                                <option value="Any">Any</option>
                                @foreach($users as $user)
                                    <option value="{{$user->name}}">@if(!empty($user->emp_no)) {{$user->emp_no}} : @endif {{$user->name}} </option>
                                @endforeach
                            </select>
                            <span class="invalid-feedback user-error" role="alert"></span>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="row">
                        <label for="permission" class="col-md-4 col-form-label">Action</label>
                        <div class="col-md-8">
                            <select name="permission" id="permission" class="form-control" data-live-search="true">
                                <option value="Any">Any</option>
                                @foreach ($permissions as $permission)
                                    <option value="{{$permission->name}}" >{{ucfirst($permission->name)}}</option>
                                @endforeach
                            </select>
                            <span class="invalid-feedback permission-error" role="alert"></span>
                        </div>
                    </div>
                </div>

            </div>
            <div class="form-group row">
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="row">
                        <label for="start_date" class="col-md-4 col-form-label">Start Date</label>
                        <div class="col-md-8">
                            <input type="text" name="start_date" id="start_date" class="form-control datepicker" data-date-format="yyyy-mm-dd" value="{{date("Y-m-d", strtotime("-30 day"))}}">
                            <span class="invalid-feedback form.start_date-error" role="alert"></span>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="row">
                        <label for="end_date" class="col-md-4 col-form-label">End Date</label>
                        <div class="col-md-8">
                            <input type="text" name="end_date" id="end_date" class="form-control datepicker" data-date-format="yyyy-mm-dd" value="{{date('Y-m-d')}}">
                            <span class="invalid-feedback form.end_date-error" role="alert"></span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <button type="button" class="filter-form-submit btn btn-primary"><i class="fa fa-filter"></i> Search</button>
                </div>
            </div>
        </form>
    </div>
</div>
