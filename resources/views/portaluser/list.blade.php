@extends('layouts.app')
@section('title','Portal Users')
@section('content')
    @if(Auth::user()->role_id==1)
        <div class="xs">
            <h3>List of Portal Users</h3>
            <div class="bs-example4" data-example-id="contextual-table">
                <table class="table table-striped table-bordered dt-responsive nowrap" id="userList">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Username</th>
                            <th>Name</th>
                            <th>Role</th>
                            <th>Supplier</th>
                            <th>Client</th>
                            <th>Active</th>
                            <th>Last Login</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($portalusers as $key => $portaluser)
                            <tr class="active" id="user-id-{{$portaluser->id}}">
                                <th scope="row">{{$key+1}}</th>
                                <td>{{$portaluser->username}}</td>
                                <td>{{$portaluser->name}}</td>
                                <td>{{$portaluser->rname}}</td>
                                <td>{{$portaluser->sname}}</td>
                                <td>{{$portaluser->cname}}</td>
                                <td>@if($portaluser->active==1)<i class="fa fa-check" aria-hidden="true"></i>@endif</td>
                                @if($portaluser->last_login !='')
                                    <td>{{ date('dS M Y h:i:s a', strtotime($portaluser->last_login)) }}</td>
                                @else
                                    <td>&nbsp;</td>
                                @endif
                                <td><a href="{{ url('/portaluser/'.$portaluser->id.'/edit') }}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> | <a href="javascript:void(0);" onclick="destroy({{$portaluser->id}})"><i class="fa fa-trash-o" aria-hidden="true"></i></a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <script type="text/javascript">
                    $(document).ready(function(){
                        $('#userList').DataTable({
                            "aaSorting": [[ 2, "asc" ]]
                        });
                    });
                    function destroy(id) {
                        if (confirm('Are you sure you want to delete this?')) {
                            jQuery.ajax({
                                type: 'POST',
                                url: "{{ url('/portaluser') }}/"+id,
                                data: { _token:'{{ csrf_token() }}', _method:'DELETE'} , 
                                success: function(result){
                                    if(result==1){
                                        jQuery('#user-id-'+id).remove();
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