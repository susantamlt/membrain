{{-- 
	CSV Processing View
		Input Paremeters :
			@Client_name
			@Campaign_name
			@csv_file
		Author: Latitude Global Partners
--}}

@extends('layouts.app')
@section('title','Add/Process Lead CSV')
@section('content')
	@if(Auth::user()->role_id==1 || Auth::user()->role_id==4 || Auth::user()->role_id==5  || Auth::user()->role_id==2)
		<div class="xs">
			<h3>Process Lead CSV</h3>
			<div class="well1 white">
				<form class="form-horizontal" id="leadcsvprocess" name="leadcsvprocess" method="post" action="{{ action('ProcessleadcsvController@store') }}" accept-charset="UTF-8" enctype="multipart/form-data">
					{{ csrf_field() }}
					<fieldset>
						@if(Auth::user()->role_id==1 || Auth::user()->role_id==2 || Auth::user()->role_id==5)
							<div class="form-group">
								<label for="client_name">Client Name:</label>
								<select class="form-control1 ng-invalid ng-invalid-required" id="client_name" name="client_name">
									<option value="">--Please select a client name--</option>
									@if(isset($clients))
										@foreach($clients as $client)
										    <option value="{{ $client->id }}">{{ $client->name }}</option>
										@endforeach
									@else
										<option value="">No clients found</option>
									@endif
								</select><span class="client_error" style="color:red"></span>
							</div>  
						@elseif (Auth::user()->role_id==4)
							@if(isset($clients))
								<input type="hidden" value="{{ $clients[0]->id }}" name="client_name" />
							@endif
						@else
							<input type="hidden" value="" name="client_name" />
						@endif
						<div class="form-group">
							<label for="campaign_name">Campaign Name:</label>
							<select class="form-control1 ng-invalid ng-invalid-required" id="campaign_name" name="campaign_name">
								<option value="">--Please select a campaign--</option>
								@if(Auth::user()->role_id==4)
									@if(isset($campaigns))
										@foreach($campaigns as $campaign)
											<option value="{{ $campaign->id }}">{{ $campaign->name }}</option>
										@endforeach
									@else
										<option value="">No campaigns found</option>
									@endif
								@endif
							</select>
							<input type="hidden" id="campaignpublicid" name="campaignpublicid" value="" />
							<span id="campaign_error" style="color:red;diplay:none"></span>
						</div>
						<div class="form-group csv_div" style="display:none;">
							<label class="control-label">CSV File:</label>
							<div id="fileuploader">Upload</div>
						</div>
						<div class="form-group">
							<button type="button" id="process" class="btn btn-primary" disabled>Process</button>
						</div>
					</fieldset>    
				</form>    
				<div id="massage" class="alert alert-danger" style="display: none"></div>
				<script type="text/javascript">
					$(document).ready(function() {
						/* CSV file upload after user selects a file to upload */
						$('#fileuploader').uploadFile({
							url:"{{ action('ProcessleadcsvController@processcsv') }}",
							multiple:true,
							autoSubmit:true,
							fileName:'myfile',
							allowedTypes : 'csv',
							formData: {'_token':'{{ csrf_token() }}'},
							maxFileCount:1,
							dynamicFormData: function(files,data,xhr) {
							var data ={'id':$('#campaignpublicid').val()}
								return data;
							},
							onSubmit: function(){
								if($('#client_name').val() == '' && $('#campaign_name').val() == ''){
									$('.client_error').text("Please select client name").show();
									$('#campaign_error').text('Please select campaign name').show();
									return false;
								} else if($('#client_name').val() == ''){
									$('.client_error').text("Please select client name").show();
									return false;
								} else if($('#campaign_name').val() == '') {
									$('#campaign_error').text('Please select campaign name').show();
									return false;
								} else {
									return true;
								}
							},
							onSuccess : function(files,data,xhr) {
								var errorText = "";
								if(data.filename == '0' && data.status=='0') {
									$('#massage').html("<ul class='error-success'><li class='errors'>File name doesn't match with campaign ID</li></ul>").show();
								} else if(data.empty=='0' && data.status=='0') {
									$('#massage').html('<ul class="error-success"><li class="errors">CSV file is empty</li></ul>').show();
								} else if(data.delimiter = '0' && data.status=='0') {
									$('#massage').html('<ul class="error-success"><li class="errors">Couldn"t find Tab delimiter, CSV file is invalid.</li></ul>').show();
								} else if(data.status=='1') {
									$('#massage').html('<ul class="alert-success"><li class="success">Total no. of records are: '+data.total+'</li><li class="error-success">CSV file is valid.</li></ul>').show();
									$('#process').prop('disabled', false);
								} else {
									$('#massage').html('<ul class="error-success"><li class="success">Do not call data store '+data.success+'/'+data.total+'</li><li class="duplicate">Do not call duplicate data store '+data.dup+'/'+data.total+'</li><li class="errors">Do not call data not stored '+data.error+'/'+data.total+'</li></ul>').show();
								}
							},
							onError: function(files,status,errMsg,pd,data) {
							}
						});	
						/* END OF CSV file upload after user selects a file to upload */
						/* START: AFTER SELECTING A CLIENT FETCHING CAMPAIGN RELATED TO THAT CLIENT */
						$('#client_name').change(function(){
							var id = $(this).val();
							$.ajax({
								type:'POST',
								url:"{{ url('campaign/getCampaignsByClient') }}",
								data:{ _token:"{{ csrf_token() }}", id:id },
								dataType:'json',
								success:function(response){
									if(response.status == 1){
										var campaignHTML = '<option value="">--Please select a campaign--</option>';
										$.each(response.campaigns, function(key, element){
											campaignHTML += '<option value="'+element.id+'">'+element.name+'</option>';
										});
										$('#campaign_name').html(campaignHTML)
									}
								},
								error: function(jqXHR, textStatus, errorThrown){
									alert('some problem occurred, please try again.');
								}
							});
						});
						/* END: OF AFTER SELECTING A CLIENT FETCHING CAMPAIGN RELATED TO THAT CLIENT */
						/* START: After user selects a campaign from dropdown appending that campaign id to a form hidden input field */
						$('#campaign_name').change(function(){
							var cid = $('#campaign_name').val();
								$('#campaignpublicid').val(cid);
							if(cid == ''){
								$('.csv_div').hide();
							} else{
								$('.csv_div').show();
							}
						});
						/* END: After user selects a campaign from dropdown appending that campaign id to a form hidden input field */
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