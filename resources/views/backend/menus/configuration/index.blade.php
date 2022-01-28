@php
  $activeNavigation = collect(
    explode('.', request()->route()->getName())
  )->contains('configuration');
@endphp

<li class="@if($activeNavigation) active @endif">
  <a href="{{ route('admin.home') }}">
    <i class="fa fa-home"></i>
    <span>Dashboard</span>
  </a>
</li>
<li class="@if($activeNavigation) active @endif">
  <a href="{{ route('admin.catalog.listing') }}">
    <i class="fa fa-cubes"></i>
    <span>Catalog</span>
  </a>
</li>
<li class="@if($activeNavigation) active @endif">
  <a href="{{ route('admin.order.listing') }}">
    <i class="fa fa-taxi"></i>
    <span>Order</span>
  </a>
</li>
<li class="treeview" style="height: auto;">
  <a href="#"><i class="fa fa-comments"></i> Conversations
    <span class="pull-right-container">
      <i class="fa fa-angle-left pull-right"></i>
    </span>
  </a>
  <ul class="treeview-menu" style="display: none;">
    <li class="treeview">
      <a href="#"><i class="fa fa-facebook-square"></i> Facebook Messenger
        <span class="pull-right-container">
          <i class="fa fa-angle-left pull-right"></i>
        </span>
      </a>
      <ul class="treeview-menu">
        <li class="@if($activeNavigation) active @endif"><a href="{{route('admin.facebook.flow.index')}}"><i class="fa fa-forward"></i> Flow</a></li>
        <li class="@if($activeNavigation) active @endif"><a href="{{route('admin.messenger.messages')}}"><i class="fa fa-commenting"></i> Messages</a></li>
      </ul>
    </li>
  </ul>
</li>