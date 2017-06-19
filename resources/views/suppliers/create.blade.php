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
@section('title','Create/Suppliers')
@section('content')
    @if(Auth::user()->role_id==1 || Auth::user()->role_id==2)
        <div class="xs">
            <h3>Create Supplier</h3>
            <div class="well1 white">
                <div id="massage"></div>
                <form class="form-floating ng-pristine ng-invalid ng-invalid-required ng-valid-email ng-valid-url ng-valid-pattern form-horizontal" role="form" name="supplierCreate" method="post" action="{{ action('SuppliersController@store') }}">
                    {{ csrf_field() }}
                    <fieldset>
                        <div class="form-group">
                            <div class="col-sm-6">
                                <label class="control-label">Name</label>
                                <input type="text" class="form-control1 ng-invalid ng-invalid-required ng-touched" id="name" name="name" />
                            </div>
                            <div class="col-sm-6">
                                <label class="control-label">Contact Email</label>
                                <input type="email" class="form-control1 ng-invalid ng-valid-email ng-invalid-required ng-touched" id="contact_email" name="contact_email" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-6">
                                <label class="control-label">Contact Name</label>
                                <input type="text" class="form-control1 ng-invalid ng-invalid-required ng-touched" id="contact_name" name="contact_name" />
                            </div>
                            <div class="col-sm-6">
                                <label class="control-label">Contact Phone</label>
                                <input type="text" class="form-control1 ng-invalid ng-invalid-required ng-touched" id="contact_phone" name="contact_phone" />
                            </div>
                        </div>
                        <div class="form-group filled">
                            <div class="col-sm-6">
                                <label class="control-label">Error Allowance</label>
                                <select class="form-control1 ng-invalid ng-invalid-required" name="error_allowance" id="error_allowance">
                                    <option value="">Please Select Error Allowance</option>
                                    @for($i=0;$i<=100;$i+=10)
                                        <option value="{{$i}}">{{$i}}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label class="control-label">&nbsp;</label>
                                  <div class="checkboxb checkbox-primary">
                                    <input id="return_csv" name="return_csv"   type="checkbox" value="" />
                                    <label for="return_csv">
                                        CSV Return
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-6">
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
                            <div class="col-sm-6">
                                <button type="submit" class="btn btn-primary">Save New Supplier</button>
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
                                <p>Supplier has been created </p>
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
                        jQuery("form[name='supplierCreate']").validate({
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
                                    minlength: 9,
                                    maxlength: 11,
                                },
                                contact_name:  {
                                    required: true,
                                    regex:/^[a-zA-Z ]*$/,
                                },
                                error_allowance: "required",
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
                                error_allowance: " Please select a number."
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
                                            jQuery('html').animate({ scrollTop: 0 }, 300);
                                            setTimeout(function(){
                                                window.location.href = "{{ action('SuppliersController@index') }}";
                                            }, 3000);
                                        } else if (response==2) {
                                            jQuery('#massage').text('Supplier email/phone no already exist');
                                            $('html').animate({ scrollTop: 0 }, 300);
                                        } else {
                                            jQuery('#massage').text('Something Wrong! Supplier not created');
                                            jQuery('html').animate({ scrollTop: 0 }, 300);
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
                    /* Start reset */
                    jQuery("button[type=reset]").click(function(){                    
                        jQuery("#active").attr("checked",false);
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