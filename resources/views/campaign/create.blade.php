@extends('layouts.app')
@section('title','Add/Campaign')
@section('content')
  @if(Auth::user()->role_id==1 || Auth::user()->role_id==2)
    <div class="xs">
      <h3>Create Campaign</h3>
      <div class="well1 white">
        <div id="msg"></div>
        <form class="form-floating ng-pristine ng-invalid ng-invalid-required ng-valid-email ng-valid-url ng-valid-pattern form-horizontal" role="form" id="campaignCreate" name="campaignCreate" method="post" action="{{ action('CampaignController@store') }}">
          {{ csrf_field() }}
          <fieldset>
            <div class="form-group">
              <div class="col-sm-12">
                <label class="control-label">Name</label>
                <input type="text" class="form-control1 ng-invalid ng-invalid-required ng-touched" id="name" name="name" />
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-12">
                <label class="control-label">Client Name</label>
                <select class="form-control1 ng-invalid ng-invalid-required ng-touched" name="client_name" id="client_name">
                  <option value="">---Please Select Client---</option>
                  @foreach ($clients as $client)
                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-12">
                <label class="control-label">Method</label>
                <select class="form-control1 ng-invalid ng-invalid-required ng-touched" name="cpmethod" id="cpmethod">
                  <option value="">---Please Select Method---</option>
                  <option value="API">API</option>
                  <option value="CSV">CSV</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-12">
                <div class="checkboxb checkbox-primary">
                  <input id="age_criteria" name="age_criteria" class="age_criteria"  type="checkbox">
                  <label for="age_criteria">Age Criteria</label>
                </div>
              </div>
            </div>
            <div id="age_range" style="display:none">
              <h4>Age Range</h4>
              <div class="form-group">
                <div class="col-sm-6">
                  <select class="form-control1" name="start_age" id="start_age">
                    <option value="">---Please Select Min Age---</option>
                    @for($i=0;$i<100;$i++)
                      <option value="{{$i}}">{{$i}}</option>
                    @endfor
                  </select>
                </div>
                <div class="col-sm-6">
                  <select class="form-control1" name="end_age" id="end_age">
                  <option value="">---Please Select Max Age---</option>
                    @for($i=0;$i<100;$i++)
                      <option value="{{$i}}">{{$i}}</option>
                    @endfor
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-12">
                <div class="checkboxb checkbox-primary">
                  <input id="state_criteria" name="state_criteria" class="state_criteria"  type="checkbox">
                  <label for="state_criteria">
                    State Criteria
                  </label>
                </div>
              </div>
            </div>
            <div id="state_list_hidden" style="display:none">
              <div class="form-group">
                <div class="col-sm-12">
                  <input type="text" name="state_list" class="state_list form-control1" id="state_list">
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-12">
                <div class="checkboxb checkbox-primary">
                  <input id="postcode_criteria" name="postcode_criteria" class="postcode_criteria"  type="checkbox">
                  <label for="postcode_criteria">
                    Postcode Criteria
                  </label>
                </div>
              </div>
            </div>
            <div id="postcode_list_hidden" style="display:none">
              <div class="form-group">
                <div class="col-sm-12">
                  <label for="postcode_list">Postcode List:</label>
                  <textarea name="postcode_list" class="postcode_list form-control1" rows="5" id="postcode_list"></textarea>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-6">
                <label>DNCR Required</label>
                <div class="radio radio-info radio-inline" style="padding-top:0px;">
                  <input type="radio" id="dncr_required1" value="0" name="dncr_required" checked="checked">
                  <label for="dncr_required1">Yes </label>
                </div>
                <div class="radio radio-inline radio-info" style="padding-top:0px;">
                  <input type="radio" id="dncr_required2" value="1" name="dncr_required">
                  <label for="dncr_required2"> No </label>
                </div>
              </div>
              <div class="col-sm-6">
                <label class="control-label">&nbsp;</label>
                <div class="checkboxb checkbox-primary">
                  <input id="active" name="active" type="checkbox" checked="checked">
                  <label for="active">
                    Active
                  </label>
                </div>
              </div>
            </div>
            <div class="api" style="display:none">
              <div class="form-group">
                <div class="col-sm-6">
                  <label for="type">Type:</label>
                  <select class="form-control1" id="type" name="type" >
                    <option value="">--Please select type--</option>
                    <option value="GET">GET</option>
                    <option value="POST">POST</option>
                    <option value="JSON POST">JSON POST</option>
                  </select>
                </div>
                <div class="col-sm-6">
                  <label class="control-label">Endpoint</label>
                  <input type="text" class="form-control1 ng-invalid ng-invalid-required ng-touched" id="endpoint" name="endpoint" class="endpoint" />
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-6">
                  <label class="control-label">User</label>
                  <input type="text" class="form-control1 ng-invalid ng-invalid-required ng-touched" id="user" name="user" class="user" />
                </div>    
                <div class="col-sm-6">
                  <label class="control-label">Password</label>
                  <input type="password" class="form-control1 ng-invalid ng-invalid-required ng-touched" id="password" name="password" class="password" />
                </div>
              </div>    
              <div class="form-group">
                <div class="col-sm-6">
                  <label class="control-label">Port</label>
                  <input type="text" class="form-control1 ng-invalid ng-invalid-required ng-touched" id="port" name="port" class="port" />
                </div>
              </div>     
              <h3>Standard Parameter Mapping Fields</h3>
              <div class="form-group">
                <div class="col-sm-6">
                  <label for="email">email:</label>
                  <input type="text" class="form-control1" id="email" name="email">
                </div>
                <div class="col-sm-6">
                  <label for="phone">phone:</label>
                  <input type="text" class="form-control1 phone" id="phone" name="phone">
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-6">
                  <label for="title">title:</label>
                  <input type="text" class="form-control1 title" id="title" name="title">
                </div>
                <div class="col-sm-6">
                  <label for="firstName">firstName:</label>
                  <input type="text" class="form-control1 firstName" id="firstName" name="firstName">
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-6">
                  <label for="lastName">lastName:</label>
                  <input type="text" class="form-control1 lastName" id="lastName" name="lastName">
                </div>
                <div class="col-sm-6">
                  <label for="birthdate">birthdate:</label>
                  <input type="text" class="form-control1 birthdate" id="birthdate" name="birthdate">
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-6">
                  <label for="age">age:</label>
                  <input type="text" class="form-control1 age" id="age" name="age">
                </div>
                <div class="col-sm-6">
                  <label for="ageRange">ageRange:</label>
                  <input type="text" class="form-control1 ageRange" id="ageRange" name="ageRange">
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-6">
                  <label for="gender">gender:</label>
                  <input type="text" class="form-control1 gender" id="gender" name="gender">
                </div>
                <div class="col-sm-6">
                  <label for="address1">address1:</label>
                  <input type="text" class="form-control1 address1" id="address1" name="address1">
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-6">
                  <label for="address2">address2:</label>
                  <input type="text" class="form-control1 address2" id="address2" name="address2">
                </div>
                <div class="col-sm-6">
                  <label for="city">city:</label>
                  <input type="text" class="form-control1 city" id="city" name="city">
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-6">
                  <label for="state">state:</label>
                  <input type="text" class="form-control1 state" id="state" name="state">
                </div>
                <div class="col-sm-6">
                  <label for="postcode">postcode:</label>
                  <input type="text" class="form-control1 postcode" id="postcode" name="postcode">
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-6">
                  <label for="countryCode">countryCode:</label>
                  <input type="text" class="form-control1 countryCode" id="countryCode" name="countryCode">
                </div>
              </div>
            </div>
            <div class="csv" style="display:none">
              <div class="form-group">
                <div class="col-sm-6">
                  <label for="server_type">Server Type:</label>
                  <select class="form-control1" id="server_type" name="server_type">
                    <option value="">--Please select server type--</option>
                    <option value="ftp">FTP</option>
                    <option value="sftp">SFTP</option>
                  </select>
                </div>
                <div class="col-sm-6">
                  <label class="control-label">Server</label>
                  <input type="text" class="form-control1" id="csv_server" name="csv_server" class="csv_server" />
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-6">
                  <label class="control-label">Directory</label>
                  <input type="text" class="form-control1" id="directory" name="directory" class="directory" />
                </div>   
                <div class="col-sm-6">
                  <label class="control-label">User</label>
                  <input type="text" class="form-control1" id="csv_user" name="csv_user" class="csv_user" />
                </div>
              </div> 
              <div class="form-group">
                <div class="col-sm-6">
                  <label class="control-label">Password</label>
                  <input type="password" class="form-control1" id="csv_password" name="csv_password" class="csv_password" />
                </div>
              </div>    
              <h3>Column-Mapping Fields</h3>
              <div class="form-group">
                <div class="col-sm-6">
                  <label for="csv_email">email:</label>
                  <select class="form-control1 ng-invalid ng-invalid-required csv_email" name="csv_email" id="csv_email">
                    <option value="">Please Select</option>
                    @for($i=0;$i<100;$i++)
                      <option value="{{$i}}">{{$i}}</option>
                    @endfor
                  </select>
                </div>
                <div class="col-sm-6">
                  <label for="csv_phone">phone:</label>
                  <select class="form-control1 ng-invalid ng-invalid-required csv_phone" name="csv_phone" id="csv_phone">
                    <option value="">Please Select</option>
                    @for($i=0;$i<100;$i++)
                      <option value="{{$i}}">{{$i}}</option>
                    @endfor
                  </select>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-6">
                  <label for="csv_title">title:</label>
                  <select class="form-control1 ng-invalid ng-invalid-required csv_title" name="csv_title" id="csv_title">
                    <option value="">Please Select</option>
                    @for($i=0;$i<100;$i++)
                      <option value="{{$i}}">{{$i}}</option>
                    @endfor
                  </select>
                </div>
                <div class="col-sm-6">
                  <label for="csv_firstName">firstName:</label>
                  <select class="form-control1 ng-invalid ng-invalid-required csv_firstName" name="csv_firstName" id="csv_firstName">
                    <option value="">Please Select</option>
                    @for($i=0;$i<100;$i++)
                      <option value="{{$i}}">{{$i}}</option>
                    @endfor
                  </select>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-6">
                  <label for="csv_lastName">lastName:</label>
                  <select class="form-control1 ng-invalid ng-invalid-required csv_lastName" name="csv_lastName" id="csv_lastName">
                    <option value="">Please Select</option>
                    @for($i=0;$i<100;$i++)
                      <option value="{{$i}}">{{$i}}</option>
                    @endfor
                  </select>
                </div>
                <div class="col-sm-6">
                  <label for="csv_birthdate">birthdate:</label>
                  <select class="form-control1 ng-invalid ng-invalid-required csv_birthdate" name="csv_birthdate" id="csv_birthdate">
                    <option value="">Please Select</option>
                    @for($i=0;$i<100;$i++)
                      <option value="{{$i}}">{{$i}}</option>
                    @endfor
                  </select>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-6">
                  <label for="csv_age">age:</label>
                  <select class="form-control1 ng-invalid ng-invalid-required csv_age" name="csv_age" id="csv_age">
                    <option value="">Please Select</option>
                    @for($i=0;$i<100;$i++)
                      <option value="{{$i}}">{{$i}}</option>
                    @endfor
                  </select>
                </div>
                <div class="col-sm-6">
                  <label for="csv_ageRange">ageRange:</label>
                  <select class="form-control1 ng-invalid ng-invalid-required csv_ageRange" name="csv_ageRange" id="csv_ageRange">
                    <option value="">Please Select</option>
                    @for($i=0;$i<100;$i++)
                      <option value="{{$i}}">{{$i}}</option>
                    @endfor
                  </select>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-6">
                  <label for="csv_gender">gender:</label>
                  <select class="form-control1 ng-invalid ng-invalid-required csv_gender" name="csv_gender" id="csv_gender">
                    <option value="">Please Select</option>
                    @for($i=0;$i<100;$i++)
                      <option value="{{$i}}">{{$i}}</option>
                    @endfor
                  </select>
                </div>
                <div class="col-sm-6">
                  <label for="csv_address1">address1:</label>
                  <select class="form-control1 ng-invalid ng-invalid-required csv_address1" name="csv_address1" id="csv_address1">
                    <option value="">Please Select</option>
                    @for($i=0;$i<100;$i++)
                      <option value="{{$i}}">{{$i}}</option>
                    @endfor
                  </select>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-6">
                  <label for="csv_address2">address2:</label>
                  <select class="form-control1 ng-invalid ng-invalid-required csv_address2" name="csv_address2" id="csv_address2">
                    <option value="">Please Select</option>
                    @for($i=0;$i<100;$i++)
                      <option value="{{$i}}">{{$i}}</option>
                    @endfor
                  </select>
                </div>
                <div class="col-sm-6">
                  <label for="csv_city">city:</label>
                  <select class="form-control1 ng-invalid ng-invalid-required csv_city" name="csv_city" id="csv_city">
                    <option value="">Please Select</option>
                    @for($i=0;$i<100;$i++)
                      <option value="{{$i}}">{{$i}}</option>
                    @endfor
                  </select>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-6">
                  <label for="csv_state">state:</label>
                  <select class="form-control1 ng-invalid ng-invalid-required csv_state" name="csv_state" id="csv_state">
                    <option value="">Please Select</option>
                    @for($i=0;$i<100;$i++)
                      <option value="{{$i}}">{{$i}}</option>
                    @endfor
                  </select>
                </div>
                <div class="col-sm-6">
                  <label for="csv_postcode">postcode:</label>
                  <select class="form-control1 ng-invalid ng-invalid-required csv_postcode" name="csv_postcode" id="csv_postcode">
                    <option value="">Please Select</option>
                    @for($i=0;$i<100;$i++)
                      <option value="{{$i}}">{{$i}}</option>
                    @endfor
                  </select>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-6">
                  <label for="csv_countryCode">countryCode:</label>
                  <select class="form-control1 ng-invalid ng-invalid-required csv_countryCode" name="csv_countryCode" id="csv_countryCode">
                    <option value="">Please Select</option>
                    @for($i=0;$i<100;$i++)
                      <option value="{{$i}}">{{$i}}</option>
                    @endfor
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group">
              <button type="submit" class="btn btn-primary">Save New Campaign</button>
              <button type="reset" class="btn btn-default">Reset</button>
            </div>
          </fieldset>
        </form>
        <div id="myModal" class="modal fade" role="dialog">
          <div class="modal-dialog">
            <div class="modal-header"></div>
            <div class="modal-content">
              <div class="modal-body">
                <p>Campaign has been Created </p>
              </div>
              <div class="modal-footer"></div>
            </div>
          </div>
        </div>
        <script type="text/javascript">
          jQuery(function() {
            $.validator.addMethod("regex",function(value, element, regexp) {
              if (regexp.constructor != RegExp)
                regexp = new RegExp(regexp);
              else if (regexp.global)
                regexp.lastIndex = 0;
              return this.optional(element) || regexp.test(value);
            },"Please check your input.");

            jQuery("form[name='campaignCreate']").validate({
              rules: {
                name: {
                  required: true,
                  minlength: 2,
                  maxlength: 20,
                  lettersonly: true,
                },
                client_name:{
                  required: true,
                },
                cpmethod: "required",
                dncr_required: "required",
                start_age:{
                  required: function(el){
                    return $(el).closest('form').find('#age_criteria').is(':checked') == true;
                  }
                },
                end_age:{
                  required: function(el){
                    return $(el).closest('form').find('#age_criteria').is(':checked') == true;
                  }
                },
                state_list:{
                  required: function(el){
                    return $(el).closest('form').find('#state_criteria').is(':checked') == true;
                  }
                },
                postcode_list:{
                  required:function(el){
                    return $(el).closest('form').find('#postcode_criteria').is(':checked') == true;
                  },
                  number:true,
                }  
              },
              messages: {
                name: "Please enter your name",
                client_name: {
                  required:"Please select a client name",
                },
                cpmethod: {
                  required: "Please select a method.",
                },
                dncr_required: "Please select dncr required",
                start_age:"Please select Min Age",
                end_age:"Please select Max Age",
                state_list:"Please enter a state",
                postcode_list:{
                  required :"Please enter a postcode",
                  number :"Please enter numeric value",
                }
              }, 
              onfocusout: function(element) {
                this.element(element);
              },
              submitHandler: function(form) {
                $.ajax({
                  url: form.action,
                  type: form.method,
                  data: $(form).serialize(),
                  dataType: "json",
                  success: function(response) {
                    if(response==1){
                      $("#myModal").modal({
                        "backdrop"  : "static",
                        "keyboard"  : true,
                        "show"      : true
                      });
                      jQuery('html').animate({ scrollTop: 0 }, 300);
                      setTimeout(function(){
                        window.location.href = "{{ action('CampaignController@index') }}";
                      }, 3000);
                    } else{
                      $('#msg').text('Some problem occurred, campaign not created.').show().delay('3000').hide();
                    }
                  },
                  error: function(jqXHR, textStatus, errorThrown){
                    alert('some problem occurred, please try again.');
                  }
                });
              }
            });
          });

          jQuery(document).on('change','#cpmethod',function(){
            var val = $(this).val();
            if(val == 'API') {
              $('.csv').hide();
              $('.api').show();
            } else if(val == 'CSV') {
              $('.api').hide();
              $('.csv').show();
            } else {
              $('.api').hide();
              $('.csv').hide();
            }
          });
          
          jQuery(document).on('change','#age_criteria',function(){
            if($(this).is(":checked")) {
              var checked = $(this).val();
              $('#age_range').show();
            } else {
              $('#age_range').hide();
            }
          });

          jQuery(document).on('change','#state_criteria',function(){
            if($(this).is(":checked")) {
              $('#state_list_hidden').show();
            } else {
              $('#state_list_hidden').hide()
            }
          });
          jQuery(document).on('change','#postcode_criteria',function(){
            if($(this).is(":checked")) {
              $('#postcode_list_hidden').show()
            } else {
              $('#postcode_list_hidden').hide()
            }
          });
          
          jQuery(document).on('click','#active',function(){
            if(jQuery(this).is(':checked')==true){
              jQuery(this).attr('checked','checked');
              jQuery(this).val('1');
            } else {
              jQuery(this).removeAttr('checked');
              jQuery(this).val('0');
            }
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