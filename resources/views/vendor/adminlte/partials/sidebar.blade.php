<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar Menu -->
        <ul class="sidebar-menu tree" data-widget="tree">


           

            @if(Auth::guard('controlpanel')->user()->role_id == 2)

             <li class="">
                <a href="{{ url('/controlpanel') }}">
                    <i class="fa fa-dashboard"></i>
                    <span>Dashboard</span>
                </a>
            </li>

             <li class="">
                <a href="{{ url('controlpanel/tickets/adminindex') }}">
                    <i class="fa fa-ticket" aria-hidden="true"></i>
                    <span>Manage Ticket</span>
                </a>
                </li>
            @endif

            <!-- support agent -->
             @if(Auth::guard('controlpanel')->user()->role_id == 3)
             <li class="">
                <a href="{{ url('controlpanel/agents/index') }}">
                    <i class="fa fa-ticket" aria-hidden="true"></i>
                    <span>Manage Ticket</span>
                </a>
                </li>
            @endif

            @if(Auth::guard('controlpanel')->user()->role_id == 1)

             <li class="">
                <a href="{{ url('/controlpanel') }}">
                    <i class="fa fa-dashboard"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="">
                <a href="{{ url('controlpanel/user-list') }}">
                    <i class="fa fa-user"></i>
                    <span>User</span>
                </a>
            </li>


            <li class="">
                <a href="{{ url('controlpanel/review-list') }}">
                    <i class="fa fa-check-circle"></i>
                    <span>Offer/receive Profile Review</span>
                </a>
            </li>

            <li class="">
                <a href="{{ url('controlpanel/import-users') }}">
                    <i class="fa fa-users"></i>
                    <span>Import users</span>
                </a>
            </li>
            <li class="">
                <a href="{{ url('controlpanel/bid-list') }}">
                    <i class="fa fa-gavel" aria-hidden="true"></i>
                    <span>Bids</span>
                </a>
            </li>
            <li class="">
                <a href="{{ url('controlpanel/opportunity-list') }}">
                    <i class="fa fa-tasks" aria-hidden="true"></i>
                    <span>Opportunities</span>
                </a>
            </li>
            <li class="">
                <a href="{{ url('controlpanel/codes') }}">
                    <i class="fa fa-gift" aria-hidden="true"></i>
                    <span>Vouch Codes</span>
                </a>
            </li>
            <li class="">
                <a href="{{ url('controlpanel/redeem-request') }}">
                    <i class="fa fa-gift" aria-hidden="true"></i>
                    <span>Redeem Request</span>
                </a>
            </li>

            <li class="treeview">
                <a href="#">
                <i class="fa fa-dashboard"></i> <span>Marketplace</span>
                <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
                </span>
                </a>
                <ul class="treeview-menu">
                <li class="">
                <a href="{{ url('controlpanel/partners') }}">
                    <i class="fa fa-handshake-o" aria-hidden="true"></i>
                    <span>Partners</span>
                </a>
                </li>
            <li class="">
                <a href="{{ url('controlpanel/offers') }}">
                    <i class="fa fa-gift" aria-hidden="true"></i>
                    <span>Offers</span>
                </a>
            </li>

            <li class="">
                <a href="{{ url('controlpanel/offers-opt') }}">
                    <i class="fa fa-gift" aria-hidden="true"></i>
                    <span>Offers Opt</span>
                </a>
            </li>

        
            <li class="">
                <a href="{{ url('controlpanel/partnersbank') }}">
                    <i class="fa fa-list" aria-hidden="true"></i>
                    <span>Partner Bank Details</span>
                </a>
            </li>

                </ul>
                </li>


            <li class="">
                <a href="{{ url('controlpanel/partner-review-list') }}">
                    <i class="fa fa-check"></i>
                    <span>Partner Profile Review</span>
                </a>
            </li>


             <li class="">
                <a href="{{ url('controlpanel/tickets/adminindex') }}">
                    <i class="fa fa-ticket" aria-hidden="true"></i>
                    <span>Manage Ticket</span>
                </a>
                </li>

            <li class="">
                <a href="{{ url('controlpanel/events') }}">
                    <i class="fa fa-calendar" aria-hidden="true"></i>
                    <span>Events</span>
                </a>
            </li>

            <li class="">
                <a href="{{ url('controlpanel/collaborates') }}">
                    <i class="fa fa-handshake-o" aria-hidden="true"></i>
                    <span>Collaborates</span>
                </a>
            </li>

            <li class="treeview">
                <a href="#">
                <i class="fa fa-money"></i> <span>Payments</span>
                <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
                </span>
                </a>
                <ul class="treeview-menu">

                <li class="">
                <a href="{{ url('controlpanel/all-beneficiary') }}">
                    <i class="fa fa-users" aria-hidden="true"></i>
                    <span>Beneficiary List</span>
                </a>
            </li>
                <li class="">
                <a href="{{route('controlpanel.all-beneficiary-payment', 0)}}">
                    <i class="fa fa-money" aria-hidden="true"></i>
                    <span>Payments List</span>
                </a>
                </li>

                <li class="">
                <a href="{{route('controlpanel.refund')}}">
                    <i class="fa fa-undo" aria-hidden="true"></i>
                    <span>Refund List</span>
                </a>
                </li>

                
                </ul>
            </li>
            <li class="">
                <a href="{{ url('controlpanel/faqs') }}">
                    <i class="fa fa-comments" aria-hidden="true"></i>
                    <span>ChatBot</span>
                </a>
                </li>

            <li class="treeview">
                <a href="#">
                    <i class="fa fa-gear" aria-hidden="true"></i>
                    <span>Setting</span>
                      <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
                </span>
                </a>

                <ul class="treeview-menu">
                <li class="">
                <a href="{{ url('controlpanel/setting-button') }}">
                    <i class="fa fa-recycle" aria-hidden="true"></i>
                    <span>Buttons Setting</span>
                </a>
                </li>

                 <li class="">
                <a href="{{ url('controlpanel/configuration') }}">
                    <i class="fa fa-gear" aria-hidden="true"></i>
                    <span>Configuration</span>
                </a>
                </li>

                  <li class="">
                <a href="{{ url('controlpanel/faqs') }}">
                    <i class="fa fa-gear" aria-hidden="true"></i>
                    <span>FAQ's</span>
                </a>
                </li>
                </ul>
            </li>


         
            @endif

        </ul>
        <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>
