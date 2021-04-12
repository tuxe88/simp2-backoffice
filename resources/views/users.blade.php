@extends('layouts.base', ["title"=> "Users",
                            "sectionTitle"=>"Users",
                            "subSectionTitle"=>"",
                            "subSectionSubTitle"=>""])

@push('css')
    <link href="{{asset('plugins/datatable/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Current backoffice users</div>
                </div>
                <div class="d-flex table-responsive p-3">
                    <div class="btn-group mr-2">
                        {{--<button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addModal"><i class="mdi mdi-plus-circle-outline"></i> Add new user</button>--}}
                    </div>
                    <div class="btn-group mr-2">
                        <button type="button" class="btn btn-light" disabled><i class="mdi mdi-printer"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <div id="example_wrapper">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="example" class="table table-striped table-bordered dataTable no-footer"
                                           style="width: 100%;" role="grid" aria-describedby="example_info">
                                        <thead>
                                        <tr role="row">
                                            <th class="wd-15p sorting" tabindex="0" aria-controls="example"
                                                rowspan="1" colspan="1" aria-sort="ascending"
                                                aria-label="Name: activate to sort column descending"
                                                style="width: 47px;">Name
                                            </th>
                                            <th class="wd-15p sorting" tabindex="0" aria-controls="example"
                                                rowspan="1" colspan="1" aria-sort="ascending"
                                                aria-label="Email: activate to sort column descending"
                                                style="width: 47px;">Email
                                            </th>
                                            <th class="wd-15p sorting" tabindex="0" aria-controls="example" rowspan="1"
                                                colspan="1" aria-label="Status: activate to sort column ascending"
                                                style="width: 47px;">Status
                                            </th>
                                            <th class="wd-15p sorting" tabindex="0" aria-controls="example" rowspan="1"
                                                colspan="1" aria-label="Role: activate to sort column ascending"
                                                style="width: 47px;">Role
                                            </th>
                                            <th class="wd-15p sorting" tabindex="0" aria-controls="example"
                                                rowspan="1" colspan="1" aria-sort="ascending"
                                                aria-label="Creation date: activate to sort column descending"
                                                style="width: 47px;">Creation date
                                            </th>

                                            <th class="wd-25p sorting" tabindex="0" aria-controls="example" rowspan="1"
                                                colspan="1" aria-label="Options: activate to sort column ascending"
                                                style="width: 5px;">
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($response["backoffice_users"] as $user)
                                                <tr role="row" class="@if ($loop->even) even @else odd @endif">
                                                    <td class="sorting_1">{{$user["name"]}}</td>
                                                    <td>{{$user["email"]}}</td>
                                                    <td>
                                                        @if ( isset($user["enabled"]) && $user["enabled"]==true)
                                                            <span class="tag tag-lime">Enabled</span>
                                                        @else
                                                            <span class="tag tag-gray">Disabled</span>
                                                        @endif
                                                    </td>
                                                    <td>{{$user->getRoleNames()[0]}}</td>
                                                    <td>{{$user["created_at"]->format('j F, Y')}}</td>
                                                    <td>
                                                        <div class="item-action dropdown">
                                                            <a href="javascript:void(0)" data-toggle="dropdown" class="icon"><i class="fe fe-more-vertical"></i></a>
                                                            <div class="dropdown-menu dropdown-menu-right">
                                                                <a href="javascript:editCompany(0)" class="dropdown-item"><i class="dropdown-icon fe fe-edit-2"></i> Edit </a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- table-wrapper -->
            </div>
            <!-- section-wrapper -->

        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Current api clients</div>
                </div>
                <div class="d-flex table-responsive p-3">
                    <div class="btn-group mr-2">
                        <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addModalApiClients"><i class="mdi mdi-plus-circle-outline"></i> Add new api client</button>
                    </div>
                    <div class="btn-group mr-2">
                        <button type="button" class="btn btn-light" disabled><i class="mdi mdi-printer"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <div id="example_wrapper">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="example-api-clients" class="table table-striped table-bordered dataTable no-footer"
                                           style="width: 100%;" role="grid" aria-describedby="example_info">
                                        <thead>
                                        <tr role="row">
                                            <th hidden class="wd-15p sorting" tabindex="0" aria-controls="example"
                                                rowspan="1" colspan="1" aria-sort="ascending"
                                                aria-label="Name: activate to sort column descending"
                                                style="width: 15px;">id
                                            </th>
                                            <th class="wd-15p sorting" tabindex="0" aria-controls="example"
                                                rowspan="1" colspan="1" aria-sort="ascending"
                                                aria-label="Name: activate to sort column descending"
                                                style="width: 47px;">Name
                                            </th>
                                            <th class="wd-15p sorting" tabindex="0" aria-controls="example" rowspan="1"
                                                colspan="1" aria-label="Status: activate to sort column ascending"
                                                style="width: 47px;">Api key
                                            </th>
                                            <th class="wd-15p sorting" tabindex="0" aria-controls="example" rowspan="1"
                                                colspan="1" aria-label="Role: activate to sort column ascending"
                                                style="width: 47px;">Enabled
                                            </th>
                                            {{--<th class="wd-15p sorting" tabindex="0" aria-controls="example"
                                                rowspan="1" colspan="1" aria-sort="ascending"
                                                aria-label="Creation date: activate to sort column descending"
                                                style="width: 47px;">Ip list
                                            </th>
                                            <th class="wd-15p sorting" tabindex="0" aria-controls="example"
                                                rowspan="1" colspan="1" aria-sort="ascending"
                                                aria-label="Creation date: activate to sort column descending"
                                                style="width: 47px;">Role
                                            </th>
                                            <th class="wd-15p sorting" tabindex="0" aria-controls="example"
                                                rowspan="1" colspan="1" aria-sort="ascending"
                                                aria-label="Creation date: activate to sort column descending"
                                                style="width: 47px;">Id
                                            </th>--}}
                                            <th class="wd-15p sorting" tabindex="0" aria-controls="example"
                                                rowspan="1" colspan="1" aria-sort="ascending"
                                                aria-label="Creation date: activate to sort column descending"
                                                style="width: 5px;">Company
                                            </th>
                                            <th class="wd-25p sorting" tabindex="0" aria-controls="example" rowspan="1"
                                                colspan="1" aria-label="Options: activate to sort column ascending"
                                                style="width: 5px;">
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($response["api_clients"] as $user)
                                            <tr role="row" class="@if ($loop->even) even @else odd @endif">
                                                <td hidden class="sorting_1">
                                                    @foreach ($user["_id"] as $id)
                                                        {{ trim($id) }}
                                                    @endforeach
                                                </td>
                                                <td class="sorting_1">{{$user["name"]}} {{$user["last_name"]}}</td>
                                                <td>{{$user["api_key"]}}</td>
                                                <td>
                                                    @if ( isset($user["enabled"]) && $user["enabled"]==true)
                                                        <span class="tag tag-lime">Enabled</span>
                                                    @else
                                                        <span class="tag tag-gray">Disabled</span>
                                                    @endif
                                                </td>
                                                {{--<td>{{$user["ip_list"]}}</td>--}}
                                                {{--<td>{{$user["role"]}}</td>--}}
                                                {{--<td>{{$user["id"]}}</td>--}}

                                                <td>{{$user["company"][0]["name"]}}</td>
                                                {{--<td>{{$user->getRoleNames()[0]}}</td>
                                                <td>{{$user["created_at"]->format('j F, Y')}}</td>--}}
                                                <td>
                                                    <div class="item-action dropdown">
                                                        <a href="javascript:void(0)" data-toggle="dropdown" class="icon"><i class="fe fe-more-vertical"></i></a>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <a href="javascript:editApiClient(0)" class="dropdown-item"><i class="dropdown-icon fe fe-edit-2"></i> Edit </a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- table-wrapper -->
            </div>
            <!-- section-wrapper -->

        </div>
    </div>


    <!-- Message Modal -->
    <div class="modal fade" id="addModalApiClients" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="example-Modal3">New user</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{route('users')}}" method="post">
                        @csrf <!-- {{ csrf_field() }} -->
                            <div class="form-group">
                                <label for="recipient-name" class="form-control-label">Name:</label>
                                <input type="text" class="form-control" name="name" id="user-name-create">
                            </div>
                            <div class="form-group">
                                <label for="recipient-last-name" class="form-control-label">Last name:</label>
                                <input type="text" class="form-control" name="last_name" id="user-last-name-create">
                            </div>
                            <div class="form-group">
                                <label class="form-label">User role</label>
                                <select name="user_role_create" id="select-roles-create" class="form-control custom-select">
                                    <option value="admin">admin</option>
                                    <option value="user">user</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Company</label>
                                <select name="user_company_create" id="select-company-create" class="form-control custom-select">
                                    @foreach($response["companies"] as $company )
                                        @isset($company["unique_id"])
                                            <option value="{{$company["unique_id"]}}">{{$company["name"]}}</option>
                                        @endisset
                                    @endforeach
                                </select>
                            </div>
                            <label class="custom-switch">
                                <input id="user-enable-modify" type="checkbox" name="enabled" class="custom-switch-input" checked>
                                <span class="custom-switch-indicator"></span>
                                <span class="custom-switch-description">Enabled</span>
                            </label>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Create user</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Message Modal -->
    <div class="modal fade" id="modifyModalApiClients" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="example-Modal3">Modify user</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('users')}}" method="post">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}

                        @csrf <!-- {{ csrf_field() }} -->
                        <input type="text" class="form-control" hidden name="id-user-apiclient-modify" id="id-user-apiclient-modify">
                        <label class="custom-switch">
                            <input id="user-api-client-enable-modify" type="checkbox" name="enabled" class="custom-switch-input" checked>
                            <span class="custom-switch-indicator"></span>
                            <span class="custom-switch-description">Enabled</span>
                        </label>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Modify user</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{asset('plugins/datatable/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/datatable/dataTables.bootstrap4.min.js')}}"></script>

    <script>
        $(document).ready(function() {
            /*Backoffice table initialization*/
            var table = $('#example').DataTable(
                {
                    "columnDefs": [ {
                        "targets": [2,4],
                        "orderable": false
                    } ]
                }
            )

            $('#example tbody').on( 'click', 'a', function () {
                var data = table.row( $(this).parents('tr') ).data();
                $("#user-name-modify").val(data[0]);

                $("#user-email-modify").val(data[1]);

                var enabled = data[2].includes("Enabled");
                $("#user-enable-modify").prop("checked", enabled);

                var role = data[3]
                //console.log(role);
                $('#select-roles option[value="'+role+'"]');

            } );

            /*Api clients table*/
            var table_api_clients = $('#example-api-clients').DataTable(
                {
                    "columnDefs": [ {
                        "targets": [2],
                        "orderable": false
                    } ]
                }
            );

            $('#example-api-clients tbody').on( 'click', 'a', function () {
                var data = table_api_clients.row( $(this).parents('tr') ).data();
                console.log(data);
                $("#id-user-apiclient-modify").val(data[0]);

                var enabled = data[3].includes("Enabled");
                $("#user-api-client-enable-modify").prop("checked", enabled);
                console.log(enabled);
                //var role = data[3]
                //console.log(role);
                //$('#select-roles option[value="'+role+'"]');

            } );

        });


        function editCompany(a){
            //console.log(a);
            $("#modifyModal").modal();
        }

        function editApiClient(a){
            //console.log(a);
            $("#modifyModalApiClients").modal();
        }

    </script>
@endpush
