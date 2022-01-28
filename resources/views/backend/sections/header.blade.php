<?php
$backend_user = session()->get('backend_user');
?>

<header class="main-header">
  <!-- Logo -->
  <a href="{{route('admin.home')}}" class="logo">
    <!-- mini logo for sidebar mini 50x50 pixels -->
    <span class="logo-mini"> Demo</span>
    <!-- logo for regular state and mobile devices -->
    <span class="logo-lg"><b>Demo Build Chatbot</b><span>
  </a>
  <!-- Header Navbar: style can be found in header.less -->
  <nav class="navbar navbar-static-top">
    <!-- Sidebar toggle button-->
    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
      <span class="sr-only">Toggle navigation</span>
    </a>

    <div class="navbar-custom-menu">
      <ul class="nav navbar-nav">
        <!-- Messages: style can be found in dropdown.less-->
        <li class="hidden-lg hidden-md hidden-sm">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <span>USER NAME</span>
          </a>
        <li>

        <li class="hidden-lg hidden-md hidden-sm">
          <a href="#" >Sign out</a>
        </li>
        <!-- User Account: style can be found in dropdown.less -->
        <li class="dropdown user user-menu">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <span class="hidden-xs">{{$backend_user->firstname . ' ' . $backend_user->lastname}}</span>
          </a>
          <ul class="dropdown-menu">
            <li class="user-header"> </li>
            <li class="user-body"> </li>
            <li class="user-footer">
              <div class="pull-right">
                <form action="{{route('admin.post.logout')}}" method="POST">
                    @csrf
                    <input type="submit" class="btn btn-default btn-flat" value="Sign out">
                </form>
              </div>
            </li>
          </ul>
        </li>
        <!-- Control Sidebar Toggle Button -->
        <li>
          <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
        </li>
      </ul>
    </div>
  </nav>
</header>
