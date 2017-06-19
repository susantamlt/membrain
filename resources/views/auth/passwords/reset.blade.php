@extends('layouts.login')
@section('title','Reset Password')
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
        <form class="form-horizontal" role="form" method="POST" action="{{ action('HomeController@newpassword') }}" name="resetPassword">
            {{ csrf_field() }}
            <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}" style="display:none;">
                <input type="email" class="text" id="username" name="username" placeholder="E-mail Address" value="{{ app('request')->input('email') }}">
                @if ($errors->has('username'))
                    <span class="help-block">
                        <strong>{{ $errors->first('username') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                <input type="password" class="text" id="password"  name="password" placeholder="New Password">
                @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                <input type="password" class="text" id="password_confirmation"  name="password_confirmation" placeholder="Re-enter">
                @if ($errors->has('password_confirmation'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                    </span>
                @endif
            </div>            
            <div class="submit"><input type="submit" class='resetpassword-button' value="Reset Password"></div>           
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
                jQuery("form[name='resetPassword']").validate({
                    rules: {                       
                        password: {
                            required: true,
                            minlength:  6,
                        },
                        password_confirmation: {
                            required: true,
                            minlength:  6,
                            equalTo: "#password"
                        }
                    },
                    messages: {                       
                        password: {
                            required: "Please enter a password.",
                            minlength: "Please enter a password of 6 char",
                        },
                        password_confirmation: {
                            required: "Please enter a password.",
                            minlength: "Please enter a password of 6 char",
                            equalTo:'Please enter match password',
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
                                    jQuery('#massage').text('Your password has been successfully reset. Please login').show();
                                    $('html').animate({ scrollTop: 0 }, 300);
                                    setTimeout(function(){
                                        window.location.href = "{{ url('/login') }}";
                                    }, 3000);
                                } else {
                                    jQuery('#massage').text('This link is no longer valid – please request a new password from the login screen').show();
                                    jQuery('form.form-horizontal').remove();
                                }
                            }
                        });
                    }
                });
            });
        </script>
    </div>
@endsection