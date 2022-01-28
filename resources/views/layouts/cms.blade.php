<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title')</title>

  @include('backend.sections.top-script')

  @yield('css')
</head>
<body class="hold-transition skin-blue sidebar-mini">
  <div class="wrapper">
    @include('backend.sections.header')

    @include('backend.sections.nav-left')

    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          {{$page_title}}
        </h1>
      </section>
      <section class="content">
        <div class="row">
          @yield('content')
        </div>
      </section>
    
    </div>
    

    @include('backend.sections.footer')

    @include('backend.sections.nav-right')
  </div>

  @include('backend.sections.bottom-script')
</body>
</html>
