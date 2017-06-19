@extends('layouts.app')
@section('title','Clients')
@section('content')
    @if(Auth::user()->role_id==1 || Auth::user()->role_id==2)
        <div class="xs">
            <h3>List of Clients</h3>
            <div class="bs-example4" data-example-id="contextual-table">
                <table class="table table-striped table-bordered dt-responsive nowrap" id="clients">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Contact Email</th>
                            <th>Contact Name</th>
                            <th>Contact Phone</th>
                            <th>Portal Users</th>
                            <th>Campaigns</th>
                            <th>Active</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($clients as $key => $client)
                            <tr class="active" id="client-id-{{$client->id}}">
                                <th scope="row">{{$key+1}}</th>
                                <td>{{$client->name}}</td>
                                <td>{{$client->contact_email}}</td>
                                <td>{{$client->contact_name}}</td>
                                <td>{{$client->contact_phone}}</td>
                                <td>{{$client->PortalUser}}</td>
                                <td>{{$client->TotalCampaigns}}</td> 
                                <td>@if($client->active==1)<i class="fa fa-check" aria-hidden="true"></i>@endif</td>
                                <td><a href="{{ url('/clients/'.$client->id.'/edit') }}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> | <a href="javascript:void(0);" onclick="destroy({{$client->id}})"><i class="fa fa-trash-o" aria-hidden="true"></i></a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <script type="text/javascript">
                    $(document).ready(function(){
                        $('#clients').DataTable({
                            "aaSorting": [[ 1, "asc" ]]
                        });
                    });
                    function destroy(id) {
                        if (confirm('Are you sure you want to delete this?')) {
                            jQuery.ajax({
                                type: 'POST',
                                url: "{{ url('/clients') }}/"+id,
                                data: { _token:'{{ csrf_token() }}', _method:'DELETE'} , 
                                success: function(result){
                                    if(result==1){
                                        jQuery('#client-id-'+id).remove();
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