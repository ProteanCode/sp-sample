<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>
    <style>
        .error {
            color: red;
            display: block;
            padding-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 18px;
            text-align: left;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
        }

        th {
            background-color: #f4f4f4;
            color: #333;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>

    <div>
        <label style="display: block; padding-bottom: 50px;">
            API TOKEN:
            <input id="token" name="token" type="text" style="min-width: 400px; text-align: right;" value="{{ request()->query('token', config('app.running_token')) }}">
        </label>

        <a style="border: 1px solid black; padding: 5px;" href="{{ route('first-page') }}">Pierwsza strona</a>
        <form id="nextPageForm" action="{{ route('second-page') }}" method="GET" style="display: inline;">
            <input type="hidden" name="token" value="">
            <button type="submit" style="background: transparent; border: 1px solid black; cursor: pointer; padding: 5px; top: -2px; position: relative;">Druga strona</button>
        </form>
        @yield('pages')
    </div>
    @yield('content')

    <script type="text/javascript">
        // or $(document).ready(function() { ... });
        document.addEventListener('readystatechange', function(e) {
            if(document.readyState === "complete") {
                document.querySelector('#nextPageForm input[name="token"]').value = document.getElementById('token').value;

                const activeHref = document.querySelector('a[href="{{ request()->url() }}"]');
                if(activeHref) {
                    activeHref.style.backgroundColor = 'black';
                    activeHref.style.color = 'white';
                }
            }
        });

        // or $('#token').on('keyUp', function() { ... });
        document.getElementById('token').addEventListener('keyup', function(e) {
            document.querySelector('#nextPageForm input[name="token"]').value = e.currentTarget.value;
        });
    </script>
</body>
</html>
