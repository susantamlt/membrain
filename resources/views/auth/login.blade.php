@extends('layouts.login')
@section('title','Login/Membrain')
@section('content')
    <div class="login-logo">
        <a href="{{ url('/') }}"><img src="{{ asset('images/logo.png') }}"></a>
    </div>
    <!-- <h2 class="form-heading">login</h2> -->
    <div class="app-cam">
        <form class="form-horizontal" role="form" method="POST" action="{{ route('login') }}" name="loginSection">
            {{ csrf_field() }}
            <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                <input type="email" class="text" id="username" name="username" placeholder="E-mail Address">
                @if ($errors->has('username'))
                    <span class="help-block">
                        <strong>{{ $errors->first('username') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                <input type="password" class="text" id="password"  name="password" placeholder="Password">
                @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>
            <div class="submit"><button type="submit" id="submitbutton" class="btn btn-primary" style="width:100%">Login</button></div>
            <ul class="new">
                <li class="new_left" style="float:left;"><p><a href="{{ route('password.request') }}">Forgot Password ?</a></p></li>               
                <div class="clearfix"></div>
            </ul>
        </form>
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
                jQuery("form[name='loginSection']").validate({
                    rules: {
                        username: {
                            required: true,
                            email: true,
                            regex: "^[_A-Za-z0-9-\\+]+(\\.[_A-Za-z0-9-]+)*@" + "[A-Za-z0-9-]+(\\.[A-Za-z0-9]+)*(\\.[A-Za-z]{2,})$",
                        },
                        password: "required"
                    },
                    messages: {
                        username: {
                            required:"Please enter a valid email address",
                            email:"Please enter a valid email address",
                            regex: 'Please enter a valid email without spacial chars, ie, Example@gmail.com'
                        },
                        password: "Please enter password"
                    },
                    onfocusout: function(element) {
                        this.element(element);
                    },
                    submitHandler: function(form) {
                        
                        form.submit();
                    }
                });
            });
            $('#username').focusout(function(){
                var emil = $(this).val();
                jQuery.ajax({
                    type: 'POST',
                    url: "{{ action('HomeController@getUsername') }}",
                    data: { _token:'{{ csrf_token() }}', 'username':emil} , 
                    success: function(result){
                        if(result==2){
                            jQuery('#massage').text('Password reset in progress for this email address. Please check your email box or reset your password again').show();
                            jQuery('#submitbutton').attr('disabled','disabled');                           
                        } else if(result==0) {
                            jQuery('#massage').text('No user found with this email address').show();
                            jQuery('#submitbutton').attr('disabled','disabled');
                        } else {
                            jQuery('#submitbutton').removeAttr('disabled');
                            jQuery('#massage').text('').hide();
                        }
                    }
                });  
            });
        </script>
    </div>    
@endsection