<html>
<head>
    <title>{{ $title }}</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>

<body class="bg-white">
    <h3 class="text-center">{{ $heading }}</h3>
    @if(isset($filter_text) || !empty($filter_text))
        <h5>Search Text : {{ $filter_text }}</h5>
    @endif
    <hr>
    <div>
        <table class="table table-bordered">
            <thead>
            <tr>
                @foreach($headers as $header)
                    <th scope="col">{{ $header }}</th>
                @endforeach
            </tr>
            </thead>
            <tbody>
                @foreach ($data as $value)
                    <tr>
                        @foreach ($columns as $c)
                            <td>{{ $value->$c }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
