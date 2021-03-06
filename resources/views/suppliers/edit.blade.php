{{--
    Input Parameters:
        @Name
        @Contact Email (Unique)
        @Contact Full Name
        @Contact Phone (Unique)
        @Error Allowance(Select Dropdown)
        @CSV Return (Chheckbox)
        @Active (Chheckbox)
        Author: Latitude Global Partners
--}}

@extends('layouts.app')
@section('title','Edit/Supplier')
@section('content')
    @if(Auth::user()->role_id==1 || Auth::user()->role_id==2)
        <div class="xs">
            <h3>Edit Supplier</h3>
            <div class="well1 white">
                <div id="massage"></div>
                <form class="form-floating ng-pristine ng-invalid ng-invalid-required ng-valid-email ng-valid-url ng-valid-pattern form-horizontal" role="form" name="supplierUpdate" method="post" action="{{ action('SuppliersController@update',$suppliers->id) }}">
                    {{ csrf_field() }}
                    {{method_field('PUT')}}
                    <fieldset>
                        <div class="form-group">
                            <div class="col-sm-6">
                                <label class="control-label">Name</label>
                                <input type="text" class="form-control1 ng-invalid ng-invalid-required ng-touched" id="name" name="name" value="{{$suppliers->name}}" />
                            </div>
                            <div class="col-sm-6">
                                <label class="control-label">Contact Email</label>
                                <input type="email" class="form-control1 ng-invalid ng-valid-email ng-invalid-required ng-touched" id="contact_email" name="contact_email" value="{{$suppliers->contact_email}}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-6">
                                <label class="control-label">Contact Name</label>
                                <input type="text" class="form-control1 ng-invalid ng-invalid-required ng-touched" id="contact_name" name="contact_name" value="{{$suppliers->contact_name}}" />
                            </div>
                            <div class="col-sm-6">
                                <label class="control-label">Contact Phone</label>
                                <input type="text" class="form-control1 ng-invalid ng-invalid-required ng-touched" id="contact_phone" name="contact_phone" value="{{$suppliers->contact_phone}}" />
                            </div>
                        </div>
                        <div class="form-group filled">
                            <div class="col-sm-6">
                                <label class="control-label">Error Allowance</label>
                                <select class="form-control1 ng-invalid ng-invalid-required" name="error_allowance" id="error_allowance">
                                    <option value="" @if($suppliers->error_allowance=='') selected @endif>Please Select Error Allowance</option>
                                    @for($i=0;$i<=100;$i+=10)
                                        <option value="{{$i}}" @if($suppliers->error_allowance==$i) selected @endif>{{$i}}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label class="control-label">&nbsp;</label>
                                <div class="checkboxb checkbox-primary">
                                    <input id="return_csv" name="return_csv" type="checkbox" value="{{$suppliers->return_csv}}" {{$suppliers->return_csv==1 ?' checked=checked':''}}>
                                    <label for="return_csv">
                                        CSV Return
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">                       
                            <div class="col-sm-6">
                                <div class="checkboxb checkbox-primary">
                                    <input id="active" name="active"   type="checkbox" value="{{$suppliers->active}}" {{$suppliers->active==1 ?' checked=checked':''}}>
                                    <label for="active">
                                        Active
                                    </label>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-6">
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                <button type="reset" class="btn btn-default">Reset</button>
                            </div>
                        </div>
                    </fieldset>
                </form>
                <div id="myModal" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-header"></div>
                        <div class="modal-content">
                            <div class="modal-body">
                                <p>Supplier has been updated </p>
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
                        jQuery("form[name='supplierUpdate']").validate({
                            rules: {
                                name: {
                                    required: true,
                                    regex:/^[a-zA-Z ]*$/,
                                },
                                contact_email: {
                                    required: true,
                                    email: true,
                                    regex: "^[_A-Za-z0-9-\\+]+(\\.[_A-Za-z0-9-]+)*@" + "[A-Za-z0-9-]+(\\.[A-Za-z0-9]+)*(\\.[A-Za-z]{2,})$",
                                },
                                contact_phone: {
                                    required: true,
                                    number: true,
                                    minlength:9,
                                    maxlength:11,
                                },
                                contact_name:  {
                                    required: true,
                                    regex:/^[a-zA-Z ]*$/,
                                },
                                error_allowance: "required"
                            },
                            messages: {
                                name: {
                                    required: "Please enter name",
                                    regex: "Special character and Number not allowed"
                                },
                                contact_email: {
                                    required:"Please enter a email address.",
                                    email:"Please enter a valid email address.",
                                    regex: 'Please enter a valid email without spacial chars, ie, Example@gmail.com'
                                },
                                contact_phone: {
                                    required: "Please enter a phone number.",
                                    number: "Please enter a valid phone number.",
                                    minlength: "Your phone must be at min 9 digits",
                                    maxlength: "Your phone must be at max 11 digits",
                                },
                                contact_name:  {
                                    required: "Please enter a name",
                                    regex: "Special character and Number not allowed"
                                },
                                error_allowance: "Please select a number."
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
                                            $("#myModal").modal({
                                                "backdrop"  : "static",
                                                "keyboard"  : true,
                                                "show"      : true
                                            });
                                            $('html').animate({ scrollTop: 0 }, 300);
                                            setTimeout(function(){
                                                window.location.href = "{{ action('SuppliersController@index') }}";
                                            }, 3000);
                                        } else if (response==2) {
                                            jQuery('#massage').text('Supplier email/phone no already exist');
                                            $('html').animate({ scrollTop: 0 }, 300);
                                        } else {
                                            jQuery('#massage').text('Something Wrong! Supplier not created');
                                            $('html').animate({ scrollTop: 0 }, 300);
                                        }
                                    }            
                                });
                            }
                        });
                    });
                    /* Start validation form and store data using ajax */
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
                </script>
            </div>
        </div>
    @else
        <div class="xs">
            <div  class="alert alert-danger" >You are not allowed to access this page</div>
        </div>
    @endif
@endsection