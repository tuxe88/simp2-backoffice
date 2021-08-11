@extends('layouts.base', ["title"=> "Debts",
                            "sectionTitle"=>"Debts",
                            "subSectionTitle"=>"",
                            "subSectionSubTitle"=>""])

@push('css')
    <link href="{{asset('plugins/datatable/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
    <link href="{{asset('plugins/select2/select2.min.css')}}" rel="stylesheet" />
    <link href="{{asset('plugins/date-picker/spectrum.css')}}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.4.2/css/buttons.dataTables.min.css">
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12 col-lg-12">
            @if(Auth::user()->api_key == null)
                <div class="alert alert-warning" role="alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    In order to operate with debts you must have an api_key assigned to the user</div>
            @endif
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Current debts</div>
                </div>
                <div class="d-flex table-responsive p-3">
                    <div class="btn-group mr-2">
                        <button @if(Auth::user()->api_key == null) disabled @endif class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addModal"><i class="mdi mdi-plus-circle-outline"></i> Add new debt</button>
                    </div>
                    {{--<div class="btn-group mr-2">
                        <button @if(Auth::user()->api_key == null) disabled @endif class="btn btn-sm btn-green"><i class="mdi mdi-file-excel"></i> Import XLS file</button>
                    </div>--}}
                    {{--<div class="btn-group mr-2">
                        <button type="button" class="btn btn-light"><i class="mdi mdi-download"></i></button>
                    </div>--}}
                </div>
                @if(Auth::user()->api_key != null)

                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Filters</div>
                        </div>
                        <form action="{{route('debts')}}" method="post">
                            {{ csrf_field() }}
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <label class="form-label">From date</label>
                                            <input class="form-control fc-datepicker hasDatepicker" placeholder="MM/DD/YYYY" type="text" id="from-date-filter" name="from-date-filter">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <label class="form-label">To date</label>
                                            <input class="form-control fc-datepicker hasDatepicker" placeholder="MM/DD/YYYY" type="text" id="to-date-filter" name="to-date-filter">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6 mt-6">
                                        <div class="btn-group mr-2">
                                            <button type="button" onclick="updateTable()" class="btn btn-sm btn-primary"><i class="fa fa-filter"></i> Apply filters</button>
                                        </div>
                                        {{--<div class="btn-group mr-2">
                                            <button type="button" onclick="updateTable()" class="btn btn-sm btn-primary"><i class="fa fa-file-excel-o"></i> Export Excel</button>
                                        </div>
                                        <div class="btn-group mr-2">
                                            <button type="button" onclick="updateTable()" class="btn btn-sm btn-primary"><i class="fa fa-file-text"></i> Export CSV</button>
                                        </div>--}}
                                    </div>
                                    {{--<div class="col-md-3 col-lg-3">
                                        <div class="form-group">
                                            <label class="form-label">Code</label>
                                            <input type="text" class="form-control" id="code-filter" name="code-filter" placeholder="Text..">
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-lg-3">
                                        <div class="form-group">
                                            <label class="form-label">Client id</label>
                                            <input type="text" class="form-control" id="client-id-filter" name="client-id-filter" placeholder="Text..">
                                        </div>
                                    </div>--}}

                                </div>
                            </div>
                        </form>
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
                                            <th style="display: none;"> status raw </th>
                                            <th class="wd-15p sorting" tabindex="0" aria-controls="example"
                                                rowspan="1" colspan="1" aria-sort="ascending"
                                                aria-label="Name: activate to sort column descending"
                                                style="width: 47px;">Code
                                            </th>
                                            <th class="wd-15p sorting hide-row-992" tabindex="0" aria-controls="example"
                                                rowspan="1" colspan="1" aria-sort="ascending"
                                                aria-label="Name: activate to sort column descending"
                                                style="width: 47px;">client id
                                            </th>
                                            <th class="wd-15p sorting hide-row-768" tabindex="0" aria-controls="example" rowspan="1"
                                                colspan="1" aria-label="Status: activate to sort column ascending"
                                                style="width: 47px;">Expiration
                                            </th>
                                            <th class="wd-15p sorting" tabindex="0" aria-controls="example" rowspan="1"
                                                colspan="1" aria-label="Status: activate to sort column ascending"
                                                style="width: 47px;">Status
                                            </th>
                                            <th class="wd-15p sorting" tabindex="0" aria-controls="example" rowspan="1"
                                                colspan="1" aria-label="CompanyTransactionToken: activate to sort column ascending"
                                                style="width: 47px;">amount
                                            </th>
                                            <th class="wd-25p sorting" tabindex="0" aria-controls="example" rowspan="1"
                                                colspan="1" aria-label="E-mail: activate to sort column ascending"
                                                style="width: 5px;">Options
                                            </th>
                                        </tr>
                                        </thead>
                                        {{--<tbody>
                                        @forelse ($response["payments"] as $debt)
                                                <tr role="row" class="@if ($loop->even) even @else odd @endif">
                                                    <td style="display: none">
                                                        {{json_encode($debt)}}
                                                    </td>
                                                    <td style="display: none">
                                                        {{$debt["barcode"]}}
                                                    </td>
                                                    <td style="display: none">
                                                        {{$debt["unique_reference"]}}
                                                    </td>
                                                    <td data-toggle="tooltip" data-popup="tooltip-custom" data-original-title="@isset($debt["debt"]["code"]){{$debt["debt"]["code"]}} @endisset" class="sorting_1">
                                                          {{Str::limit($debt["unique_reference"],$limit = 20, $end = "...")}}
                                                        --}}{{--<a href="#" onclick="copyToClipboard({{$debt["unique_reference"]}})" class="btn btn-link">Copy</a>--}}{{--
                                                    </td>
                                                    <td class="sorting_1 hide-row-992" >
                                                        {{Str::limit($debt["client_origin_id"],$limit = 15, $end = "...")}}
                                                        --}}{{--<a href="#" onclick="copyToClipboard({{$debt["barcode"]}})" class="btn btn-link">Copy</a>--}}{{--
                                                    </td>
                                                    <td class="hide-row-768">
                                                        @if ( isset($debt["expired"]) && $debt["expired"]==true)
                                                            <span class="tag tag-danger">Expired</span>
                                                        @else
                                                            <span class="tag tag-cyan">Non expired</span>
                                                        @endif
                                                        <div class="small text-muted text-gray-dark">Due date: {{$debt["due_date"]->toDateTime()->format('Y-m-d')}}</div>
                                                    </td>
                                                    @switch($debt["status"])
                                                        @case("pending_payment")
                                                            <td class="sorting_1"><span class="badge badge-secondary">Pending payment</span></td>
                                                            @break
                                                        @case("payment_notified")
                                                            <td class="sorting_1"><span class="badge badge-primary">Payment notified</span></td>
                                                            @break
                                                        @case("payment_confirmed")
                                                            <td class="sorting_1"><span class="badge badge-success">Payment confirmed</span>
                                                                <div class="small text-muted text-gray-dark">At: {{$debt["confirmed_at"]->toDateTime()->format('Y-m-d H:i:s')}}</div>
                                                            </td>

                                                            @break
                                                        @case("rollback_notified")
                                                            <td class="sorting_1"><span class="badge badge-warning">Rollback notified</span></td>
                                                            @break
                                                        @case("rollback_confirmed")
                                                            <td class="sorting_1"><span class="badge badge-default">Rollback confirmed</span></td>
                                                            @break
                                                        @default
                                                            <td class="sorting_1"><span class="badge badge-danger">No status defined</span></td>

                                                    @endswitch
                                                    <td class="sorting_1">{{$debt["amount"]}}</td>
                                                    <td>
                                                        @if(Auth::user()->api_key != null)
                                                            <div class="item-action dropdown">
                                                                <a href="javascript:void(0)" data-toggle="dropdown" class="icon"><i class="fe fe-more-vertical"></i></a>
                                                                <div class="dropdown-menu dropdown-menu-right">
                                                                    <a href="javascript:debtDetails(0)" class="dropdown-item"><i class="dropdown-icon fe fe-book-open"></i> Details </a>
                                                                    @if($debt["status"]=="pending_payment")
                                                                        <a href="javascript:deleteDebt(0)" class="dropdown-item"><i class="dropdown-icon fe fe-delete"></i> Delete </a>
                                                                    @endif
                                                                    --}}{{--<a href="javascript:void(0)" class="dropdown-item"><i class="dropdown-icon fe fe-delete"></i> Delete </a>--}}{{--
                                                                </div>
                                                            </div>
                                                        @else
                                                            <i class="fe fe-x" data-toggle="tooltip"></i>
                                                        @endif
                                                    </td>
                                                </tr>
                                        @empty
                                        @endforelse
                                        </tbody>--}}
                                    </table>
                                    @endif
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
    <div class="modal fade" id="detailsModal" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="card">
                        <div class="text-center pricing pricing1 ">
                            <div class="card-category bg-cyan" id="statusDetail"></div>
                            <ul class="list-unstyled leading-loose">
                                <strong>Barcode</strong><li id="barcodeDetail"></li>
                                <strong>Unique reference</strong><li id="uniqueReferenceDetail"></li>
                                <strong>
                                    Debt history
                                </strong>
                                <ul id="listHistoryDetail" class="list-group">
                                </ul>
                            </ul>
                        </div>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
               {{-- <div class="modal-body">

                </div>--}}
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="deleteDebtModal" tabindex="-1" role="dialog" aria-labelledby="deleteDebtModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete debt</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{route('debts')}}" method="post">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}
                <div class="modal-body">
                    <p>Are you sure you want to delete the selected debt?</p>
                    <input hidden type="text" name="delete-payment-debt-unique-reference" id="delete-payment-debt-unique-reference">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Message Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="example-Modal3">New debt</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{route('debts')}}" method="post">
                    @csrf <!-- {{ csrf_field() }} -->
                        <div style="border-bottom: 1px  rgba(255,255,255,0.66);">
                            <h5 style="font-weight: 600;" class="modal-title" id="example-Modal3">Debt entry point data</h5>
                        </div>
                        <br>
                        <div class="row">
                            <br>
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label for="recipient-code" class="form-control-label">Code:</label>
                                    <input type="text" class="form-control" name="new-code" id="new-code">
                                </div>
                                <div class="form-group">
                                    <label for="recipient-ccf-code" class="form-control-label">CCF Code:</label>
                                    <input type="text" class="form-control" name="new-ccf-code" id="new-ccf-code">
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label for="recipient-ccf-client-id" class="form-control-label">CCF client id:</label>
                                    <input type="text" class="form-control" name="new-ccf-client-id" id="new-ccf-client-id">
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-12">
                                <div class="form-group">
                                    <label for="recipient-ccf-extra" class="form-control-label">CCF Extra:</label>
                                    <textarea class="form-control" name="new-ccf-extra" id="new-ccf-extra" rows="3" placeholder="Write here if you need some extra information .."></textarea>
                                </div>
                            </div>
                        </div>

                        <div style="border-bottom: 1px  rgba(255,255,255,0.66);">
                            <h5 style="font-weight: 600;" class="modal-title" id="example-Modal3">Client information</h5>
                        </div>
                        <br>
                        <div class="row">
                            <br>
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label for="recipient-first-name" class="form-control-label">First name:</label>
                                    <input type="text" class="form-control" name="new-first-name" id="new-first-name">
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6">
                                <div>
                                    <label for="recipient-last-name" class="form-control-label">Last name:</label>
                                    <input type="text" class="form-control" name="new-last-name" id="new-last-name">
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-12">
                                <div class="form-group">
                                    <label for="recipient-extra" class="form-control-label">Extra:</label>
                                    <textarea class="form-control" name="new-extra" id="new-extra" rows="3" placeholder="Write here if you need some extra information .."></textarea>
                                </div>
                            </div>
                        </div>

                        <div style="border-bottom: 1px  rgba(255,255,255,0.66);">
                            <h5 style="font-weight: 600;" class="modal-title" id="example-Modal3">Payment Companies</h5>
                        </div>
                        <br>
                        <div class="row">
                            <br>
                            <div class="col-md-12 col-lg-12">
                                @foreach($response["payment_methods"] as $key=>$pm)
                                    <div class="form-group m-0">
                                        <div class="form-group">
                                            <label class="custom-switch">
                                                <input type="checkbox" name="new-payment-switch-{{$pm["name"]}}" id="new-payment-switch-{{$pm["name"]}}" class="custom-switch-input payment-company-enable">
                                                <span class="custom-switch-indicator"></span>
                                                <span class="custom-switch-description">{{$pm["name"]}}</span>
                                            </label>
                                        </div>

                                        <div id="{{$pm}}-submethods" style="display: none;">
                                            <div class="form-group ">
                                                {{--<label class="form-label">Allowed payment methods</label>--}}
                                                <div class="selectgroup selectgroup-pills">
                                                    <label class="selectgroup-item">
                                                        <input type="checkbox" name="{{$pm}}-cash" id="{{$pm}}-cash" value="cash" class="selectgroup-input" >
                                                        <span class="selectgroup-button">Cash</span>
                                                    </label>
                                                    <label class="selectgroup-item">
                                                        <input type="checkbox" name="{{$pm}}-credit" id="{{$pm}}-credit" value="credit_card" class="selectgroup-input">
                                                        <span class="selectgroup-button">Credit Card</span>
                                                    </label>
                                                    <label class="selectgroup-item">
                                                        <input type="checkbox" name="{{$pm}}-debit" id="{{$pm}}-debit" value="debit_card" class="selectgroup-input">
                                                        <span class="selectgroup-button">Debit Card</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                <div style="border-bottom: 1px  rgba(255,255,255,0.66);">
                                    <h5 style="font-weight: 600;" class="modal-title" id="example-Modal3">
                                        Debts
                                        <button type="button" onclick="openDebtForm()" id="btn-add-subdebt" class="btn btn-primary ml-2"><i class="fe fe-plus"></i></button>
                                        <button type="button" onclick="closeDebtForm()" id="btn-remove-subdebt" class="btn btn-danger ml-2"><i class="fa fa-close"></i></button>
                                    </h5>
                                </div>
                                <br>
                                <div class="form-group p-2" id="div-add-subdebt" style="display: none; border: grey 1px solid; border-radius: 3px;">
                                    <div class="col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <label for="recipient-code" class="form-control-label">Unique reference:</label>
                                            <input type="text" class="form-control" name="new-unique-reference" id="new-unique-reference">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <label for="recipient-code" class="form-control-label">amount:</label>
                                            <input type="text" class="form-control" name="new-amount" id="new-amount">
                                        </div>
                                        <div class="wd-200 mg-b-30">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <i class="fa fa-calendar tx-16 lh-0 op-6"></i>
                                                    </div>
                                                </div><input class="form-control fc-datepicker hasDatepicker" placeholder="DD/MM/YYYY" type="text" name="new-debt-due-date" id="new-debt-due-date">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="recipient-ccf-extra" class="form-control-label">Texts:</label>
                                            <textarea class="form-control" name="new-subdebt-text" id="new-subdebt-text" rows="3" placeholder="Write here if you need some extra information .."></textarea>
                                        </div>
                                        <button type="button" onclick="saveSubdebt()" id="btn-remove-subdebt" class="btn btn-success mr-2"><i class="fe fe-check">Add debt</i></button>
                                    </div>
                                </div>

                                <div class="col-md-12 col-lg-12">
                                    <div>
                                        <div class="form-group">
                                            {{--<label class="form-label">Allowed payment methods</label>--}}
                                            <div class="selectgroup selectgroup-pills"  id="div-subdebts">
                                                {{--<label class="selectgroup-item">
                                                    <input type="checkbox" name="{{$pm}}-cash" id="{{$pm}}-cash" value="cash" class="selectgroup-input" >
                                                    <span class="selectgroup-button">Cash</span>
                                                </label>
                                                <label class="selectgroup-item">
                                                    <input type="checkbox" name="{{$pm}}-credit" id="{{$pm}}-credit" value="credit_card" class="selectgroup-input">
                                                    <span class="selectgroup-button">Credit Card</span>
                                                </label>
                                                <label class="selectgroup-item">
                                                    <input type="checkbox" name="{{$pm}}-debit" id="{{$pm}}-debit" value="debit_card" class="selectgroup-input">
                                                    <span class="selectgroup-button">Debit Card</span>
                                                </label>--}}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button id="btn-create-debt" type="submit" class="btn btn-primary">Create debt</button>
                            </div>
                        {{--</div>--}}
                    </form>
                </div>
            </div>
        </div>
    </div>


@endsection

@push('scripts')
    <script src="{{asset('plugins/datatable/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/datatable/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('plugins/select2/select2/js/select2.min.js')}}"></script>
    <script src="{{asset('plugins/date-picker/spectrum.js')}}"></script>
    <script src="{{asset('plugins/date-picker/jquery-ui.js')}}"></script>
    <script src="{{asset('plugins/input-mask/jquery.maskedinput.js')}}"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.4.2/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.4.2/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.4.2/js/buttons.print.min.js"></script>


    <script>
        $(document).ready(function() {

            var fromDate = null;
            var toDate = null;

            $("#from-date-filter").on("change", function() {
                fromDate = $("#from-date-filter").val();
            });

            $("#to-date-filter").on("change", function() {
                toDate = $("#to-date-filter").val();
            });

            var table = $('#example').DataTable(
                {
                    'processing':true,
                    'serverSide':true,
                    'pagingType':'simple_numbers',
                    'ajax':{
                        'url':'{{route('ajax_table')}}',
                        data:function(dtParms){
                            dtParms.fromDate = fromDate;
                            dtParms.toDate = toDate;
                            return dtParms
                        }
                    },
                    dom: 'lBfrtip',
                    buttons: [
                        {
                            extend:'copy',
                            exportOptions: {
                                columns: [1,2,3,4,5]
                            }
                        },
                        {
                            extend: 'csv',
                            exportOptions: {
                                columns: [1,2,3,4,5]
                            }
                        } ,
                        {
                            extend: 'excel',
                            exportOptions: {
                                columns: [1,2,3,4,5]
                            }
                        },
                        {
                            extend: 'pdf',
                            exportOptions: {
                                columns: [1,2,3,4,5]
                            }
                        },
                        {
                            extend:'print',
                            exportOptions: {
                                columns: [1,2,3,4,5]
                            }
                        }
                    ],
                    exportOptions: {
                        columns:'visible'
                    },
                    lengthMenu: [
                        [ 10, 20, 50,100, -1],
                        [ '10', '20', '50','100', 'Show all' ]
                    ],
                    aoColumnDefs:[
                        {
                            "targets": [ 0 ],
                            "visible": false,
                            "searchable": false
                        }
                    ],
                    aoColumns:[
                        {mData:'raw_json'},
                        {mData:'code'},
                        {mData:'client_id'},
                        {mData:'expiration'},
                        {mData:'status'},
                        {mData: 'amount'},
                        {mData: 'options'}
                    ],
                    "createdRow": function ( row, data, index ) {
                        //console.log(row);

                        //add expiration date details
                        if(data["raw_json"]["expired"]){
                            $('td', row).eq(2).css("color",'red').wrapInner("<strong />");
                        }


                        //add status colors
                        switch (data["status"]){
                            case "pending_payment":
                                $('td', row).eq(3).css("color",'darkOrange').wrapInner("<strong />");
                                break;
                            case "payment_notified":
                                $('td', row).eq(3).css("color",'steelBlue').wrapInner("<strong />");
                                break;
                            case "payment_confirmed":
                                //console.log(data["raw_json"]["confirmed_at"]["$date"]["$numberLong"]);
                                $('td', row).eq(3).css("color",'seaGreen').wrapInner("<strong />")
                                    .append('<p>'+getDatetimeFromTimestamp(data["raw_json"]["confirmed_at"])+'</p>');
                                break;
                            case "rollback_notified":
                                $('td', row).eq(3).css("color",'mediumPurple').wrapInner("<strong />");
                                break;
                            case "rollback_confirmed":
                                $('td', row).eq(3).css("color",'darkGray').wrapInner("<strong />");
                                break;
                        }
                        var deleteOption =   data["status"] == 'pending_payment' ? '<a href="javascript:deleteDebt(0)" class="dropdown-item"><i class="dropdown-icon fe fe-delete"></i> Delete </a>' : "";
                        $('td', row).eq(5).html('<div class="item-action dropdown"> <a href="javascript:void(0)" data-toggle="dropdown" class="icon"><i class="fe fe-more-vertical"></i></a> <div class="dropdown-menu dropdown-menu-right"> <a href="javascript:debtDetails(0)" class="dropdown-item"><i class="dropdown-icon fe fe-book-open"></i> Details </a> '+deleteOption+' </div> </div>')
                    }
                }
            )

            //TODO
            /*table.button(0).action( function (e, dt, button, config) {
                table.buttons.info('Generating export', 'it may take a few seconds. Please wait for download ...', 2000);
                $('div.dataTables_length  option[value="-1"]')
                //setTimeout(function() { $.fn.dataTable.ext.buttons.copy.action(e, dt, button, config) }, 1000 );
            });*/

            $('#example tbody').on( 'click', 'a', function () {
                var data = table.row( $(this).parents('tr') ).data();
                var json = data["raw_json"];

                //card-category bg-cyan
                switch (json["status"]){
                    case "pending_payment":
                        //$('#statusDetail').parent().class('card-category bg-green');
                        //console.log("cambiando a green");
                        break
                    case "payment_confirmed":
                        break;
                    case "rollback_notified":
                        break;
                    case "rollback_confirmed":
                        break;
                    default:
                        break;
                }


                $('#statusDetail').text(json["status"].replace('_',' '));
                $('#barcodeDetail').text(json["barcode"]);
                $('#uniqueReferenceDetail').text(json["unique_reference"]);

                $('#delete-payment-debt-unique-reference').val(json["unique_reference"]);

                var list = $('#listHistoryDetail');
                list.empty();
                //var created_date = new Date(parseInt(json["created_at"]["$date"]["$numberlong"])*1000);
                //console.log(parseInt(json["created_at"]["$date"]["$numberLong"]), created_date);
                list.append('<li class="list-group-item list-group-item-secondary">Debt created at '+getDatetimeFromTimestamp(json["created_at"])+'</li>');
                if(json.hasOwnProperty('notified_at')){
                    list.append('<li class="list-group-item list-group-item-primary">Debt notified at '+getDatetimeFromTimestamp(json["notified_at"])+'</li>');
                }
                if(json.hasOwnProperty('confirmed_at')){
                    list.append('<li class="list-group-item list-group-item-success">Debt confirmed at '+getDatetimeFromTimestamp(json["confirmed_at"])+'</li>');
                }
                if(json.hasOwnProperty('rollback_notified_at')){
                    list.append('<li class="list-group-item list-group-item-warning">Debt rollback notified at '+getDatetimeFromTimestamp(json["rollback_notified_at"])+'</li>');
                }
                if(json.hasOwnProperty('rollback_confirmed_at')){
                    list.append('<li class="list-group-item list-group-item-success">Debt rollback confirmed at '+getDatetimeFromTimestamp(json["rollback_confirmed_at"])+'</li>');
                }

            } );



            $('.payment-company-enable').change(function(){
                var medioPago = this.id.replace('new-payment-switch-','');
                if(this.checked){
                    $('#'+medioPago+'-submethods').show();
                }else{
                    $('#'+medioPago+'-submethods').hide();
                }
            });



        });
        function openDebtForm() {
            $('#new-unique-reference').prop('disabled',false);
            $('#new-amount').prop('disabled',false);
            $('#new-debt-due-date').prop('disabled',false);
            $('#new-subdebt-text').prop('disabled',false);
            $('#div-add-subdebt').show();
            $('#btn-add-subdebt').hide();
            $('#btn-remove-subdebt').show();
        }

        function closeDebtForm() {
            $('#new-unique-reference').prop('disabled',true);
            $('#new-amount').prop('disabled',true);
            $('#new-debt-due-date').prop('disabled',true);
            $('#new-subdebt-text').prop('disabled',true);
            $('#new-unique-reference').val('');
            $('#new-amount').val('');
            $('#new-debt-due-date').val('');
            $('#new-subdebt-text').val('');
            $('#div-add-subdebt').hide();
            $('#btn-add-subdebt').show();
            $('#btn-remove-subdebt').hide();
        }

        function saveSubdebt() {

            var unique_ref = $('#new-unique-reference').val();
            var amount = $('#new-amount').val();
            var due_date = $('#new-debt-due-date').val();
            var text = $('#new-subdebt-text').val();
            var arr_value = {
                "unique_reference":unique_ref,
                "amount":amount,
                "due_date":due_date,
                //"text":text
            };
            var json_array = JSON.stringify(arr_value);

            $('#div-subdebts').append(
                "<label class='selectgroup-item'>" +
                    "<input type='checkbox' name='new-subdebt-"+unique_ref+"' id='new-subdebt-"+unique_ref+"' value="+json_array+" class='selectgroup-input' checked>"+
                    "<span class='selectgroup-button'>"+unique_ref+"</span>"+
                "</label>"
            );
            closeDebtForm();
        }



        function editDebt(a){
            //console.log(a);
            $("#modifyModal").modal();
        }

        function debtDetails(a){
            $("#detailsModal").modal();
        }

        function deleteDebt(a){
            //console.log(a);
            $("#deleteDebtModal").modal();
        }

        function copyToClipboard(copying){
            document.execCommand(copying);
        }

        function getDatetimeFromTimestamp(mongoDate){
            var date = new Date(parseInt(mongoDate["$date"]["$numberLong"]));
            var days = date.getDay().toString().length > 1 ? date.getDay() : '0'+date.getDay().toString();
            var month = date.getMonth().toString().length > 1 ? date.getMonth() : '0'+date.getMonth().toString();
            var year = date.getFullYear();

            var hours = date.getHours().toString().length > 1 ? date.getHours() : '0'+date.getHours().toString();
            var minutes = "0" + date.getMinutes();
            var seconds = "0" + date.getSeconds();

            return year+'-'+month+'-'+days+' '+ hours + ':' + minutes.substr(-2) + ':' + seconds.substr(-2);
        }

        function updateTable(){
            $('#example').DataTable().ajax.reload();
        }
    </script>
@endpush
