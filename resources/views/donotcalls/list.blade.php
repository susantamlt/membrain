@extends('layouts.app')
@section('title','Add/Do Not Call')
@section('content')
	@if(Auth::user()->role_id==1 || Auth::user()->role_id==2)
		<div class="xs">
	        <h3>Do Not Call List</h3>
	        <div class="well1 white">
				@if($donotcalls > 0)
					<h4>Current list has {{$donotcalls}} phone numbers</h4>
					<p><a href="{{ action('DonotcallsController@downloadExcelFile','csv') }}">Download</a>
				@else
					<h4>Current list has {{$donotcalls}} phone numbers</h4>
				@endif
	            <fieldset>
	                <div class="form-group">
	                    <label class="control-label">&nbsp;</label>
	                    <div id="fileuploader">Upload</div>
	                </div>
	            </fieldset>            
	        	<div id="massage" class="alert alert-danger" style="display: none"></div>
				<script type="text/javascript">
					jQuery(document).ready(function() {
						jQuery('#fileuploader').uploadFile({
							url:"{{ action('DonotcallsController@store') }}",
							multiple:true,
							autoSubmit:true,
							fileName:'myfile',
							allowedTypes : 'csv',
							formData: {'_token':'{{ csrf_token() }}'},
							maxFileCount:1,
							onSuccess : function(files,data,xhr) {
								if(data.status=='0' && data.empty=='0'){
									jQuery('#massage').html('<ul class="error-success"><li class="success">Do not call csv file have empty</li></ul>').show();
								} else if(data.status=='1') {
									jQuery('#massage').html('<ul class="error-success"><li class="success">Do not call data store '+data.success+'/'+data.total+'</li><li class="duplicate">Do not call duplicate data store '+data.dup+'/'+data.total+'</li><li class="errors">Do not call data not stored '+data.error+'/'+data.total+'</li></ul>').show();
									setTimeout(function(){
										window.location.href = "{{ action('DonotcallsController@index') }}";
									}, 3000);
								} else {
									jQuery('#massage').html('<ul class="error-success"><li class="success">Do not call data store '+data.success+'/'+data.total+'</li><li class="duplicate">Do not call duplicate data store '+data.dup+'/'+data.total+'</li><li class="errors">Do not call data not stored '+data.error+'/'+data.total+'</li></ul>').show();
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