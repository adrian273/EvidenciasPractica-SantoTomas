@php
if (isset($patient)) {
    $task = 'Patient Chart ';
} else {
    $task = 'Add';
    $detail = [];
}
@endphp
@extends('layouts.userMaster')
@section('title', 'Patient List')
@section('content')
    <style>
        .pills-tab-bgcolor .nav-link.active {
            background-color: #0050db !important;
        }

    </style>
    <div class="row wrapper page-heading">
        <div class="col-lg-10">
            <ol class="breadcrumb">
                <!--<li class="breadcrumb-item">
                                                                                <a href="{{ url('user/dashboard') }}">Dashboard</a>
                                                                            </li>-->
                <li class="breadcrumb-item">
                    <a href="{{ url('user/agency/patients' . '?filter=' . @$_GET['filter']) }}">Patient List</a>
                </li>
                <li class="breadcrumb-item active">
                    {{ $task }} Patient
                </li>
            </ol>
            <h2>
                {{ $task }} {{-- {{ ucfirst(@$detail->first_name) . ' ' . ucfirst(@$detail->last_name) }} --}}
            </h2>
        </div>
        <div class="col-lg-2">

        </div>
    </div>
    @include('home::agency.patient.components.box_short_details')
    <!-- App container -->
    <div id="app">
        <div class="tabs-container">
            <!-- primary tabs -->
            <ul class="nav nav-tabs new_tab" role="tablist" id="primary-tab">
                <li>
                    <a class="nav-link {{ @$_GET['table'] == 'pt_chart' ? 'active' : '' }} {{!isset($_GET['table'])?'active':''}}"
                        data-toggle="tab" href="#tab-1">
                        Pt Chart <i class="fa fa-user-circle-o" aria-hidden="true"></i>
                    </a>
                </li>
                <li>
                    <a class="nav-link {{ @$_GET['table'] == 'emergency_contact' ? 'active' : '' }}"  page="emergency_contact" Data-toggle="tab"
                        href="#tab-2">
                        Admissions <i class="fa fa-handshake-o" aria-hidden="true"></i>
                    </a>
                </li>
                <li>
                    <a class="nav-link {{ @$_GET['tab'] == 'team' ? 'active' : '' }}" Data-toggle="tab" href="#tab-3">
                        Team <i class="fa fa-users" aria-hidden="true"></i>
                    </a>
                </li>
                <li>
                    <a class="nav-link {{ @$_GET['tab'] == 'calendar' ? 'active' : '' }}" Data-toggle="tab" href="#tab-4">
                        Calendar <i class="fa fa-calendar" aria-hidden="true"></i>
                    </a>
                </li>
                <li>
                    <a class="nav-link {{ @$_GET['tab'] == 'visit_task' ? 'active' : '' }}" Data-toggle="tab"
                        href="#tab-5">
                        Visit Tasks <i class="fa fa-tasks" aria-hidden="true"></i>
                    </a>
                </li>
                <li>
                    {{-- <a class="nav-link {{@$_GET['tab']=='quick_report'?'active':''}}" 
                Data-toggle="tab" href="#tab-6">Quick Reports</a> --}}
                    <input type="hidden" value="" id="q-report-value">
                    <a class="dropdown-toggle nav-link {{ @$_GET['tab'] == 'quick_report' ? 'active' : '' }}"
                        id="quick-report-link" data-toggle="dropdown" href="#">
                        <span id="quick-report-text">
                            Quick Reports
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li>
                            <a class="dropdown-item" href="#tab-7" onclick="qReportSelected(this)">Doc.
                                Orders</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#tab-8" onclick="qReportSelected(this)">Communication Notes</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#tab-9" onclick="qReportSelected(this)">Care
                                Plans</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#tab-10" onclick="qReportSelected(this)">60 day
                                Summary</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#tab-11" onclick="qReportSelected(this)">Vital
                                Signs</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#tab-12" onclick="qReportSelected(this)">Medications</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#tab-13" onclick="qReportSelected(this)">Allergies</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#tab-14" onclick="qReportSelected(this)">DME</a>
                        </li>
                    </ul>
                </li>
            </ul> <!-- / primary tabs header end -->
            <div class="tab-content">
                {{-- PT Chart Panel --}}
                <div role="tabpanel" id="tab-1"
                    class="tab-pane {{ @$_GET['tab'] == 'pt_chart' ? 'active' : '' }} {{ !isset($_GET['table']) ? 'active' : '' }}">
                    <div class="panel-body">
                        <!-- Sub Main PT Chart-->
                        <ul class="nav nav-pills mb-3" id="tab-ptchart" role="tablist">
                            @php
                                $sub_main = [['id' => 'detail'], ['id' => 'notes'], ['id' => 'documents']];
                            @endphp
                            @foreach ($sub_main as $item)
                                <li class="nav-item">
                                    <a class="nav-link {{ $item['id'] == 'detail' ? 'active' : '' }}"
                                        id="pills-{{ $item['id'] }}-tab" data-toggle="pill"
                                        href="#pills-{{ $item['id'] }}" role="tab"
                                        aria-controls="pills-{{ $item['id'] }}" aria-selected="false">
                                        {{ 
                                            $item['id'] == 'emergency_contact' ? 
                                            'Emergency Contact' : Str::title( $item['id'] ) 
                                        }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        <!-- end sub main header -->
                        <div class="tab-content" id="content-ptchart">
                            <div class="tab-pane fade show active" id="pills-detail" role="tabpanel"
                                aria-labelledby="pills-home-tab">
                                @include('home::agency.patient.components.patient_form_edit', [
                                'title' => 'Demographics'
                                ]);
                            </div>
                            {{--<div class="tab-pane fade" id="pills-insurance" role="tabpanel"
                                aria-labelledby="pills-insurance-tab">
                                this is sample of insure
                            </div>
                            <div class="tab-pane fade" id="pills-emergency_contact" role="tabpanel"
                                aria-labelledby="pills-emergency_contact-tab">
                                <input type="hidden" name="action" value="" class="action_type">
                		        <input type="hidden" name="page" value="" class="current_page">
                                <emergency-contact></emergency-contact>
                            </div> {{--}}
                            <div class="tab-pane fade" id="pills-notes" role="tabpanel" aria-labelledby="pills-notes-tab">
                                this is sample of notes
                            </div>
                            <div class="tab-pane fade" id="pills-documents" role="tabpanel"
                                aria-labelledby="pills-documents-tab">
                                this is sample of documents
                            </div>
                        </div> <!-- end sub main content Detail -->
                    </div> <!-- end panel body -->
                </div> <!-- end Pt Chart content -->
                <div role="tabpanel" id="tab-2" class="tab-pane {{ @$_GET['tab'] == 'admission' ? 'active' : '' }}">
                    <div class="panel-body">
                        Admision
                    </div>
                </div>
                <div role="tabpanel" id="tab-3" class="tab-pane {{ @$_GET['tab'] == 'team' ? 'active' : '' }}">
                    <div class="panel-body">
                        Team
                    </div>
                </div>
                <div role="tabpanel" id="tab-4" class="tab-pane {{ @$_GET['tab'] == 'calendar' ? 'active' : '' }}">
                    <div class="panel-body">
                        calendar
                    </div>
                </div>
                <div role="tabpanel" id="tab-5" class="tab-pane {{ @$_GET['tab'] == 'visit_task' ? 'active' : '' }}">
                    <div class="panel-body">
                        visit task
                    </div>
                </div>
                <div role="tabpanel" id="tab-6" class="tab-pane {{ @$_GET['tab'] == 'quick_report' ? 'active' : '' }}">
                    <div class="panel-body">
                        quick_report
                    </div>
                </div>
                <div role="tabpanel" id="tab-7" class="tab-pane {{ @$_GET['tab'] == 'doc_order' ? 'active' : '' }}">
                    <div class="panel-body">
                        doc order
                    </div>
                </div>
                <div role="tabpanel" id="tab-8"
                    class="tab-pane {{ @$_GET['tab'] == 'communication_note' ? 'active' : '' }}">
                    <div class="panel-body">
                        communication note
                    </div>
                </div>
                <div role="tabpanel" id="tab-9" class="tab-pane {{ @$_GET['tab'] == 'care_plan' ? 'active' : '' }}">
                    <div class="panel-body">
                        care plan
                    </div>
                </div>
                <div role="tabpanel" id="tab-10" class="tab-pane {{ @$_GET['tab'] == 'summary' ? 'active' : '' }}">
                    <div class="panel-body">
                        60 day Summary
                    </div>
                </div>
                <div role="tabpanel" id="tab-11" class="tab-pane {{ @$_GET['tab'] == 'vital_sign' ? 'active' : '' }}">
                    <div class="panel-body">
                        Vital Sing
                    </div>
                </div>
                <div role="tabpanel" id="tab-12" class="tab-pane {{ @$_GET['tab'] == 'medication' ? 'active' : '' }}">
                    <div class="panel-body">
                        MEdication
                    </div>
                </div>
                <div role="tabpanel" id="tab-13" class="tab-pane {{ @$_GET['tab'] == 'allergy' ? 'active' : '' }}">
                    <div class="panel-body">
                        allergies
                    </div>
                </div>
                <div role="tabpanel" id="tab-14" class="tab-pane {{ @$_GET['tab'] == 'dme' ? 'active' : '' }}">
                    <div class="panel-body">
                        DME
                    </div>
                </div>
            </div> {{-- / end Primary Tab Content --}}
        </div> <!-- end tabs container -->

    </div> {{-- End app container --}}
    @include('common.map')
@endsection

@section('scripts')
    <script type="application/javascript">
        function moreOrLess() {
            var val_moreorless = $("#val_moreorless");
            if (val_moreorless.val() == "1") {
                $("#text_moreorless").html('More...');
                val_moreorless.val("2");
            } else {
                $("#text_moreorless").html('Less...');
                val_moreorless.val("1");
            }
        }

        /**
         *@memberOf Quick Reports
         *@param that: it's [this object]
         **/
        function qReportSelected(that) {
            //alert(that.text)
            $("#quick-report-link").addClass('active');
            $("#quick-report-text").html(`<span class="badge badge-success">
                                            ${that.text}
                                        </span>`);
            var link = that.href;
            link = link.split('#');
            $('#q-report-value').val(link[1]);
        }

        $('#primary-tab a').on('click', function(e) {
            e.preventDefault();
            $(this).tab('show');
        });

        $('#quick-report-link').on('click', function(e) {
            e.preventDefault();
            var q_report_value = $('#q-report-value').val();
            $(`#primary-tab a[href="#${q_report_value}"]`).tab('show');
            $("#" + q_report_value).trigger('click');
        });
    </script>
    @yield('patient_form__script')
    <script>
        $('.action_span').hide();
    
    </script>
    <script>
        $(document).ready(function(){
            $(document).on('click','.action', function(){
                action = $(this).attr('type');
                current_page='';
                $(".nav-link").each(function(){
                    if ($(this).hasClass('active')) {
                        current_page=$(this).attr('page');
                    }
                });

                $('.action_type').val(action);
                $('.current_page').val(current_page);
            });
        });
    </script>
    <script>
    
        current_page='';
        $(".nav-link").each(function(){
            if ($(this).hasClass('active')) {
                current_page=$(this).attr('page');
            }
        });

        $(document).on('click','#checkall', function(){
             current_page='';
             $(".nav-link").each(function(){
                 if ($(this).hasClass('active')) {
                     current_page=$(this).attr('page');
                 }
             });
    
             if ($(this).is(':checked')) {
    
                $('.checkinput_'+current_page).prop('checked',true);
             }else{
                $('.checkinput_'+current_page).prop('checked',false);
             }
             len = $('.checkinput_'+current_page+':checked').length;
         // alert(len)
             if (len==0) {
                 $('.'+current_page).hide();
             }else{
                 $('.'+current_page).show();
             }
        });
        $(document).on('click','.checkinput', function(){
            current_page='';
             $(".nav-link").each(function(){
                 if ($(this).hasClass('active')) {
                     current_page=$(this).attr('page');
                 }
             });
             len = $('.checkinput_'+current_page+':checked').length;
             total = $('.checkinput_'+current_page).length;
             if (len==0) {
                 $('.'+current_page).hide();
             }else{
                 $('.'+current_page).show();
             }
        });    

        $(".nav-link").each(function(){
            current_page=$(this).attr('page');
            $('.'+current_page).hide();
        });
   
        $(document).on('click','.rows', function(){
            URL = $(this).closest('tr').find('.link').val();
            viewer = $(this).closest('tr').find('.viewer').val();
            if (URL!=null) {
                current_page='';
                $(".nav-link").each(function(){
                    if ($(this).hasClass('active')) {
                        current_page=$(this).attr('page');
                    }
                });
                page = '?page='+current_page+'&viewer='+viewer;
                window.location.href = URL+page;
            }
        });
    </script>
  
    <script>
        href='{{url('user/internal-email/send')}}';
        $('#add_email').attr('href',href);
    </script>
    <script type="text/javascript">
        $(document).on('click','.all-edit-tabs',function(event){
            event.preventDefault();
            var URL = $(this).attr('href');
            filterValue = $('.inbox_filter ').val();
            // sentValue = $('.sent_filter ').val();
            // trash_filter
            // archive_filter
    
            URL = URL + "?filter="+filterValue;
            window.location.href = URL;
        });
    </script>
    <script>
        $(document).ready(function(){
            add_url = '{{url('user/therapy-referral/add')}}';
            $('#add_patient').attr('href',add_url);
        });
    </script>

@endsection
