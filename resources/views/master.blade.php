<!DOCTYPE html>
<?php 
    $setup = \App\Setup::latest()->first(); 

    if($setup != NULL){
        $title = $setup->name;
    }else{
        $title = '-';
    }
?>
<html lang="en" class="no-focus">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

        <title>{{ $title }}</title>

        <link rel="shortcut icon" href="<?php echo asset('public/assets/media/favicons/favicon.png') ?>">
        <link rel="icon" type="image/png" sizes="192x192" href="<?php echo asset('public/assets/media/favicons/favicon-192x192.png') ?>">
        <link rel="apple-touch-icon" sizes="180x180" href="<?php echo asset('public/assets/media/favicons/apple-touch-icon-180x180.png') ?>">

        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Muli:300,400,400i,600,700">
        <link rel="stylesheet" href="<?php echo asset('public/assets/js/plugins/datatables/dataTables.bootstrap4.css') ?>">
        <link rel="stylesheet" href="<?php echo asset('public/assets/js/plugins/select2/css/select2.min.css') ?>">
        <link rel="stylesheet" href="<?php echo asset('public/assets/js/plugins/flatpickr/flatpickr.min.css') ?>">
        <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
        <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/css/bootstrap-timepicker.css">
        <link rel="stylesheet" id="css-main" href="<?php echo asset('public/assets/css/codebase.min.css') ?>">
        <style type="text/css">
            .btn{
                border-radius: 0px;
            }
            .bg-earth {
                background-color: #3F9CE8!important;
            }
            .bg-primary-dark-op {
                background-color: #fad390!important;
            }
            .content-side-user{
                height: 150px !important;
            }
        </style>
        @toastr_css
    </head>
    <body onload="startTime()">
        <div id="page-container" class="sidebar-o enable-page-overlay side-scroll page-header-modern main-content-boxed">
            <nav id="sidebar">
                <div class="sidebar-content">
                    <div class="content-header content-header-fullrow px-15">
                        
                        <div class="content-header-section sidebar-mini-visible-b">
                            <span class="content-header-item font-w700 font-size-xl float-left animated fadeIn">
                                <span class="text-dual-primary-dark">{{ $title }}</span>
                            </span>
                        </div>
                        
                        <div class="content-header-section text-center align-parent sidebar-mini-hidden">
                            <button type="button" class="btn btn-circle btn-dual-secondary d-lg-none align-v-r" data-toggle="layout" data-action="sidebar_close">
                                <i class="fa fa-times text-danger"></i>
                            </button>
                            
                            <div class="content-header-item">
                                <a class="link-effect font-w700" href="#">
                                    <span class="font-size-xl text-dual-primary-dark">{{ $title }}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php 
                        $activeuser = \App\User::where('employee_id',Auth::user()->employee_id)->first();
                        if($activeuser->usertype == 1){
                            $name = 'ADMIN';
                        }else{
                            $name = $activeuser->employee->FullName;
                        }
                    ?>
                    <div class="content-side content-side-full content-side-user px-10 align-parent">
                        <div class="sidebar-mini-visible-b align-v animated fadeIn">
                            <img class="img-avatar img-avatar32" src="#" alt="">
                        </div>
                        <div class="sidebar-mini-hidden-b text-center">
                            <a class="img-link" href="#">
                                @if($activeuser->usertype == 1)
                                <img class="img-avatar" src="<?php echo asset('public/images/admin.png') ?>" alt="">
                                @else
                                    @if($activeuser->employee->img != NULL)
                                    <img class="img-avatar img-avatar32" src="data:image/jpeg;base64,{{ $activeuser->employee->img }}" style="height: 70px; width: 70px;">
                                    @else
                                    <img class="img-avatar img-avatar32" src="<?php echo asset('public/images/noimage.png') ?>" style="height: 70px; width: 70px;">
                                    @endif
                                @endif
                            </a>
                            <ul class="list-inline mt-10">
                                <li class="list-inline-item">
                                    <a class="link-effect text-dual-primary-dark font-size-xs font-w600 text-uppercase" href="#">
                                        {{ $name }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="content-side content-side-full">
                        <ul class="nav-main">
                            <li>
                                <a href="{{ action('DashboardController@index') }}"><i class="fa fa-home"></i><span class="sidebar-mini-hide"> Dashboard</a>
                            </li>
                            @if(Auth::user()->usertype == 1)
                            <li>
                                <a href="{{ action('EmployeeController@index') }}"><i class="fa fa-users"></i><span class="sidebar-mini-hide"> Employees</a>
                            </li>
                            @else
                            <li>
                                <a href="{{ action('EmployeeController@edit',Auth::user()->employee_id) }}"><i class="fa fa-user"></i><span class="sidebar-mini-hide"> Employee Profile</a>
                            </li>
                            @endif
                            <li>
                                <a href="{{ action('DashboardController@view_dtr',Auth::user()->id) }}"><i class="fa fa-clock-o"></i><span class="sidebar-mini-hide"> DTR</a>
                            </li>
                            <li>
                                <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="fa fa-wpforms"></i><span class="sidebar-mini-hide"> HR Forms</span></a>
                                <ul>
                                    <li>
                                        <a href="{{ action('EmployeeleaveController@index') }}" data-toggle="#" class="#"><span class="sidebar-mini-hide"> Leave Application</span></a>
                                    </li>
                                    <li>
                                        <a href="{{ action('EmployeeloanController@index') }}" data-toggle="#" class="#"><span class="sidebar-mini-hide"> Loan Application</span></a>
                                    </li>
                                    <li>
                                        <a href="{{ action('EmployeeoffenseController@index') }}" data-toggle="#" class="#"><span class="sidebar-mini-hide"> Offense Application</span></a>
                                    </li>
                                    <li>
                                        <a href="{{ action('EmployeeovertimeController@index') }}" data-toggle="#" class="#"><span class="sidebar-mini-hide"> Overtime Application</span></a>
                                    </li>
                                </ul>
                            </li>
                            @if(Auth::user()->usertype == 3)
                            <li>
                                <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="fa fa-cog"></i><span class="sidebar-mini-hide"> Payroll Utilities</span></a>
                                <ul>
                                    <li>
                                        <a href="{{ action('AdjustmentController@index') }}" data-toggle="#" class="#"><span class="sidebar-mini-hide"> Employee Adjustments</span></a>
                                    </li>
                                    <li>
                                        <a href="{{ action('EmployeeallowanceController@index') }}" data-toggle="#" class="#"><span class="sidebar-mini-hide"> Employee Allowances</span></a>
                                    </li>
                                    <li>
                                        <a href="{{ action('ScheduledeductionController@index') }}" data-toggle="#" class="#"><span class="sidebar-mini-hide"> Schedule Deduction</span></a>
                                    </li>
                                </ul>
                            </li>
                            @endif
                            @if(Auth::user()->usertype == 1)
                            <li>
                                <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="fa fa-cog"></i><span class="sidebar-mini-hide"> Utilities</span></a>
                                <ul>
                                    <!-- <li>
                                        <a href="{{ action('AllowanceController@index') }}" data-toggle="#" class="#"><span class="sidebar-mini-hide"> Allowance Setup</span></a>
                                    </li> -->
                                    <li>
                                        <a href="{{ action('BankController@index') }}" data-toggle="#" class="#"><span class="sidebar-mini-hide"> Bank Setup</span></a>
                                    </li>
                                    <li>
                                        <a href="{{ action('CampusController@index') }}" data-toggle="#" class="#"><span class="sidebar-mini-hide"> Campus Setup</span></a>
                                    </li>
                                    <!-- <li>
                                        <a href="{{ action('DeductionController@index') }}" data-toggle="#" class="#"><span class="sidebar-mini-hide"> Deduction Setup</span></a>
                                    </li> -->
                                    <li>
                                        <a href="{{ action('HolidayController@index') }}" data-toggle="#" class="#"><span class="sidebar-mini-hide"> Holiday Setup</span></a>
                                    </li>
                                    <li>
                                        <a href="{{ action('LeaveController@index') }}" data-toggle="#" class="#"><span class="sidebar-mini-hide"> Leave Setup</span></a>
                                    </li>
                                    <!-- <li>
                                        <a href="{{ action('LoanController@index') }}" data-toggle="#" class="#"><span class="sidebar-mini-hide"> Loan Setup</span></a>
                                    </li> 
                                    <li>
                                        <a href="{{ action('RequirementController@index') }}" data-toggle="#" class="#"><span class="sidebar-mini-hide"> Requirement Setup</span></a>
                                    </li>
                                    -->
                                    <li>
                                        <a href="{{ action('SanctionController@index') }}" data-toggle="#" class="#"><span class="sidebar-mini-hide"> Sanction Setup</span></a>
                                    </li>
                                    <li>
                                        <a href="{{ action('ViolationController@index') }}" data-toggle="#" class="#"><span class="sidebar-mini-hide"> Violation Setup</span></a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="{{ action('SetupController@index') }}"><i class="fa fa-cogs"></i><span class="sidebar-mini-hide"> System Setup</a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </nav>

            <header id="page-header">
                <div class="content-header">
                    <div class="content-header-section">
                        <button type="button" class="btn btn-circle btn-dual-secondary" data-toggle="layout" data-action="sidebar_toggle">
                            <i class="fa fa-navicon"></i>
                        </button>
                    </div>
                    <div class="content-header-section">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-rounded btn-dual-secondary" id="page-header-user-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-user d-sm-none"></i>
                                <span class="d-none d-sm-inline-block">
                                    {{ $name }}
                                </span>
                                <i class="fa fa-angle-down ml-5"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right min-width-200" aria-labelledby="page-header-user-dropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="si si-note mr-5"></i> Change Password
                                </a>
                                <?php 
                                    //$employee = \App\Employee::find(Auth::user()->employee_id);
                                ?>
                                <a class="dropdown-item" href="{{ route('gawas') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    <i class="si si-logout mr-5"></i> Sign Out
                                </a>
                                <form id="logout-form" action="{{ route('gawas') }}" method="GET" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            
            <main id="main-container">
                
                <div class="content">
                    @yield('content')
                    @yield('modal')
                </div>

            </main>
            <footer id="page-footer" class="opacity-0">
                <div class="content py-20 font-size-xs clearfix">
                    <div class="float-right">
                        Developed by: <a class="font-w600" href="#" target="_blank">MDS</a>
                    </div>
                </div>
            </footer>
        </div>

        <script src="<?php echo asset('public/assets/js/codebase.core.min.js') ?>"></script>
        <script src="<?php echo asset('public/assets/js/codebase.app.min.js') ?>"></script>
        <!-- Page JS Plugins -->
        <script src="<?php echo asset('public/assets/js/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
        <script src="<?php echo asset('public/assets/js/plugins/datatables/dataTables.bootstrap4.min.js') ?>"></script>
        <script src="<?php echo asset('public/assets/js/plugins/select2/js/select2.full.min.js') ?>"></script>
        <script src="<?php echo asset('public/assets/js/plugins/flatpickr/flatpickr.min.js') ?>"></script>
        <script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/js/bootstrap-timepicker.min.js" integrity="sha512-2xXe2z/uA+2SyT/sTSt9Uq4jDKsT0lV4evd3eoE/oxKih8DSAsOF6LUb+ncafMJPAimWAXdu9W+yMXGrCVOzQA==" crossorigin="anonymous"></script>
        
        <!-- Page JS Code -->
        <script src="<?php echo asset('public/assets/js/pages/be_tables_datatables.min.js') ?>"></script>
        <script src="<?php echo asset('public/assets/js/pages/be_forms_plugins.min.js') ?>"></script>
        <script>
            $(document).ready(function(){
                $('#table1').DataTable();
                $('#summernote').summernote({
                    tabsize: 2,
                    height: 300
                });
                $('.tp').timepicker({
                  showInputs: false
                })
            });
        </script>
        <script>
            function startTime() {
              var today = new Date();
              var h = today.getHours();
              var m = today.getMinutes();
              var s = today.getSeconds();
              m = checkTime(m);
              s = checkTime(s);
              document.getElementById('txt').innerHTML =
              h + ":" + m + ":" + s;
              var t = setTimeout(startTime, 500);
            }
            function checkTime(i) {
              if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
              return i;
            }
        </script>
        <script>jQuery(function(){ Codebase.helpers(['flatpickr', 'datepicker', 'colorpicker', 'maxlength', 'select2', 'masked-inputs', 'rangeslider', 'tags-inputs','summernote']); });</script>
        @yield('js')
        @toastr_js
        @toastr_render
        @stack('scripts')
    </body>
</html>