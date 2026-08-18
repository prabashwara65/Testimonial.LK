{{-- Testimonial Button --}}
@if (Route::has("$route.testimonial") && !empty($count['testimonial']) && $count['testimonial'])
    {!! Form::open(['method' => 'GET', 'class'=> 'd-inline view-form', 'route' => ["$route.testimonial", 'id' => $id]]) !!}
    <button class="btn view-button btn-sm" type="submit" title="View testimonial"><i class="icon-eye"></i> : {{ $count['testimonial'] }}</button>
    {!! Form::close() !!}
@endif

{{-- Feedback Button --}}
@if (Route::has("$route.feedback") && !empty($count['feedback']) && $count['feedback'])
    {!! Form::open(['method' => 'GET', 'class'=> 'd-inline view-form', 'route' => ["$route.feedback", 'id' => $id]]) !!}
    <button class="btn view-button btn-sm" type="submit" title="View feedback"><i class="icon-eye"></i> : {{ $count['feedback'] }}</button>
    {!! Form::close() !!}
@endif

{{-- Reward Button --}}
@if (Route::has("$route.reward") && !empty($count['reward']) && $count['reward'])
    {!! Form::open(['method' => 'GET', 'class'=> 'd-inline view-form', 'route' => ["$route.reward", 'id' => $id]]) !!}
    <button class="btn view-button btn-sm" type="submit" title="View reward"><i class="icon-eye"></i> : {{ $count['reward'] }}</button>
    {!! Form::close() !!}
@endif

{{-- Reward Add Button --}}
@if (Route::has("$route.assignreward") && $count['assignreward'])
    {!! Form::open(['method' => 'GET', 'class'=> 'd-inline view-form', 'route' => ["$route.assignreward", 'id' => $id]]) !!}
    <button class="btn view-button btn-sm" type="submit" title="Assign reward"><i class="icon-trophy2"></i></button>
    {!! Form::close() !!}
@endif
