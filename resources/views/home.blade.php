<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>STEP</title>


  <!-- Font Awesome -->
  <link href="https://use.fontawesome.com/releases/v5.11.0/css/all.css" rel="stylesheet">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
  <!-- <link rel="stylesheet" href="{{ asset('css/app.css') }}"> -->
  <link rel="stylesheet" href="{{ mix('css/app.css') }}">

</head>
<body>
  <div id="app">
    <App></App>
  </div>
  <!-- <script src="{{ asset('js/app.js') }}"></script> -->
  <script src="{{ mix('js/app.js') }}" defer></script>
</body>
</html>
