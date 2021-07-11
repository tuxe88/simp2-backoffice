@extends('layouts.base', ["title"=> "Reverses",
                            "sectionTitle"=>"Reverses",
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
                    <div class="card-title">Current reverses</div>
                </div>
                <div class="d-flex table-responsive p-3">
                    <div class="btn-group mr-2">
                        {{--<button @if(Auth::user()->api_key == null) disabled @endif class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addModal"><i class="mdi mdi-plus-circle-outline"></i> Add new debt</button>--}}
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
                                            <th style="display: none;"> status raw </th>
                                            <th style="display: none;"> barcode </th>
                                            <th style="display: none;"> unique </th>
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
                                        <tbody>
                                        @forelse ($response["reverses"] as $debt)
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
                                                    {{--<a href="#" onclick="copyToClipboard({{$debt["unique_reference"]}})" class="btn btn-link">Copy</a>--}}
                                                </td>
                                                <td class="sorting_1 hide-row-992" >
                                                    {{Str::limit($debt["client_origin_id"],$limit = 15, $end = "...")}}
                                                    {{--<a href="#" onclick="copyToClipboard({{$debt["barcode"]}})" class="btn btn-link">Copy</a>--}}
                                                </td>
                                                <td class="hide-row-768">
                                                    @if ( isset($debt["expired"]) && $debt["expired"]==true)
                                                        <div style="color:red;"><strong>{{$debt["due_date"]->toDateTime()->format('Y-m-d H:i:s')}}</strong></div>
                                                    @else
                                                        <div><strong>{{$debt["due_date"]->toDateTime()->format('Y-m-d H:i:s')}}</strong></div>
                                                    @endif
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
                                                    <td style="color: mediumpurple" class="sorting_1"><strong>Rollback notified</strong></td>
                                                    @break
                                                    @case("rollback_confirmed")
                                                    <td style="color: darkgray" class="sorting_1"><strong>Rollback confirmed</strong></td>
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
                                                                {{--<a href="javascript:editDebt(0)" class="dropdown-item"><i class="dropdown-icon fe fe-edit-2"></i> Edit </a>--}}
                                                                {{--<a href="javascript:void(0)" class="dropdown-item"><i class="dropdown-icon fe fe-delete"></i> Delete </a>--}}
                                                            </div>
                                                        </div>
                                                    @else
                                                        <i class="fe fe-x" data-toggle="tooltip"></i>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                        @endforelse
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

    <!-- Large Modal -->
    <div id="largeModal" class="modal fade">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content ">
                <div class="modal-header pd-x-20">
                    <h6 class="modal-title">Message Preview</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pd-20">
                    <h5 class=" lh-3 mg-b-20"><a href="" class="font-weight-bold">Why We Use Electoral College, Not Popular Vote</a></h5>
                    <p class="">It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. </p>
                </div><!-- modal-body -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary">Save changes</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div><!-- modal-dialog -->
    </div><!-- modal -->

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

            {{--console.log(JSON.parse("{{$response["json_subdebts"]}}"));--}}

            var table = $('#example').DataTable(
                {
                    "columnDefs": [ {
                        "targets": [3],
                        "orderable": false
                    } ],
                    dom: 'lBfrtip',
                    buttons: [
                        {
                            extend:'copy',
                            exportOptions: {
                                columns: [3,4,5,6,7]
                            }
                        },
                        {
                            extend: 'csv',
                            exportOptions: {
                                columns: [3,4,5,6,7]
                            }
                        } ,
                        {
                            extend: 'excel',
                            exportOptions: {
                                columns: [3,4,5,6,7]
                            }
                        },
                        {
                            extend: 'pdf',
                            exportOptions: {
                                columns: [3,4,5,6,7]
                            }
                        },
                        {
                            extend:'print',
                            exportOptions: {
                                columns: [3,4,5,6,7]
                            }
                        }
                    ],
                    exportOptions: {
                        columns:'visible'
                    }
                }
            )

            $('#example tbody').on( 'click', 'a', function () {
                var data = table.row( $(this).parents('tr') ).data();
                var json = JSON.parse(data[0]);
                /*var raw_status = [0];
                var barcode = data[1];
                var unique = data[2];
                var code = data[3];
                var client_id = data[4];
                var status = data[5].replace("/<[^>]*>/",'');
                var amount = data[6];*/

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
            console.log(a);
            $("#modifyModal").modal();
        }

        function debtDetails(a){
            $("#addModal").modal();
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

    </script>
@endpush
