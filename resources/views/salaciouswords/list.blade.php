@extends('layouts.app')
@section('title','Add/Salacious Words')
@section('content')
	<div class="xs">
        <h3>Salacious Words List</h3>
        <div class="well1 white">
        	@if($salaciouswords > 0)
        		<h4>Current list has {{$salaciouswords}} Salacious Words</h4>
        		<p><a href="{{ action('SalaciouswordsController@downloadExcelFile','csv') }}">Download</a>
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
						url:"{{ action('SalaciouswordsController@store') }}",						
						multiple:true,
						autoSubmit:true,
						fileName:'myfile',
						allowedTypes : 'csv',
						formData: {'_token':'{{ csrf_token() }}'},
						maxFileCount:1,
						onSuccess : function(files,data,xhr) {
							if(data.status=='0' && data.empty=='0'){
								jQuery('#massage').html('<ul class="error-success"><li class="success">Salacious Words csv file has empty</li></ul>').show();
							} else if(data.status=='1') {
								jQuery('#massage').html('<ul class="error-success"><li class="success">Salacious Words csv data store '+data.success+'/'+data.total+'</li><li class="duplicate">Salacious Words csv duplicate data '+data.dup+'/'+data.total+'</li><li class="errors">Salacious Words csv data not stored '+data.error+'/'+data.total+'</li></ul>').show();
								setTimeout(function(){
									window.location.href = "{{ action('SalaciouswordsController@index') }}";
								}, 3000);
							} else {
								jQuery('#massage').html('<ul class="error-success"><li class="success">Salacious Words csv data store '+data.success+'/'+data.total+'</li><li class="duplicate">Salacious Words csv duplicate data '+data.dup+'/'+data.total+'</li><li class="errors">Salacious Words csv data not stored '+data.error+'/'+data.total+'</li></ul>').show();
							}
						},
					});
				});
			</script>
        </div>
    </div>
@endsection