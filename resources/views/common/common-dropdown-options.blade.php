@if(isset($with_any_option) && !empty($with_any_option))
    <option value="any">Any</option>
@endif
@if(count($options))
    <option hidden></option>
    @foreach($options as $item)
        <option value="{{$item->id}}">{{$item->name}}</option>
    @endforeach
@endif
