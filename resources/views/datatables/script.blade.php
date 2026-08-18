<script>
    loadDataTable();

    function loadDataTable(filters = [])
    {
        window.dataTable = $('#{{ $dataTable['table'] }}').DataTable({
            processing: true,
            scrollX: true,
            serverSide: true,
            bDestroy: true,
            order: [[ 0, "desc" ]],
            ajax: {
                url: '{!! route($dataTable["url"]) !!}',
                data: { filters }
            },
            columns: [
                    @if(!empty($dataTable['columns']))
                        @foreach ($dataTable['columns'] as $columnName => $value)
                            { data: '{{ $value }}', name: '{{ $value }}', orderable: {{ (strpos($columnName, '@no-sort@')) ? 'false' : 'true' }} },
                        @endforeach
                    @endif
                    ]
        });
    }

    $(document).ready(function () {
        $('.filter-form-submit').on('click', function (e) {
            window.dataTable.draw();
        });

        $('#{{ $dataTable['table'] }}').on('preXhr.dt', function ( e, settings, data ) {
            $('.filter-form :input').each(function () {
                data[$(this).prop('name')] = $(this).val();
            });
        });
    });
</script>
