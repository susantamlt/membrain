
{{-- -------------------------------------------------
           Statistics View
            Input Parameters :
            @Suppliers
            @Source
            @Clients
            @Campaign
            @Start date
            @End date
            Author: Latitude Global Partners
     -------------------------------------------------
--}} 

@extends('layouts.app')
@section('title','Add/Statistics')
@section('content')
    <div class="xs">
        <h3>Statistics</h3>
        <div class="well1 white">
            <div class="control-group error" style="color:red">
                <ul id="summary"></ul>
            </div>
            <div id="statsError" class="text-danger" style="display:none;"></div>
            <form id="statistics" method="post" action="{{ action('StatisticsController@store') }}">
                {{ csrf_field() }}
                @if(Auth::user()->role_id==1 || Auth::user()->role_id==2 || Auth::user()->role_id==4 || Auth::user()->role_id==5)
                    <div class="form-group">
                        <div class="checkboxb checkbox-primary">
                            <input type="checkbox" id="suppliers" value="suppliers" name="suppliers">
                            <label for="suppliers">Suppliers</label>
                        </div>
                    </div>
                @endif
                <div class="form-group">
                    <div class="checkboxb checkbox-primary">
                        <input type="checkbox" id="source" value="source" name="source">
                        <label for="source">Source</label>
                    </div>
                </div>
                @if(Auth::user()->role_id==1 || Auth::user()->role_id==2 || Auth::user()->role_id==3 || Auth::user()->role_id==5)
                    <div class="form-group">
                        <div class="checkboxb checkbox-primary">
                            <input type="checkbox" id="clients" value="clients" name="clients">
                            <label for="clients">Clients</label>
                        </div>
                    </div>
                @endif
                <div class="form-group">
                    <div class="checkboxb checkbox-primary">
                        <input type="checkbox" id="campaign" value="campaign" name="campaign">
                        <label for="campaign">Campaign</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="start_date" class="control-label">From:</label>
                    <input type="text" name="startdate" id="startdate" class="form-control2" />
                    <i class="fa fa-calendar startdate-icon"></i>
                    <span id="startDateError" class="text-danger" style="display:none;"></span>
                </div>
                <div class="form-group">
                    <label for="enddate" class="control-label">To:</label>
                    <span>&nbsp;&nbsp;&nbsp;&nbsp;</span><input type="text" id="enddate" name="enddate" class="form-control2" />
                    <i class="fa fa-calendar enddate-icon"></i>
                    <span id="endDateError" class="text-danger" style="display:none;"></span>
                </div>
                <div class="form-group">
                    <button type="button" id="process" class="btn btn-primary submit">Run Report</button>
                </div>               
            </form>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function(){
            /*** START & END DATEPICKER SETUP ***/            
            $('.startdate-icon').click(function(event){
                event.preventDefault();
                $('#startdate').focus();
            });
            $('.enddate-icon').click(function(event){
                event.preventDefault();
                $('#enddate').focus();
            });
            $("#enddate").datepicker({
                format:'dd/mm/yyyy',
                autoclose: true
            });
            var d = new Date();
            var currDate = d.getDate();
            var currMonth = d.getMonth();
            var currYear = d.getFullYear();
            var dateStr = currYear + "-" + currMonth + "-" + currDate;
            $("#startdate").datepicker({                
                todayBtn:  1,
                autoclose: true,
                format: 'dd/mm/yyyy',
            }).on('changeDate', function (selected) {
                var minDate = new Date(selected.date.valueOf());
                $('#enddate').datepicker('setStartDate', minDate);                
            });
            $('#startdate').datepicker('setDate', new Date(currYear, currMonth, 01));
            $("#enddate").datepicker({
                autoclose: true
            }).on('changeDate', function (selected) {
                var maxDate = new Date(selected.date.valueOf());
                $('#startdate').datepicker('setEndDate', maxDate);
                $('#enddate').datepicker('hide');
            });
            $('#enddate').datepicker('setDate', new Date(currYear, currMonth, currDate ));
            /*** END OF START & END DATEPICKER SETUP ***/
            /*** Javascript validation after user clicks on the 'Run Report' button ***/
            $('#process').click(function(){
                var suppliers = $('#suppliers').val();
                var source = $('#source').val();
                var clients = $('#clients').val();
                var campaign = $('#campaign').val();
                var startDate = $('#startdate').val();
                var endDate = $('#enddate').val();
                if(startDate == ''){
                    $('#startDateError').text('Please select start date').show();
                } else {
                    $('#startDateError').hide();
                }
                if(endDate == ''){
                    $('#endDateError').text('Please select end date').show();
                } else {
                    $('#endDateError').hide();
                }
                <?php if(Auth::user()->role_id==1 || Auth::user()->role_id==2 || Auth::user()->role_id==5) { ?>
                if($('#suppliers').prop("checked") == false && $('#source').prop("checked") == false && $('#clients').prop("checked") == false && $('#campaign').prop("checked") == false){
                    $('#statsError').text('Please select atleast one option').show();
                }
                else{
                    $('#statsError').hide();
                }
                <?php } if(Auth::user()->role_id==3) { ?>
                if($('#source').prop("checked") == false && $('#clients').prop("checked") == false && $('#campaign').prop("checked") == false){
                    $('#statsError').text('Please select atleast one option').show();
                }else {
                    $('#statsError').hide();
                }
                <?php } if(Auth::user()->role_id==4){ 
                ?>
                if($('#suppliers').prop("checked") == false && $('#source').prop("checked") == false && $('#campaign').prop("checked") == false){
                    $('#statsError').text('Please select atleast one option').show();
                } else {
                    $('#statsError').hide();
                    //$('#statistics').submit();           
                }
                <?php } ?>
                if ( $('#startDateError').css('display') == 'none' && $('#endDateError').css('display') == 'none' && $('#statsError').css('display') == 'none' ){
                    $('#statistics').submit();
                }
            }); 
            /*** END OF Javascript validation after user clicks on the 'Run Report' button ***/                   
        });
    </script>
@endsection