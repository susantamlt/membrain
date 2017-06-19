{{--
    Alerts View:
        List of all Alerts
    Author: Latitude Global Partners
--}} 

@extends('layouts.app')
@section('title','Alerts')
@section('content')
    @if(Auth::user()->role_id==1 || Auth::user()->role_id==2)
        <div class="xs">
            <h3>List of Alerts</h3>
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            <div id="delete_alert_div">
                <span class="delete_alert_ok" style="color:green;text-align: center"></span>
                <span class="delete_alert_error" style="color:red;text-align: center"></span>
            </div>
            <div class="bs-example4" data-example-id="contextual-table">
                <table class="table table-striped table-bordered dt-responsive nowrap" id="alert">
                    <thead>
                        <tr>
                            <th style="background:none"><input type="checkbox" class="alert_checkbox_all" name="alert_checkbox_all" value=""></th>
                            <th>Alert</th>
                            <th>Date</th>
                            <th>Ack'ed</th>
                        </tr>
                    </thead>
                    <tbody>                
                        @foreach ($alerts as $alert)                    
                            <tr class="active {{ $alert->id }}" >                                
                                <td><input type="checkbox" class="alert_checkbox" name="alert_checkbox" value="{{ $alert->id }}"></td>
                                <td class="clickable-row ackalert_id_{{ $alert->id }}" style="cursor: pointer;" data-toggle="modal" data-target="#alertModal" data-row_id="{{$alert->id}}" data-subject="{{$alert->subject}}" data-body="{{$alert->body}}" data-filename="{{$alert->filename}}" data-whoacknow="{{ $alert->login_usernme }}" data-supplierid="{{$alert->supplier_id}}" data-date="{{ ($alert->acknowledged_date != Null) ? date('dS M Y h:i:s a', strtotime($alert->acknowledged_date)) :'' }}" data-acknowledged="{{$alert->acknowledged}}"> {{ $alert->subject }} </td>
                                <td scope="row">{{ date('dS M Y h:i:s a', strtotime($alert->created)) }}</td>
                                <td scope="row" class='{{ $alert->id.'_acked' }}' style='color:blue'> @if($alert->acknowledged == 0) Y @endif </td>                        
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <button type="button" class="btn btn-danger" id="delete_alert_button" disabled="disabled">Delete Selected Alerts</button>
                <div id="alertModal" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Alert Details</h4>
                            </div>
                            <div class="modal-body">
                                <div id="ack_status" style="display:none">
                                    <span class="ack_ok" style="text-align:center;color:green"></span>
                                    <span class="ack_error" style="text-align:center;color:red"></span>
                                </div>
                                <input type="hidden" id="newid" class="id">
                                <b>Alert:</b><p id="modal_alert"></p>
                                <b>Details:</b><p id="details"></p>
                                <b>File:</b><div id="file"></div>
                                <b>Acknowledged By:</b><div id="whoAcknowledge"></div>
                                <b>Acknowledged Date:</b><div id="dateAcknowledge"></div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary ack_alert" id="ack_alert">Acknowledge Alert</button>
                            </div>
                        </div>
                    </div>
                </div>
                <script type="text/javascript">
                    $(document).ready(function(){
                        $('#alert').DataTable();
                        /* START: SHOWS EACH ALERT'S DETAILED VIEW IN POPUP MODAL */
                        $("#alert").on("click", ".clickable-row", function() {
                            var subject = $(this).data('subject');
                            var body = $(this).data('body');
                            var filename = $(this).data('filename');
                            var ext = filename.split('.').pop().toLowerCase();
                            var id = $(this).data('row_id');
                            var whoacknow = $(this).data('whoacknow');
                            var supplierid = $(this).data('supplierid');
                            var dateAcknowledge = $(this).data('date');
                            var acknowledged = $(this).data('acknowledged');                        
                            var urlbase = "{{ url('') }}";
                            $('#modal_alert').text('');
                            $('#details').text('');
                            $('#file').html('');
                            $('.id').val('');

                            $('#modal_alert').text(subject);
                            $('#details').text(body);
                            $('#newid').val(id);
                            $('#whoAcknowledge').text(whoacknow);
                            $('#dateAcknowledge').text(dateAcknowledge);

                            if(filename !='' && $.inArray(ext, ['txt']) != -1){
                                $('#file').html('<a href="'+urlbase+'/alerts/download/'+supplierid+'">Download</a>');
                            }

                            if(acknowledged == 0){
                                $('#ack_alert').attr('disabled','disabled');
                            } else {
                                $('#ack_alert').removeAttr('disabled');
                            }
                        });
                        /* END: SHOWS EACH ALERT'S DETAILED VIEW IN POPUP MODAL */
                        /* Script for Acknowledge Alert button */
                        $('#ack_alert').click(function(){
                            var id = $('#newid').val();  
                            $.ajax({
                                type:'POST',
                                url:"{{url('/alerts/ackalert')}}",
                                data:{'_token':'{{ csrf_token() }}','id':id},
                                success:function(response){
                                    if(response.status == 1) {
                                        $('.ack_ok').text('Acknowledge updated.');
                                        $("#ack_status").show();
                                        $('#alerts_count').text('('+response.count+')');
                                        $('.'+id+'_acked').text('Y');
                                        $('.ackalert_id_'+id).data('whoacknow',response.name);
                                        $('#whoAcknowledge').text(response.name);
                                        $('#dateAcknowledge').text(response.date);
                                        $('.ackalert_id_'+id).data('date',response.date);
                                        $('.ackalert_id_'+id).data('acknowledged',0);
                                        $('#ack_alert').attr('disabled','disabled');
                                    } else {
                                        $('.ack_error').text('Some problem occurred, please try again.');
                                        $("#ack_status").show();
                                    }
                                }
                            });    
                        });
                        /* END of Script for Acknowledge Alert button */
                        /* To Enable/Disable Delete button */
                        $('.alert_checkbox').change(function() {
                            if ($('.alert_checkbox:checked').length) {
                                $('#delete_alert_button').removeAttr('disabled');
                            } else {
                                $('#delete_alert_button').attr('disabled', 'disabled');
                            }
                        });
                        /* END of To Enable/Disable Delete button */
                        /* To Select/DeSelect all alerts checkboxes */
                        $('.alert_checkbox_all').change(function() {
                            if ($(this).is(':checked')) {
                                $('.alert_checkbox').prop('checked', true);
                                $('#delete_alert_button').removeAttr('disabled');
                            } else {
                                $('.alert_checkbox').prop('checked', false);
                                $('#delete_alert_button').attr('disabled', 'disabled');
                            }
                        });
                        /* END of To Select/DeSelect all alerts checkboxes */
                        /* "Delete Selected Alerts" button */
                        $('#delete_alert_button').click(function(){
                            var choice = confirm("Are you sure to delete this item?");
                            if(choice) {
                               var allVals = [];
                                $.each($("input[name='alert_checkbox']:checked"), function(){            
                                    allVals.push($(this).val());
                                });
                                $.ajax({
                                    type:'POST',
                                    url:"{{url('/alerts/destroy')}}",
                                    data: { _token:'{{ csrf_token() }}', _method:'DELETE', id:allVals},
                                    success:function(response){
                                        if(response == 1) {
                                            $('.delete_alert_ok').text('Alert(s) deleted.');
                                            $("#delete_alert_div").show();
                                            for (var key in allVals)  {
                                                $('.'+allVals[key]).remove();
                                            }
                                            $('#delete_alert_button').attr('disabled', 'disabled');
                                        } else {
                                            $('.delete_alert_error').text('Some problem occurred, please try again.');
                                            $("#delete_alert_div").show();    
                                        }
                                    }
                                });  
                            }
                        });
                        /* END of "Delete Selected Alerts" button */                       
                    });
                </script>                        
            </div>
        </div>
    @else
        <div class="xs">
            <div  class="alert alert-danger" >You are not allowed to access this page</div>
        </div>
    @endif
@endsection