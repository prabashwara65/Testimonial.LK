<div class="d-flex align-items-center">
    {{-- View Button --}}
    @if (Route::has("$route.show") && !empty($permissions['view']) && $permissions['view'])
        {!! Form::open(['method' => 'GET', 'class'=> 'd-inline view-form', 'route' => ["$route.show", $id]]) !!}
        <button class="btn view-button btn-sm" type="submit" title="View record"><i class="icon-eye"></i></button>
        {!! Form::close() !!}
    @endif

    {{-- Edit Button --}}
    @if (Route::has("$route.edit") && !empty($permissions['edit']) && Auth::user()->hasPermissionTo($permissions['edit']))
        {!! Form::open(['class'=> 'd-inline edit-form', 'route' => ["$route.edit", $id]]) !!}
        <button class="btn edit-button btn-sm" type="submit" title="Edit record"><i class="icon-pencil7"></i></button>
        {!! Form::close() !!}
    @endif

    {{-- Delete Button --}}
    @if (Route::has("$route.destroy") && !empty($permissions['delete']) && Auth::user()->hasPermissionTo($permissions['delete']))
        {!! Form::open(['method' => 'DELETE', 'class'=> 'd-inline delete-form', 'route' => ["$route.destroy", $id]]) !!}
        <button class="btn delete-button btn-sm" type="submit" title="Delete record"><i class="icon-trash-alt"></i></button>
        {!! Form::close() !!}
    @endif
</div>
