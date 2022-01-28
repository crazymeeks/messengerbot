<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cold BeerDelivers</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{url('app_assets/frontend/messengerbot/css/style.css')}}">
    <script defer src="https://friconix.com/cdn/friconix.js"> </script>
</head>

<body>

@yield('content')

<!-- jQuery 3 -->
<script src="{{url("/contrib/admin-lte/bower_components/jquery/dist/jquery.min.js")}}"></script>
@yield('script')
</body>

</html>