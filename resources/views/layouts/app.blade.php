<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reddit Clone</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>
                    Reditt Clone
                    <small class="pull-right">
                        <a href="{{ route('create_post_path') }}">Create Post</a>
                    </small>
                </h1>
            </div>
        </div>
        <hr>
        
        @include('layouts._errors')

        @include('layouts._messages')

        @yield('content')

    </div>
</body>
</html>