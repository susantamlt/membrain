{{--
    Input Parameters:
        @Contact Username (Unique email)
        @Contact Email (Unique)
        @Contact Full Name
        @Contact Password
        @Contact Confirm Password
        @Role(Select Dropdown)
        @Supplier(Select Dropdown) When role select supplier
        @Client(Select Dropdown) When role select Client
        @Clients(Select Dropdown) When role select Super Client
        @Active (Chheckbox)
        Author: Latitude Global Partners
--}}

@extends('layouts.app')
@section('title','Add/Portal User')
@section('content')
    @if(Auth::user()->role_id==1)
        <div class="xs">
            <h3>Create Portal User</h3>
            <div class="well1 white">
                <div id="massage" class="alert alert-success" style="display: none;"></div>
                <div id="error_message" class="alert alert-danger" style="display: none;"></div>
                @if(Auth::user()->role_id==1)
                    <form class="form-floating ng-pristine ng-invalid ng-invalid-required ng-valid-email ng-valid-url ng-valid-pattern form-horizontal" role="form" name="userCreate" method="post" action="{{ action('PortaluserController@store') }}">
                        {{ csrf_field() }}
                        <fieldset>
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <label class="control-label">Username</label>
                                    <input type="email" class="form-control1 ng-invalid ng-valid-email ng-invalid-required ng-touched" id="username" name="username" />
                                </div>
                                <div class="col-sm-6">
                                    <label class="control-label">Name</label>
                                    <input type="text" class="form-control1 ng-invalid ng-invalid-required ng-touched" id="name" name="name" />
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <label class="control-label">Password</label>
                                    <input type="password" class="form-control1 ng-invalid ng-invalid-required ng-touched" id="password" name="password" />
                                </div>
                                <div class="col-sm-6">
                                    <label class="control-label">Confirm Password</label>
                                    <input type="password" class="form-control1 ng-invalid ng-invalid-required ng-touched" id="cpassword" name="cpassword" />
                                </div>
                            </div>
                            <div class="form-group filled">
                                <div class="col-sm-6">
                                    <label class="control-label">Role</label>
                                    <select class="form-control1 ng-invalid ng-invalid-required" name="role_id" id="role_id">
                                        <option value="">Please Select Role</option>
                                        @foreach($portalroles as $portalrole)
                                            <option value="{{$portalrole->id}}">{{$portalrole->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-6 supplierid" style="display:none;">
                                    <label class="control-label">Supplier</label>
                                    <select class="form-control1 ng-invalid ng-invalid-required" name="supplier_id" id="supplier_id">
                                        <option value="">Please Select Supplier</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{$supplier->id}}">{{$supplier->contact_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-6 clientid" style="display:none;">
                                    <label class="control-label">Client</label>
                                    <select class="form-control1 ng-invalid ng-invalid-required" name="client_id" id="client_id">
                                        <option value="">Please Select Client</option>
                                        @foreach($clients as $client)
                                            <option value="{{$client->id}}">{{$client->contact_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-6 clientsid" style="display:none;">
                                    <label class="control-label">Clients</label>
                                    <select class="form-control1 ng-invalid ng-invalid-required" name="clients_id[]" id="clients_id" multiple="multiple">
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}">{{$client->contact_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <div class="checkboxb checkbox-primary">
                                        <input id="active" name="active"  type="checkbox" value="1" checked="checked">
                                        <label for="active">
                                            Active
                                        </label>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                             </div>
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <button type="submit" class="btn btn-primary">Save New User</button>
                                    <button type="reset" class="btn btn-default">Reset</button>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </fieldset>
                    </form>
                @else
                  <div  class="alert alert-danger" >You are not allowed to access this page</div>
                @endif 
                <div id="myModal" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-header"></div>
                        <div class="modal-content">
                            <div class="modal-body">
                                <p>Portal User has been created</p>
                            </div>
                            <div class="modal-footer"></div>
                        </div>
                    </div>
                </div>
                <script type="text/javascript">
                    /* Start validation form and store data using ajax */
                    jQuery(function() {
                        $.validator.addMethod("regex",function(value, element, regexp) {
                            if (regexp.constructor != RegExp)
                                regexp = new RegExp(regexp);
                            else if (regexp.global)
                                regexp.lastIndex = 0;
                            return this.optional(element) || regexp.test(value);
                        },"Please check your input.");
                        jQuery("form[name='userCreate']").validate({
                            rules: {
                                username: {
                                    required: true,
                                    email: true,
                                    regex: "^[_A-Za-z0-9-\\+]+(\\.[_A-Za-z0-9-]+)*@" + "[A-Za-z0-9-]+(\\.[A-Za-z0-9]+)*(\\.[A-Za-z]{2,})$",
                                },
                                name: {
                                    required: true,
                                    regex:/^[a-zA-Z ]*$/,
                                },
                                password: {
                                    required: true,
                                    minlength:  6,
                                },
                                cpassword: {
                                    required: true,
                                    minlength:  6,
                                    equalTo: "#password"
                                },
                                role_id: "required",
                                client_id: {
                                    required: function (el) {
                                        return $(el).closest('form').find('#role_id').val()=='4';
                                    }
                                },
                                supplier_id:  {
                                    required: function (el) {
                                        return $(el).closest('form').find('#role_id').val()=='3';
                                    }
                                },
                                clients_id:  {
                                    required: function (el) {
                                        return $(el).closest('form').find('#role_id').val()=='5';
                                    }
                                }
                            },
                            messages: {
                                username: {
                                    required:"Please enter a valid email address",
                                    email:"Please enter a valid email address",
                                    regex: 'Please enter a valid email without spacial chars, ie, Example@gmail.com'
                                },
                                name: {
                                    required: "Please enter a name.",
                                    regex: "Special character and Number not allowed"
                                },
                                password: {
                                    required: "Please enter a password.",
                                    minlength: "Please enter a password of 6 char",
                                },
                                cpassword: {
                                    required: "Please enter a password.",
                                    minlength: "Please enter a password of 6 char",
                                    equalTo:'Please enter match password',
                                },
                                role_id: "Please select a role",
                                client_id: {
                                    required: "Please select user Client"
                                },
                                supplier_id: {
                                    required: "Please select user Supplier"
                                },
                                clients_id: {
                                    required: "Please select user Clients"
                                }
                            },
                            onfocusout: function(element) {
                                this.element(element);
                            },
                            submitHandler: function(form) {
                                jQuery.ajax({
                                    url: form.action,
                                    type: form.method,
                                    data: $(form).serialize(),
                                    beforeSend:function(){
                                        $("button[type=submit]").prop("disabled",true);
                                    },
                                    success: function(response) {
                                        if(response==1){
                                            $("#myModal").modal({
                                                "backdrop"  : "static",
                                                "keyboard"  : true,
                                                "show"      : true
                                            });
                                            jQuery("#massage").show();
                                            //jQuery('#massage').text('New User Created Successfully');
                                            $('html').animate({ scrollTop: 0 }, 300);
                                            setTimeout(function(){
                                                window.location.href = "{{ action('PortaluserController@index') }}";
                                            }, 3000);
                                        } else if (response==2) {
                                            $("button[type=submit]").prop("disabled",false);
                                            jQuery("#error_message").show();
                                            jQuery('#error_message').text('User already exist in the system');
                                            $('html').animate({ scrollTop: 0 }, 300);
                                        } else {
                                            $("button[type=submit]").prop("disabled",false);
                                            jQuery("#error_message").show();
                                            jQuery('#error_message').text('Something Wrong! User not created');
                                            $('html').animate({ scrollTop: 0 }, 300);
                                        }
                                    }           
                                });
                            }
                        });
                    });
                    /* Start validation form and store data using ajax*/
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
                    jQuery(document).on('change','#role_id',function(){
                        if(jQuery(this).val()=='3'){
                            jQuery('.supplierid').show();
                            jQuery('.clientid').hide();
                            jQuery('.clientsid').hide();
                            jQuery('#client_id option:selected').removeAttr('selected');
                            jQuery('#client_id option:eq(0)').attr('selected','selected');
                            jQuery('#clients_id option:selected').removeAttr('selected');
                            jQuery('#clients_id option:eq(0)').attr('selected','selected');
                        } else if(jQuery(this).val()=='4'){
                            jQuery('.supplierid').hide();
                            jQuery('.clientid').show();
                            jQuery('.clientsid').hide();
                            jQuery('#supplier_id option:selected').removeAttr('selected');
                            jQuery('#supplier_id option:eq(0)').attr('selected','selected');
                            jQuery('#clients_id option:selected').removeAttr('selected');
                            jQuery('#clients_id option:eq(0)').attr('selected','selected');
                        } else if(jQuery(this).val()=='5'){
                            jQuery('.supplierid').hide();
                            jQuery('.clientid').hide();
                            jQuery('.clientsid').show();
                            jQuery('#supplier_id option:selected').removeAttr('selected');
                            jQuery('#supplier_id option:eq(0)').attr('selected','selected');
                            jQuery('#client_id option:selected').removeAttr('selected');
                            jQuery('#client_id option:eq(0)').attr('selected','selected');
                        } else {
                            jQuery('.supplierid').hide();
                            jQuery('.clientid').hide();
                            jQuery('.clientsid').hide();
                            jQuery('#supplier_id option:selected').removeAttr('selected');
                            jQuery('#supplier_id option:eq(0)').attr('selected','selected');
                            jQuery('#client_id option:selected').removeAttr('selected');
                            jQuery('#client_id option:eq(0)').attr('selected','selected');
                            jQuery('#clients_id option:selected').removeAttr('selected');
                            jQuery('#clients_id option:eq(0)').attr('selected','selected');
                        }
                    });
                    /* Start reset */
                    $("button[type=reset]").click(function(){                    
                        $("#active").attr("checked",false);
                        jQuery('#active').val('0');
                    });
                    /* End reset */
                </script>
            </div>
        </div>
    @else
        <div class="xs">
            <div  class="alert alert-danger" >You are not allowed to access this page</div>
        </div>
    @endif
@endsection