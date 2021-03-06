<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title')</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}" />
         <!-- Bootstrap Core CSS -->
        <link href="{{ asset('css/bootstrap.min.css') }}" rel='stylesheet' type='text/css' />
        <!-- Custom CSS -->
        <link href="{{ asset('css/style.css') }}" rel='stylesheet' type='text/css' />
         <link href="{{ asset('css/build.css') }}" rel='stylesheet' type='text/css' />
        <!-- Graph CSS -->
        <link href="{{ asset('css/lines.css') }}" rel='stylesheet' type='text/css' />
        <script src="https://use.fontawesome.com/2db0ca0da2.js"></script>
        <link href="{{ asset('css/jquery.dataTables.min.css') }}" rel="stylesheet" /> 
        <link href="{{ asset('css/jquery.dataTables_themeroller.css') }}" rel="stylesheet" /> 
        <!-- jQuery -->
        <script src="{{ asset('js/jquery.min.js') }}"></script>         
        <!-- Nav CSS -->
        <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
        <link href="{{ asset('css/uploadfile.css') }}" rel="stylesheet">
        <!-- Metis Menu Plugin JavaScript -->
        <script src="{{ asset('js/metisMenu.min.js') }}"></script>
        <script src="{{ asset('js/custom.js') }}"></script>
        <script src="{{ asset('js/jquery.validate.js') }}"></script>
        <script src="{{ asset('js/additional-methods.js') }}"></script>
        <!-- Graph JavaScript -->
        <script src="{{ asset('js/d3.v3.js') }}"></script>
        <script src="{{ asset('js/rickshaw.js') }}"></script>
        <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
        <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
        <link href="{{ asset('css/bootstrap-datepicker3.css') }}" rel="stylesheet" />
    </head>
    <body>
        <div id="wrapper">
            <nav class="top1 navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="{{ url('/dashboard') }}"><img src="{{ asset('images/logo.png') }}" alt="Membrain" /></a>
                </div>
                <ul class="nav navbar-nav navbar-right">
                     <div class="col-sm-6"> {{{ isset(Auth::user()->name) ? Auth::user()->name : Auth::user()->email }}}</div>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle avatar" data-toggle="dropdown"><i class="fa fa-user nav_icon" aria-hidden="true"></i></a>
                        <ul class="dropdown-menu">
                            <li class="m_2">
                                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa fa-lock"></i> Logout</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
                <div class="navbar-default sidebar" role="navigation">                 
                    <div class="sidebar-nav navbar-collapse">
                        <ul class="nav" id="side-menu">
                            <li><a href="{{ url('/statistics/create') }}"><i class="fa fa-dashboard fa-fw nav_icon"></i>Statistics</a></li>
                            @if(Auth::user()->role_id==1 || Auth::user()->role_id==2 )
                                <li>
                                    <a href="javascript:void(0);"><i class="fa fa-envelope nav_icon"></i>Suppliers<span class="fa arrow"></span></a>
                                    <ul class="nav nav-second-level">
                                         <li><a href="{{ url('/suppliers') }}"><i class="fa fa-cogs nav_icon" aria-hidden="true"></i>Manage Suppliers</a></li>
                                        <li><a href="{{ url('/suppliers/create/') }}"><i class="fa fa-plus nav_icon"></i>Create Supplier</a></li>
                                       
                                    </ul>
                                </li>
                            @endif
                            @if(Auth::user()->role_id==1 || Auth::user()->role_id==2 )
                                <li>
                                    <a href="javascript:void(0);"><i class="fa fa-handshake-o nav_icon"></i>Clients<span class="fa arrow"></span></a>
                                    <ul class="nav nav-second-level">
                                           <li><a href="{{ url('/clients') }}"><i class="fa fa-cogs nav_icon" aria-hidden="true"></i>Manage Clients</a></li>
                                        <li><a href="{{ url('/clients/create/') }}"><i class="fa fa-plus nav_icon"></i>Create Client</a></li>
                                     
                                    </ul>
                                </li>
                            @endif
                            @if(Auth::user()->role_id==1)
                                <li>
                                    <a href="javascript:void(0);"><i class="fa fa-user-o fa-fw nav_icon"></i>Portal Users<span class="fa arrow"></span></a>
                                    <ul class="nav nav-second-level">
                                        <li><a href="{{ url('/portaluser') }}"><i class="fa fa-cogs nav_icon" aria-hidden="true"></i>Manage Users</a></li>
                                        <li><a href="{{ url('/portaluser/create/') }}"><i class="fa fa-plus nav_icon"></i>Create User</a></li>
                                    </ul>
                                </li>
                            @endif
                            @if(Auth::user()->role_id==1 || Auth::user()->role_id==2 )
                                <li>
                                    <a href="{{ url('/campaign') }}"><i class="fa fa-bullhorn nav_icon"></i>Campaigns<span class="fa arrow"></span></a>
                                    <ul class="nav nav-second-level">
                                        <li><a href="{{ url('/campaign') }}"><i class="fa fa-cogs nav_icon" aria-hidden="true"></i>Manage Campaigns</a></li>
                                        <li><a href="{{ url('/campaign/create/') }}"><i class="fa fa-plus nav_icon"></i>Create Campaign</a></li>
                                        
                                    </ul>
                                </li>
                            @endif
                            @if(Auth::user()->role_id==1 || Auth::user()->role_id==2 )
                                <li>
                                    <a href="javascript:void(0);"><i class="fa fa-list-alt nav_icon"></i>List Management<span class="fa arrow"></span></a>
                                    <ul class="nav nav-second-level">
                                        <li><a href="{{ url('/donotcalls') }}"><i class="fa fa-plus nav_icon"></i>Do Not Call</a></li>
                                        <li><a href="{{ url('/salaciouswords') }}"><i class="fa fa-plus nav_icon"></i>Salacious Words</a></li>
                                        <li><a href="{{ url('/emailblacklists') }}"><i class="fa fa-plus nav_icon"></i>Email Blacklist</a></li>
                                        <li><a href="{{ url('/domainblacklists') }}"><i class="fa fa-plus nav_icon"></i>Domain Blacklist</a></li>
                                        <li><a href="{{ url('/nameblacklists') }}"><i class="fa fa-plus nav_icon"></i>Name Blacklist</a></li>
                                    </ul>
                                </li>
                            @endif
                            @if(Auth::user()->role_id==1 || Auth::user()->role_id==2 )
                                <li>
                                    <a href="{{ url('/alerts') }}"><i class="fa fa-exclamation-triangle nav_icon"></i>Alerts <div id="alerts_count">{{ Session::get('count_alert') }}</div></a>
                                </li>
                            @endif
                            @if(Auth::user()->role_id==1 || Auth::user()->role_id==2 )
                                <li>
                                    <a href="javascript:void(0);"><i class="fa fa-sitemap fa-fw nav_icon"></i>Quarantine<span class="fa arrow"></span></a>
                                    <ul class="nav nav-second-level">
                                        <li><a href="{{ url('/quarantines') }}">Quarantine Management</a></li>
                                    </ul>
                                </li>
                            @endif                            
                            @if(Auth::user()->role_id==1 || Auth::user()->role_id==4 || Auth::user()->role_id==5  || Auth::user()->role_id==2)
                                <li>
                                    <a href="{{ url('/processleadcsv') }}"><i class="fa fa-file nav_icon"></i>Process Lead CSV</a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </nav>
            <div id="page-wrapper">
                <div class="graphs">
                    @yield('content')
                    <div class="copy">
                        <p>Copyright &copy; 2017 Membrain. All Rights Reserved</p>
                    </div>
                </div>
            </div>
        </div>
        <script src="{{ asset('js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('js/jquery.uploadfile.js') }}"></script>
        <script src="{{ asset('js/bootstrap-datepicker.js') }}"></script>
        <script src="{{ asset('js/bootstrap-datepicker.min.js') }}"></script>
        <script type="text/javascript">
            jQuery(document).ready(function(){
                jQuery.ajax({ 
                    url: "{{url('/alerts/getAlertsCount')}}",
                    type:"post",
                    data:{'_token':'{{ csrf_token() }}'}, 
                    success: function(response){
                        jQuery('#alerts_count').text(response);
                    }
                });
            });
        </script>
    </body>
</html>