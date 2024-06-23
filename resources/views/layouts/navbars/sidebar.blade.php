<div class="sidebar" data-color="orange">
<div class="logo" style="text-align: center;">
    <a href="http://www.creative-tim.com" class="simple-text logo-normal">
        {{ __('SugarBloom Bakery') }}
    </a>
</div>
    <div class="sidebar-wrapper" id="sidebar-wrapper">
        <ul class="nav">
            @if(Auth::user()->roles === 'cashier')
            <li class="@if ($activePage == 'home') active @endif">
                <a href="{{ route('home') }}">
                    <i class="now-ui-icons design_app"></i>
                    <p>{{ __('Dashboard') }}</p>
                </a>
            </li>

            <li class="@if ($activePage == 'profile') active @endif">
                <a href="{{ route('profile.edit') }}">
                    <i class="now-ui-icons users_single-02"></i>
                    <p> {{ __("View Profile") }} </p>
                </a>
            </li>

            <li class="@if ($activePage == 'takeorders') active @endif">
                 <a href="{{ route('page.index', 'takeorders') }}">
                    <i class="now-ui-icons ui-1_bell-53"></i>
                    <p>{{ __("Take Orders") }}</p>
                </a>
            </li>
            @endif

            @if(Auth::user()->roles === 'manager')
            <li class="@if ($activePage == 'home') active @endif">
                <a href="{{ route('home') }}">
                    <i class="now-ui-icons design_app"></i>
                    <p>{{ __('Dashboard') }}</p>
                </a>
            </li>

            <li class="@if ($activePage == 'register') active @endif">
            <a href="{{ route('register') }}">
            <i class="fas fa-user-plus"></i>
                    <p> {{ __("Register") }} </p>
                </a>
            </li>

            <li class = " @if ($activePage == 'table') active @endif">
                <a href="{{ route('page.index','table') }}">
                    <i class="now-ui-icons design_bullet-list-67"></i>
                    <p>{{ __('Cashier List') }}</p>
                </a>
            </li> 
            <li class="@if ($activePage == 'profile') active @endif">
                <a href="{{ route('profile.edit') }}">
                    <i class="now-ui-icons users_single-02"></i>
                    <p> {{ __("View Profile") }} </p>
                </a>
            </li> 
            <li class="@if ($activePage == 'transactions') active @endif">
    <a href="{{ route('pages.transaction') }}">
        <i class="now-ui-icons business_money-coins"></i>
        <p>{{ __("View Transactions") }}</p>
    </a>
</li>

            <li class="@if ($activePage == 'addcategory') active @endif">
            <a href="{{ route('pages.addcategory') }}">
                    <i class="fas fa-cookie-bite"></i>
                    <p> {{ __("Food Category") }} </p>
                </a>
            </li>
            <li class="@if ($activePage == 'additem') active @endif">
            <a href="{{ route('pages.additem') }}">
            <i class="fas fa-utensils"></i>
                    <p> {{ __("Food Menu") }} </p>
                </a>
            </li>
            @endif
        </ul>
    </div>
</div>
