@extends('layouts.login')
@section('title','Forgotpassword')
@section('content')
    <div class="login-logo">
        <a href="{{ url('/') }}"><img src="{{ asset('images/logo.png') }}"></a>
    </div>
    <div class="app-cam">
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        <form class="form-horizontal" role="form" method="POST" action="{{ action('HomeController@sendmail') }}" name="forgetPassword">
            {{ csrf_field() }}
            <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                <input type="email" class="text" id="username" name="username" placeholder="E-mail Address">
                @if ($errors->has('username'))
                    <span class="help-block">
                        <strong>{{ $errors->first('username') }}</strong>
                    </span>
                @endif
            </div>            
            <div class="submit"><input type="submit" class="forgotpassword-button" value="Send Reset Email"></div>           
        </form>
        <br><br>
        <div id="massage" class="alert alert-danger" style="display: none"></div>
        <script type="text/javascript">
            jQuery(function() {
                $.validator.addMethod("regex",function(value, element, regexp) {
                    if (regexp.constructor != RegExp)
                        regexp = new RegExp(regexp);
                    else if (regexp.global)
                        regexp.lastIndex = 0;
                    return this.optional(element) || regexp.test(value);
                },"Please check your input.");
                jQuery("form[name='forgetPassword']").validate({
                    rules: {
                        username: {
                            required: true,
                            email: true,
                            regex: "^[_A-Za-z0-9-\\+]+(\\.[_A-Za-z0-9-]+)*@" + "[A-Za-z0-9-]+(\\.[A-Za-z0-9]+)*(\\.[A-Za-z]{2,})$",
                        }
                    },
                    messages: {
                        username: {
                            required:"Please enter a valid email address",
                            email:"Please enter a valid email address",
                            regex: 'Please enter a valid email without spacial chars, ie, Example@gmail.com'
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
                            success: function(response) {
                                if(response==1){
                                    jQuery('#massage').text('Password reset in progress for this email address. Please check your email box or reset your password again').show();
                                    setTimeout(function(){
                                        window.location.href = window.location.href = "{{ url('/login') }}";
                                    }, 3000);                                    
                                } else {
                                    jQuery('#massage').text('No user found with this email address').show();
                                }
                            }
                        });
                    }
                });
            });
        </script>
    </div>
@endsection