@extends('layouts.app')
@section('title','Suppliers')
@section('content')
    @if(Auth::user()->role_id==1 || Auth::user()->role_id==2)
        <div class="xs">
            <h3>List of Suppliers</h3>
            <div class="bs-example4" data-example-id="contextual-table">
                <table class="table table-striped table-bordered dt-responsive nowrap" id="suppliers">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Contact E-mail</th>
                            <th>Contact Name</th>
                            <th>Contact Phone</th>
                            <th>Portal Users</th>
                            <th>Active</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($suppliers as $key => $supplier)
                            <tr class="active" id="supplier-id-{{$supplier->id}}">
                                <th scope="row">{{$key+1}}</th>
                                <td>{{$supplier->name}}</td>
                                <td>{{$supplier->contact_email}}</td>
                                <td>{{$supplier->contact_name}}</td>
                                <td>{{$supplier->contact_phone}}</td>
                                <td>{{$supplier->PortalUser}}</td>
                                <td>@if($supplier->active)<i class="fa fa-check" aria-hidden="true"></i>@endif</td>
                                <td><a href="{{ url('/suppliers/'.$supplier->id.'/edit') }}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> | <a href="javascript:void(0);" onclick="destroy({{$supplier->id}})"><i class="fa fa-trash-o" aria-hidden="true"></i></a></td>
                            </tr>
                        @endforeach                    
                    </tbody>
                </table>
                <script type="text/javascript">
                    $(document).ready(function(){
                        $('#suppliers').DataTable({
                            "aaSorting": [[ 1, "asc" ]]
                        });
                    });
                    function destroy(id) {
                        if (confirm('Are you sure you want to delete this?')) {
                            jQuery.ajax({
                                type: 'POST',
                                url: "{{ url('/suppliers') }}/"+id,
                                data: { _token:'{{ csrf_token() }}', _method:'DELETE'} , 
                                success: function(result){
                                    if(result==1){
                                        jQuery('#supplier-id-'+id).remove();
                                    }
                                }
                            });
                        }
                    }
                </script>                        
            </div>
        </div>
    @else
        <div class="xs">
            <div  class="alert alert-danger" >You are not allowed to access this page</div>
        </div>
    @endif
@endsection