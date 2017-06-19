{{--
    Input Parameters:
        @Name
        @Contact Email (Unique)
        @Contact Full Name
        @Contact Phone (Unique)
        @Error Allowance(Select Dropdown)
        @Error Country(Select Dropdown)
        @Error E-mail Suppression(CSV File)
        @Error Phone Suppression(CSV File)
        @Lead Expiry Days
        @Active (Chheckbox)
        Author: Latitude Global Partners
--}}

@extends('layouts.app')
@section('title','Create/Client')
@section('content')
    @if(Auth::user()->role_id==1 || Auth::user()->role_id==2)
        <div class="xs">
            <h3>Create Client</h3>
            <div class="well1 white">
                <div id="massage"></div>
                <div id="clientCreate" class="form-floating ng-pristine ng-invalid ng-invalid-required ng-valid-email ng-valid-url ng-valid-pattern form-horizontal">
                    {{ csrf_field() }}
                    <fieldset>
                        <div class="form-group">
                            <div class="col-sm-6">
                                <label class="control-label">Name</label>
                                <input type="text" class="form-control1 ng-invalid ng-invalid-required ng-touched" id="name" name="name" />
                                <label id="name-error" class="error" for="name"></label>
                            </div>
                            <div class="col-sm-6">
                                <label class="control-label">Contact Email</label>
                                <input type="email" class="form-control1 ng-invalid ng-valid-email ng-invalid-required ng-touched" id="contact_email" name="contact_email" />
                                <label id="contact_email-error" class="error" for="contact_email"></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-6">
                                <label class="control-label">Contact Name</label>
                                <input type="text" class="form-control1 ng-invalid ng-invalid-required ng-touched" id="contact_name" name="contact_name" />
                                <label id="contact_name-error" class="error" for="contact_name"></label>
                            </div>
                            <div class="col-sm-6">
                                <label class="control-label">Contact Phone</label>
                                <input type="phone" class="form-control1 ng-invalid ng-invalid-required ng-touched" id="contact_phone" name="contact_phone" />
                                <label id="contact_phone-error" class="error" for="contact_phone"></label>
                            </div>
                        </div>
                        <div class="form-group">                        
                            <div class="col-sm-6">
                                <label class="control-label">Country</label>
                                <select class="form-control1 ng-invalid ng-invalid-required" name="country_code" id="country_code">
                                    <option value="">Please Select Country</option>
                                    @foreach($countries as $country)
                                        <option value="{{$country->code}}">{{$country->name}}</option>
                                    @endforeach;
                                </select>
                                <label id="country_code-error" class="error" for="country_code"></label>
                            </div>
                            <div class="col-sm-6">
                                <div class="emailsupp">
                                    <label class="control-label">Email Suppression</label>
                                    <div id="fileuploader">Upload</div>
                                </div>
                                <input type="hidden" name="tmp_client_id_email" id="tmp_client_id_email" />
                                <div id="massage-email"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-6">
                                <div class="phonesupp">
                                    <label class="control-label">Phone Suppression</label>
                                    <div id="fileuploader1">Upload</div>
                                </div>
                                <input type="hidden" name="tmp_client_id_phone" id="tmp_client_id_phone" />
                                <div id="massage-phone"></div>
                            </div>
                            <div class="col-sm-6">
                                <label class="control-label">Lead Expiry</label>
                                <input type="text" class="form-control1 ng-invalid ng-invalid-required ng-touched" id="lead_expiry_days" name="lead_expiry_days" />
                                <label id="lead_expiry_days-error" class="error" for="lead_expiry_days"></label>
                            </div>
                        </div>
                        <div class="form-group filled">
                            <div class="col-sm-12">
                                <div class="checkboxb checkbox-primary">
                                    <input id="active" name="active" type="checkbox" value="1" checked="checked">
                                    <label for="active">
                                        Active
                                    </label>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <button id="saveButtonClient" type="submit" class="btn btn-primary">Save New Client</button>
                                <button type="reset" class="btn btn-default">Reset</button>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div id="myModal" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-header"></div>
                        <div class="modal-content">
                            <div class="modal-body">
                                <p>Client has been created </p>
                            </div>
                            <div class="modal-footer"></div>
                        </div>
                    </div>
                </div>
                <script type="text/javascript">
                    /* Start validation form */
                    $(document).ready(function() {
                        $('#name').focusout(function(){
                            var regex = /^[a-zA-Z ]*$/;
                            if($(this).val()=='') {
                                $("#name-error").text('Please enter name');
                                jQuery("button[type=submit]").prop("disabled",true);
                            } else if(!regex.test($(this).val())) {
                                $("#name-error").text('Special character and Number not allowed');
                                jQuery("button[type=submit]").prop("disabled",true);
                            } else {
                                $("#name-error").text('');
                                if($('#contact_email-error').text()=='' && $('#contact_name-error').text()=='' && $('#contact_phone-error').text()=='' && $('#country_code-error').text()=='' && $('#lead_expiry_days-error').text()=='' && $('#name-error').text()==''){
                                    jQuery("button[type=submit]").prop("disabled",false);
                                }
                            }
                        });

                        $('#contact_email').focusout(function(){
                            var regex = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
                            if($(this).val()=='') {
                                $("#contact_email-error").text('Please enter a email address');
                                jQuery("button[type=submit]").prop("disabled",true);
                            } else if(!regex.test($(this).val())) {
                                $("#contact_email-error").text('Please enter a valid email without spacial chars, ie, Example@gmail.com');
                                jQuery("button[type=submit]").prop("disabled",true);
                            } else {
                                $("#contact_email-error").text('');
                                if($('#contact_email-error').text()=='' && $('#contact_name-error').text()=='' && $('#contact_phone-error').text()=='' && $('#country_code-error').text()=='' && $('#lead_expiry_days-error').text()=='' && $('#name-error').text()==''){
                                    jQuery("button[type=submit]").prop("disabled",false);
                                }
                            }
                        });

                        $('#contact_name').focusout(function(){
                            var regex = /^[a-zA-Z ]*$/;
                            if($(this).val()=='') {
                                $("#contact_name-error").text('Please enter name');
                                jQuery("button[type=submit]").prop("disabled",true);
                            } else if(!regex.test($(this).val())) {
                                $("#contact_name-error").text('Special character and Number not allowed');
                                jQuery("button[type=submit]").prop("disabled",true);
                            } else {
                                $("#contact_name-error").text('');
                                if($('#contact_email-error').text()=='' && $('#contact_name-error').text()=='' && $('#contact_phone-error').text()=='' && $('#country_code-error').text()=='' && $('#lead_expiry_days-error').text()=='' && $('#name-error').text()==''){
                                    jQuery("button[type=submit]").prop("disabled",false);
                                }
                            }
                        });

                        $('#contact_phone').focusout(function(){
                            var phone = /^[0-9]*$/;                        
                            if($(this).val()=='') {
                                $("#contact_phone-error").text('Please enter a phone number');
                                jQuery("button[type=submit]").prop("disabled",true);
                            } else if (!phone.test($(this).val())) {
                                $("#contact_phone-error").text('Please enter a valid phone number.');
                                jQuery("button[type=submit]").prop("disabled",true);
                            } else if($(this).val().length<'9') {
                                $("#contact_phone-error").text('Phone number allowed > 9 and < 11 digits.');
                                jQuery("button[type=submit]").prop("disabled",true);
                            } else if($(this).val().length>'11'){
                                $("#contact_phone-error").text('Phone number allowed > 9 and < 11 digits.');
                                jQuery("button[type=submit]").prop("disabled",true);
                            } else {
                                $("#contact_phone-error").text('');
                                if($('#contact_email-error').text()=='' && $('#contact_name-error').text()=='' && $('#contact_phone-error').text()=='' && $('#country_code-error').text()=='' && $('#lead_expiry_days-error').text()=='' && $('#name-error').text()==''){
                                    jQuery("button[type=submit]").prop("disabled",false);
                                }
                            }
                        });

                        $('#country_code').focusout(function(){
                            if($(this).val()=='') {
                                $("#country_code-error").text('Please select country name');
                                jQuery("button[type=submit]").prop("disabled",true);
                            } else {
                                $("#country_code-error").text('');
                                if($('#contact_email-error').text()=='' && $('#contact_name-error').text()=='' && $('#contact_phone-error').text()=='' && $('#country_code-error').text()=='' && $('#lead_expiry_days-error').text()=='' && $('#name-error').text()==''){
                                    jQuery("button[type=submit]").prop("disabled",false);
                                }
                            }
                        });

                        $('#lead_expiry_days').focusout(function(){
                            var regex = /[0-9]{3}/;
                            if($(this).val()=='') {
                                $("#lead_expiry_days-error").text('Please enter lead expiry days');
                                jQuery("button[type=submit]").prop("disabled",true);
                            } else if(!regex.test($(this).val())) {
                                $("#lead_expiry_days-error").text('Please enter numeric value with 3 digits');
                                jQuery("button[type=submit]").prop("disabled",true);
                            } else {
                                $("#lead_expiry_days-error").text('');
                                if($('#contact_email-error').text()=='' && $('#contact_name-error').text()=='' && $('#contact_phone-error').text()=='' && $('#country_code-error').text()=='' && $('#lead_expiry_days-error').text()=='' && $('#name-error').text()==''){
                                    jQuery("button[type=submit]").prop("disabled",false);
                                }
                            }
                        });                    
                    });

                    jQuery(document).on('click','#saveButtonClient',function(){
                        $("#name-error").text('');
                        $("#contact_email-error").text('');
                        $("#contact_name-error").text('');
                        $("#contact_phone-error").text('');
                        $("#country_code-error").text('');
                        $("#lead_expiry_days-error").text('');
                        var valid = 0;
                        if($('#name-error').text()!='' || $('#name').val()==''){
                            $("#name-error").text('Please enter name');
                            valid = 1;
                        }
                        if($('#contact_email-error').text()!='' || $('#contact_email').val()==''){
                            $("#contact_email-error").text('Please enter a email address');
                            valid = 1;
                        }
                        if($('#contact_name-error').text()!='' || $('#contact_name').val()==''){
                            $("#contact_name-error").text('Please enter name');
                            valid = 1;
                        }
                        if($('#contact_phone-error').text()!='' || $('#contact_phone').val()==''){
                            $("#contact_phone-error").text('Please enter a phone number');
                            valid = 1;
                        }
                        if($('#country_code-error').text()!='' || $('#country_code').val()==''){
                            $("#country_code-error").text('Please select country name');
                            valid = 1;
                        }
                        if($('#lead_expiry_days-error').text()!='' || $('#lead_expiry_days').val()==''){
                            $("#lead_expiry_days-error").text('Please enter lead expiry days');
                            valid = 1;
                        }

                        if(valid==0){
                            jQuery.ajax({
                                url: "{{ action('ClientsController@store') }}",
                                type: 'POST',
                                data: {'_token':'{{ csrf_token() }}','name':$('#name').val(),'contact_email':$('#contact_email').val(),'contact_name':$('#contact_name').val(),'contact_phone':$('#contact_phone').val(),'country_code':$('#country_code').val(),'lead_expiry_days':$('#lead_expiry_days').val(),'tmp_client_id_email':$('#tmp_client_id_email').val(),'tmp_client_id_phone':$('#tmp_client_id_phone').val(),'active':$('#active').val()},
                                beforeSend:function(){
                                    jQuery("button[type=submit]").prop("disabled",true);
                                },
                                success: function(response) {
                                    if(response==1){
                                        jQuery("#myModal").modal({
                                            "backdrop"  : "static",
                                            "keyboard"  : true,
                                            "show"      : true
                                        });
                                        setTimeout(function(){
                                            window.location.href = "{{ action('ClientsController@index') }}";
                                        }, 3000);
                                    } else if (response==2) {
                                        jQuery("button[type=submit]").prop("disabled",false);
                                        jQuery('#massage').text('Client email/phone no already exist');
                                    } else {
                                        jQuery("button[type=submit]").prop("disabled",false);
                                        jQuery('#massage').text('Something Wrong! Client not Created');
                                    }
                                }            
                            });
                        }                    
                    });
                    /* End validation form */
                    /* Start active/inactive */
                    jQuery(document).on('click','#active',function(){
                        if(jQuery(this).is(':checked')==true){
                            jQuery(this).attr('checked','checked');
                            jQuery(this).val('1');
                        } else {
                            jQuery(this).removeAttr('checked');
                            jQuery(this).val('0');
                        }
                    });
                    /* End active/inactive */
                    /* Start reset */
                    jQuery("button[type=reset]").click(function(){
                        jQuery("#active").attr("checked",false);
                        jQuery('#name').val('');
                        jQuery('#name-error').text('');
                        jQuery('#contact_email').val('');
                        jQuery('#contact_email-error').text('');
                        jQuery('#contact_name').val('');
                        jQuery('#contact_name-error').text('');
                        jQuery('#contact_phone').val('');
                        jQuery('#contact_phone-error').text('');
                        jQuery('#country_code').val(''),
                        jQuery('#country_code-error').text(''),
                        jQuery('#lead_expiry_days').val('');
                        jQuery('#lead_expiry_days-error').text('');
                    });
                    /* End reset */
                    /* Start File Uploader */
                    jQuery(document).ready(function() {
                        jQuery('#fileuploader1').uploadFile({
                            url:"{{ action('ClientsController@upload') }}",
                            multiple:false,
                            autoSubmit:true,
                            fileName:'myfile',
                            allowedTypes : 'csv',
                            formData: {'_token':'{{ csrf_token() }}','typ':'phone'},
                            maxFileCount:1,
                            dynamicFormData:function(){
                                var data = {'country_code':$('#country_code').val()};
                                return data;
                            },
                            onSuccess : function(files,data,xhr) {
                                if(data.status==1){
                                    jQuery("#tmp_client_id_phone").val(data.tmp_client_id);
                                    jQuery('#massage-phone').html('');
                                } else if(data.status==2) {
                                    jQuery('#massage-phone').html('Csv file not have properly formatted missing "phone_number"').css('color','red');
                                }
                            },
                        });

                        jQuery('#fileuploader').uploadFile({
                            url:"{{ action('ClientsController@upload') }}",
                            multiple:false,
                            autoSubmit:true,
                            fileName:'myfile',
                            allowedTypes : 'csv',
                            formData: {'_token':'{{ csrf_token() }}','typ':'email'},
                            maxFileCount:1,
                            onSuccess : function(files,data,xhr) {
                                if(data.status==1){
                                    $("#tmp_client_id_email").val(data.tmp_client_id);
                                    jQuery('#massage-email').html('');
                                } else if(data.status==2) {
                                    jQuery('#massage-email').html('Csv file not have properly formatted missing "email"').css('color','red');
                                }
                            },
                        });
                    });
                    /* Start File Uploader */
                </script>
            </div>
        </div>
    @else
        <div class="xs">
            <div  class="alert alert-danger" >You are not allowed to access this page</div>
        </div>
    @endif
@endsection