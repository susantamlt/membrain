@extends('layouts.app')
@section('title','Add/Email Black Lists')
@section('content')
	@if(Auth::user()->role_id==1 || Auth::user()->role_id==2)
		<div class="xs">
	        <h3>Email Black Lists</h3>
	        <div class="well1 white">
	        	@if($emailblacklists > 0)
	        		<h4>Current list has {{$emailblacklists}} Email Black Lists</h4>
	        		<p><a href="{{ action('EmailblacklistsController@downloadExcelFile','csv') }}">Download</a>
	        	@else
	        		<h4>The list is currently empty</h4>
	        	@endif
	            <fieldset>
	                <div class="form-group">
	                    <div id="fileuploader">Upload</div>
	                </div>
	            </fieldset>
	            <div id="massage" class="alert alert-danger" style="display: none"></div>
				<script type="text/javascript">
					jQuery(document).ready(function() {
						jQuery('#fileuploader').uploadFile({
							url:"{{ action('EmailblacklistsController@store') }}",						
							multiple:true,
							autoSubmit:true,
							fileName:'myfile',
							allowedTypes : 'csv',
							formData: {'_token':'{{ csrf_token() }}'},
							maxFileCount:1,
							onSuccess : function(files,data,xhr) {
								if(data.status=='0' && data.empty=='0'){
									jQuery('#massage').html('<ul class="error-success"><li class="success">Email Black List csv file has empty</li></ul>').show();
								} else if(data.status=='1') {
									jQuery('#massage').html('<ul class="error-success"><li class="success">Email Black List csv data store '+data.success+'/'+data.total+'</li><li class="duplicate">Email Black List csv duplicate data '+data.dup+'/'+data.total+'</li><li class="errors">Email Black List csv data not stored '+data.error+'/'+data.total+'</li><li class="errors">Email Black List email not valid '+data.vdom+'/'+data.total+'</li></ul>').show();
									setTimeout(function(){
										window.location.href = "{{ action('EmailblacklistsController@index') }}";
									}, 3000);
								} else {
									jQuery('#massage').html('<ul class="error-success"><li class="success">Email Black List csv data store '+data.success+'/'+data.total+'</li><li class="duplicate">Email Black List csv duplicate data '+data.dup+'/'+data.total+'</li><li class="errors">Email Black List csv data not stored '+data.error+'/'+data.total+'</li><li class="errors">Email Black List email not valid '+data.vdom+'/'+data.total+'</li></ul>').show();
								}
							},
						});
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