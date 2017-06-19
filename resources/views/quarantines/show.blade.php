@extends('layouts.app')
@section('title','Process Quarantine File')
@section('content')
    @if(Auth::user()->role_id==1 || Auth::user()->role_id==2)
        <div class="xs">
            <h3>Process Quarantine File</h3>
            <div class="well1 white">
                <div id="massage"></div>
                <div class="existing-data">
                    <div class="form-group">                    
                    </div>
                    <div class="form-group">
                        <label class="control-label">Supplier Name: {{$quarantine[0]->sname}}</label>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Client Name: {{$quarantine[0]->cname}}</label>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Campaign Name: {{$quarantine[0]->cmpname}}</label>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Reason: {{$quarantine[0]->reason}}</label>
                    </div>
                    <div class="form-group">
                        <label class="control-label">File: @if($quarantine[0]->filename !='')<a href="{{ url('/quarantines/download/'.$quarantine[0]->id) }}">Download</a>@endif</label>
                    </div>
                    <div class="form-group">
                        <label class="control-label">New File</label>
                        <div id="fileuploader">Upload</div>
                    </div>
                    <script type="text/javascript">
                        jQuery(document).ready(function() {
                            var newId = '{{$quarantine[0]->id}}';
                            jQuery('#fileuploader').uploadFile({
                                url:"{{ action('QuarantinesController@store') }}",
                                multiple:true,
                                autoSubmit:true,
                                fileName:'myfile',
                                allowedTypes : 'csv',
                                formData: {'_token':'{{ csrf_token() }}','id':newId},
                                maxFileCount:1,
                                onSuccess : function(files,data,xhr) {
                                    if(data.status==1){
                                        jQuery('#filename').val(data.fname);
                                    }
                                },
                            });
                        });
                    </script>
                </div>
                <form class="form-floating ng-pristine ng-invalid ng-invalid-required ng-valid-email ng-valid-url ng-valid-pattern form-horizontal" role="form" name="quarantineUpdate" method="post" action="{{ action('QuarantinesController@update',$quarantine[0]->id) }}">
                    {{ csrf_field() }}
                    {{method_field('PUT')}}
                    <fieldset>
                        <div class="form-group">
                            <input type="hidden" id="filename" name="filename" value="">
                        </div>
                        <div class="form-group">
                            <div class="checkboxb checkbox-primary">
                                <input type="checkbox" id="active" name="active" value="No">
                                <label for="active">
                                    Ignore Errors
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Reprocess</button>
                        </div>
                        <div class="form-group" style="margin-top:5%;">
                            <button type="button" class="btn btn-default" id="deletefile">Delete File</button>
                        </div>
                    </fieldset>
                </form>
            </div>
            <script type="text/javascript">
                jQuery(function() {                
                    jQuery("form[name='quarantineUpdate']").validate({
                        rules: {
                            filename: "required"
                        },
                        messages: {
                            filename: "please upload csv file"
                        },
                        onfocusout: function(element) {
                            this.element(element);
                        },
                        submitHandler: function(form) {
                            jQuery.ajax({
                                url: form.action,
                                type: form.method,
                                data: $(form).serialize(),
                                success: function(response) {
                                    if(response==1){
                                        jQuery('#massage').text('Quarantine Updated Successfully').addClass('success').removeClass('errors');
                                        $('html').animate({ scrollTop: 0 }, 300);
                                        setTimeout(function(){
                                            window.location.href = "{{ action('QuarantinesController@index') }}";
                                        }, 3000);
                                    } else {
                                        jQuery('#massage').text('Something Wrong! Quarantine Updated').addClass('errors').removeClass('success');
                                    }
                                }            
                            });
                        }
                    });
                });        
                jQuery(document).on('click','#active',function(){
                    if(jQuery(this).is(':checked')==true){
                        jQuery(this).attr('checked','checked');
                        jQuery(this).val('Yes');
                    } else {
                        jQuery(this).removeAttr('checked');
                        jQuery(this).val('No');
                    }
                });
                jQuery(document).on('click','#deletefile',function(){
                    var id = "{{$quarantine[0]->id}}";
                    if (confirm('Are you sure you want to delete this?')) {
                        jQuery.ajax({
                            type: 'POST',
                            url: "{{ url('/quarantines') }}/"+id,
                            data: { _token:'{{ csrf_token() }}', _method:'DELETE'} , 
                            success: function(result){
                                if(result==1){
                                    jQuery('#massage').text('Quarantine Deleted Successfully').addClass('success').removeClass('errors');
                                    $('html').animate({ scrollTop: 0 }, 300);
                                    setTimeout(function(){
                                        window.location.href = "{{ action('QuarantinesController@index') }}";
                                    }, 3000);
                                } else {
                                    jQuery('#massage').text('Quarantine Delete Fail').addClass('errors').removeClass('success');
                                    $('html').animate({ scrollTop: 0 }, 300);
                                }
                            }
                        });
                    }
                });
            </script>
        </div>
    @else
        <div class="xs">
            <div  class="alert alert-danger" >You are not allowed to access this page</div>
        </div>
    @endif
@endsection