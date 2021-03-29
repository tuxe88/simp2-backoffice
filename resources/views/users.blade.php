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
                    <div class="card-title">Current users</div>
                </div>
                <div class="d-flex table-responsive p-3">
                    <div class="btn-group mr-2">
                        <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addModal"><i class="mdi mdi-plus-circle-outline"></i> Add new user</button>
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
                                        @foreach ($response["users"] as $user)
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

    <!-- Message Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog"  aria-hidden="true">
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
                                <label for="recipient-name" class="form-control-label">Email:</label>
                                <input type="text" class="form-control" name="email" id="user-email-create">
                            </div>
                            <div class="form-group">
                                <label class="form-label">User role</label>
                                <select name="user-role-create" id="select-roles-create" class="form-control custom-select">
                                    @foreach($response["roles"] as $role)
                                        <option value="{{$role->id}}">{{$role->name}}</option>
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
                                <button type="submit" class="btn btn-primary">Modify user</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Message Modal -->
    <div class="modal fade" id="modifyModal" tabindex="-1" role="dialog"  aria-hidden="true">
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
                        <div class="form-group">
                            <label for="recipient-name" class="form-control-label">Name:</label>
                            <input type="text" class="form-control" name="name" id="user-name-modify">
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="form-control-label">Email:</label>
                            <input type="text" class="form-control" name="email" id="user-email-modify">
                        </div>
                        <div class="form-group">
                            <label class="form-label">User role</label>
                            <select name="user-role-modify" id="select-roles" class="form-control custom-select">
                                @foreach($response["roles"] as $role)
                                    <option value="{{$role->id}}">{{$role->name}}</option>
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
            var table = $('#example').DataTable(
                {
                    "columnDefs": [ {
                        "targets": [1,2],
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
        });

        function editCompany(a){
            //console.log(a);
            $("#modifyModal").modal();
        }
    </script>
@endpush
