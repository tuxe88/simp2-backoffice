@extends('layouts.base', ["title"=> "Companies",
                            "sectionTitle"=>"Companies",
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
                    <div class="card-title">Current companies</div>
                </div>
                <div class="d-flex table-responsive p-3">
                    <div class="btn-group mr-2">
                        <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addModal"><i class="mdi mdi-plus-circle-outline"></i> Add new company</button>
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
                                            <th class="wd-15p sorting" tabindex="0" aria-controls="example" rowspan="1"
                                                colspan="1" aria-label="Status: activate to sort column ascending"
                                                style="width: 47px;">Status
                                            </th>
                                            <th class="wd-25p sorting" tabindex="0" aria-controls="example" rowspan="1"
                                                colspan="1" aria-label="E-mail: activate to sort column ascending"
                                                style="width: 5px;">Options
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($response["companies"] as $company)
                                                <tr role="row" class="@if ($loop->even) even @else odd @endif">
                                                    <td class="sorting_1">{{$company["name"]}}</td>
                                                    <td>
                                                        @if ( isset($company["enabled"]) && $company["enabled"]==true)
                                                            <span class="tag tag-lime">Enabled</span>
                                                        @else
                                                            <span class="tag tag-gray">Disabled</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="item-action dropdown">
                                                            <a href="javascript:void(0)" data-toggle="dropdown" class="icon"><i class="fe fe-more-vertical"></i></a>
                                                            <div class="dropdown-menu dropdown-menu-right">
                                                                <a href="javascript:editCompany(0)" class="dropdown-item"><i class="dropdown-icon fe fe-edit-2"></i> Edit </a>
                                                                {{--<a href="javascript:void(0)" class="dropdown-item"><i class="dropdown-icon fe fe-delete"></i> Delete </a>--}}
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
                    <h5 class="modal-title" id="example-Modal3">New company</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{route('companies')}}" method="post">
                        @csrf <!-- {{ csrf_field() }} -->
                        <div class="form-group">
                            <label for="recipient-name" class="form-control-label">Name:</label>
                            <input type="text" class="form-control" name="name" id="company-name">
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Create company</button>
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
                    <h5 class="modal-title" id="example-Modal3">Modify company</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('companies')}}" method="post">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}

                        @csrf <!-- {{ csrf_field() }} -->
                        <div class="form-group">
                            <label for="recipient-name" class="form-control-label">Name:</label>
                            <input type="text" class="form-control" name="name" id="company-name-modify">
                        </div>
                        <label class="custom-switch">
                            <input id="company-enable-modify" type="checkbox" name="enabled" class="custom-switch-input" checked>
                            <span class="custom-switch-indicator"></span>
                            <span class="custom-switch-description">Enabled</span>
                        </label>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Modify company</button>
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
                $("#company-name-modify").val(data[0]);
                var enabled = data[1].includes("Enabled");
                /*console.log(enabled);*/
                $("#company-enable-modify").prop("checked", enabled);
                //console.log( data );
            } );
        });

        function editCompany(a){
            console.log(a);
            $("#modifyModal").modal();
        }
    </script>
@endpush
