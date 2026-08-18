<table class="table table-bordered" id="{{ $dataTable['table'] }}" cellspacing="0" width="100%">
    <thead>
    <tr>
        @if(!empty($dataTable['columns']))
            @foreach ($dataTable['columns'] as $columnName => $value)
                <th>{{ str_replace('@no-sort@', '', $columnName) }}</th>
            @endforeach
        @endif
    </tr>
    </thead>
</table>
