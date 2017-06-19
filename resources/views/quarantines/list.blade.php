@extends('layouts.app')
@section('title','Quarantine')
@section('content')
    @if(Auth::user()->role_id==1 || Auth::user()->role_id==2 )
        <div class="xs">
            <h3>List of Quarantine</h3>
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            @if (session('delete_error'))
                <div class="alert alert-danger">
                    {{ session('delete_error') }}
                </div>
            @endif
            <div id="delete_qua_div">
                <span class="delete_qua_ok" style="color:green;text-align: center"></span>
                <span class="delete_qua_error" style="color:red;text-align: center"></span>
            </div>
            <div class="bs-example4" data-example-id="contextual-table">
                <table class="table table-striped table-bordered dt-responsive nowrap" id="quarantines">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Supplier Name</th>
                            <th>Client Name</th>
                            <th>Campaign Name</th>
                            <th>Errors</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                    	@foreach($quarantines as $key => $quarantine)
    	                    <tr class="active">
    	                        <th scope="row">{{$key+1}}</th>
    	                        <td>{{$quarantine->sname}}</td>
    	                        <td>{{$quarantine->cname}}</td>
    	                        <td>{{$quarantine->cmpname}}</td>
    	                        <td>{{$quarantine->reason}}</td>
    	                        <td><a href="{{ url('/quarantines/'.$quarantine->id) }}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> | <a href="{{ url('/quarantines/delete/'.$quarantine->id) }}" data-id="{{$quarantine->id}}" id="delete_quarantine" onclick="return confirm('Are you sure want to delete?')"><i class="fa fa-trash-o" aria-hidden="true"></i></a></td>
    	                    </tr>
    	                @endforeach
                    </tbody>
                </table>
                <script type="text/javascript">
                    $(document).ready(function(){
                        $('#quarantines').DataTable({
                            "aaSorting": [[ 1, "asc" ]]
                        });
                        $("#delete_quarantine").click(function(){
                            if(confirm("Are you sure to delete this item?")) {
                                var id = $(this).data('id');
                                $.ajax({
                                    type:'GET',
                                    url:"{{url('/quarantines/delete')}}",
                                    data: { _token:'{{ csrf_token() }}', _method:'DELETE', id:id},
                                    success:function(response){
                                        alert(response);
                                        if(response == 1) {
                                            $('.delete_qua_ok').text('Deleted successfully.');
                                            $("#delete_qua_div").show();                                        
                                            $('.'+id).remove();
                                        } else {
                                            $('.delete_qua_error').text('Some problem occurred, please try again.');
                                            $("#delete_qua_div").show();    
                                        }
                                    }
                                });  
                            }
                        });
                        /****** END of  Delete Quarantine  *******/
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