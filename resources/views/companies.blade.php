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
                                            <th hidden> config </th>
                                            <th class="wd-15p sorting" tabindex="0" aria-controls="example"
                                                rowspan="1" colspan="1" aria-sort="ascending"
                                                aria-label="Name: activate to sort column descending"
                                                style="width: 47px;">Name
                                            </th>
                                            <th class="wd-15p sorting" tabindex="0" aria-controls="example" rowspan="1"
                                                colspan="1" aria-label="Status: activate to sort column ascending"
                                                style="width: 47px;">Status
                                            </th>
                                            <th class="wd-15p sorting" tabindex="0" aria-controls="example" rowspan="1"
                                                colspan="1" aria-label="CompanyTransactionToken: activate to sort column ascending"
                                                style="width: 47px;">Company transaction token
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
                                                    <td hidden class="sorting_1">
                                                        @isset($company['configuration'])
                                                            {{json_encode($company['configuration'])}}
                                                        @endisset
                                                    </td>
                                                    <td class="sorting_1">{{$company["name"]}}</td>
                                                    <td>
                                                        @if ( isset($company["enabled"]) && $company["enabled"]==true)
                                                            <span class="tag tag-lime">Enabled</span>
                                                        @else
                                                            <span class="tag tag-gray">Disabled</span>
                                                        @endif
                                                    </td>
                                                    <td class="sorting_1">{{$company["company_transaction_token"]}}</td>
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
                            <input type="text" class="form-control" name="name" readonly id="company-name-modify">
                        </div>
                        <div class="form-group">
                            <label class="custom-switch">
                                <input id="company-enable-modify" type="checkbox" name="enabled" class="custom-switch-input" checked>
                                <span class="custom-switch-indicator"></span>
                                <span class="custom-switch-description">Enabled</span>
                            </label>
                        </div>
                        <h5 class="modal-title" id="example-Modal3">Configuracion de medios de pago</h5>
                        <br>
                        <textarea class="form-control" id ="company-modify-config-json" name="company-modify-config-json" rows="10" placeholder='[ { "bridge" : { "enabled" : true, "bridge_url" : "https://herbalife.sandbox.simp2.com/api/v1/debt/unique" }, "payment_methods" : { "paypal" : { "method" : "POST", "replicator" : { "payment_notification" : { "enabled" : false, "url" : "https://herbalife.sandbox.simp2.com/api/v1/payments/notify", "method" : "POST" }, "payment_confirmation" : { "enabled" : false, "url" : null, "method" : "POST" }, "reverse_notification" : { "enabled" : false, "url" : null, "method" : "POST" }, "reverse_confirmation" : { "enabled" : false, "url" : null, "method" : "POST" } } }, "rapipago" : { "enabled" : false, "bridge_url" : null, "method" : "POST", "replicator" : { "payment_notification" : { "enabled" : false, "url" : null, "method" : "POST" }, "payment_confirmation" : { "enabled" : false, "url" : null, "method" : "POST" }, "reverse_notification" : { "enabled" : false, "url" : null, "method" : "POST" }, "reverse_confirmation" : { "enabled" : false, "url" : null, "method" : "POST" } } }, "pagofacil" : { "enabled" : true, "bridge_url" : "https://herbalife.sandbox.simp2.com/api/v1/debt/unique", "method" : "POST", "replicator" : { "payment_notification" : { "enabled" : true, "url" : "https://herbalife.sandbox.simp2.com/api/v1/payments/notify", "method" : "POST" }, "payment_confirmation" : { "enabled" : false, "url" : "http://localhost/simp2-notifications/public/api/requestRecieverTester", "method" : "POST" }, "reverse_notification" : { "enabled" : false, "url" : null, "method" : "POST" }, "reverse_confirmation" : { "enabled" : false, "url" : "http://localhost/simp2-notifications/public/api/requestRecieverTester", "method" : "POST" } } } } } ]'></textarea>
                        {{--@foreach($response["paymentMethods"] as $pm)
                                <div class="form-group">
                                    <label class="custom-switch">
                                        <input id="enable-{{$pm}}"  type="checkbox" class="custom-switch-input mp-enable" checked>
                                        <span class="custom-switch-indicator"></span>
                                        <span class="custom-switch-description">{{$pm}}</span>
                                    </label>
                                </div>

                                <div id="config-{{$pm}}" style="border: #7c8989 1px solid; padding: 8px; border-radius: 2px; margin-bottom: 10px;">
                                    <div id="puente-consulta-{{$pm}}">
                                        <div class="form-group">
                                            <label class="custom-switch">
                                                <input id="enable-bridge-{{$pm}}" type="checkbox" class="custom-switch-input bridge-enable" checked>
                                                <span class="custom-switch-indicator"></span>
                                                <span class="custom-switch-description">Puente de consulta</span>
                                            </label>

                                        </div>

                                        <div class="form-group" id="bridge-{{$pm}}">
                                            <div class="form-group">
                                                <label for="recipient-bridge-url" class="form-control-label">URL de puente:</label>
                                                <input type="text" class="form-control" name="bridge-url-{{$pm}}" id="bridge-url-{{$pm}}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="custom-switch">
                                            <input id="enable-replicator-{{$pm}}" type="checkbox" class="custom-switch-input enable-replicator" checked>
                                            <span class="custom-switch-indicator"></span>
                                            <span class="custom-switch-description">Replicador</span>
                                        </label>
                                    </div>

                                    <div class="form-group" id="replicator-{{$pm}}">
                                        <label class="custom-switch">
                                            <input id="enable-replicator-payment-notify-{{$pm}}" type="checkbox" class="custom-switch-input enable-replicator-url" checked>
                                            <span class="custom-switch-indicator"></span>
                                            <span class="custom-switch-description">Replicar notificacion de pagos</span>
                                        </label>
                                        <div class="form-group">
                                            <label for="recipient-replicator-payment-notify-{{$pm}}" class="form-control-label">URL de notificacion de pagos:</label>
                                            <input type="text" class="form-control replicator-url-input" name="replicator-payment-notify-{{$pm}}" id="replicator-payment-notify-{{$pm}}">
                                        </div>

                                        <label class="custom-switch">
                                            <input id="enable-replicator-payment-confirm-{{$pm}}" type="checkbox" class="custom-switch-input enable-replicator-url" checked>
                                            <span class="custom-switch-indicator"></span>
                                            <span class="custom-switch-description">Replicar notificacion de pagos</span>
                                        </label>
                                        <div class="form-group">
                                            <label for="recipient-replicator-payment-notify-{{$pm}}" class="form-control-label">URL de notificacion de pagos:</label>
                                            <input type="text" class="form-control replicator-url-input" name="replicator-payment-confirm-{{$pm}}" id="replicator-payment-confirm-{{$pm}}">
                                        </div>

                                        <label class="custom-switch">
                                            <input id="enable-replicator-reverse-notify-{{$pm}}" type="checkbox" class="custom-switch-input enable-replicator-url" checked>
                                            <span class="custom-switch-indicator"></span>
                                            <span class="custom-switch-description">Replicar notificacion de pagos</span>
                                        </label>
                                        <div class="form-group">
                                            <label for="recipient-replicator-payment-notify-{{$pm}}" class="form-control-label">URL de notificacion de pagos:</label>
                                            <input type="text" class="form-control replicator-url-input" name="replicator-reverse-notify-{{$pm}}" id="replicator-reverse-notify-{{$pm}}">
                                        </div>

                                        <label class="custom-switch">
                                            <input id="enable-replicator-reverse-confirm-{{$pm}}" type="checkbox" class="custom-switch-input enable-replicator-url" checked>
                                            <span class="custom-switch-indicator"></span>
                                            <span class="custom-switch-description">Replicar notificacion de pagos</span>
                                        </label>
                                        <div class="form-group">
                                            <label for="recipient-replicator-payment-notify-{{$pm}}" class="form-control-label">URL de notificacion de pagos:</label>
                                            <input type="text" class="form-control replicator-url-input" name="replicator-reverse-confirm-{{$pm}}" id="replicator-reverse-confirm-{{$pm}}">
                                        </div>
                                    </div>
                                </div>
                        @endforeach--}}

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" id="btn-modify-submit" class="btn btn-primary">Modify company</button>
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
            $('#btn-modify-submit').prop('disabled',true);
            $('#company-modify-config-json').keyup(function (){
                try {
                    //console.log();
                    var a  = $.parseJSON($('#company-modify-config-json').val());
                    console.log(a);
                    console.log("Json ok");
                    //$('#btn-modify-submit').val($.parseJSON($('#company-modify-config-json').val()));

                    $('#btn-modify-submit').prop('disabled',false);
                }
                catch (err) {
                    //console.log("Json vergeado");
                    $('#btn-modify-submit').prop('disabled',true);
                }
            });
            var table = $('#example').DataTable(
                {
                    "columnDefs": [ {
                        "targets": [4],
                        "orderable": false
                    } ]
                }
            )

            $('#example tbody').on( 'click', 'a', function () {
                var data = table.row( $(this).parents('tr') ).data();
                //console.log(data);
                $("#company-modify-config-json").val(data[0]);
                $("#company-name-modify").val(data[1]);
                var enabled = data[2].includes("Enabled");
                $("#company-enable-modify").prop("checked", enabled);
            } );
        });

        $('.mp-enable').change(
            function(){
                var medioPago = this.id.replace('enable-','');
                if (this.checked) {
                    $('#config-'+medioPago).show();
                }else{
                    //$('#config-'+medioPago+' input[type=text]').attr('disabled', true);
                    $('#config-'+medioPago+' input[type=checkbox]').prop('checked', false);
                    $('#config-'+medioPago).hide();
            }
        });

        $('.bridge-enable').change(
            function(){
                var medioPago = this.id.replace('enable-bridge-','');

                if (this.checked) {
                    $('#puente-consulta-'+medioPago+' input[type=text]').attr('disabled', false);
                    $('#bridge-pagofacil').show();
                }else{
                    $('#puente-consulta-'+medioPago+' input[type=text]').attr('disabled', true);
                    $('#puente-consulta-'+medioPago+' input[type=checkbox]').prop('checked', false);
                    $('#bridge-pagofacil').hide();
                }
        });

        $('#company-modify-config-json').focusout(function(e) {



        });

        $('.enable-replicator').change(
            function(){
                var medioPago = this.id.replace('enable-replicator-','');
                if (this.checked) {
                    $('#replicator-'+medioPago+' input[type=text]').attr('disabled', false);
                    $('#replicator-'+medioPago).show();
                }else{
                    $('#replicator-'+medioPago+' input[type=text]').attr('disabled', true);
                    $('#replicator-'+medioPago+' input[type=checkbox]').prop('checked', false);
                    $('#replicator-'+medioPago).hide();
                }
            });

        $('.enable-replicator').change(
            function(){
                var medioPago = this.id.replace('enable-replicator-','');
                if (this.checked) {
                    $('#replicator-'+medioPago+' input[type=text]').attr('disabled', false);
                    $('#replicator-'+medioPago).show();
                }else{
                    $('#replicator-'+medioPago+' input[type=text]').attr('disabled', true);
                    $('#replicator-'+medioPago+' input[type=checkbox]').prop('checked', false);
                    $('#replicator-'+medioPago).hide();
                }
            });

        $('.enable-replicator-url').change(
            function(){
                var medioPago = this.id.replace('enable-','');/*.replace('payment-notify-','').replace('payment-confirm-','').replace('reverse-notify-','').replace('reverse-confirm-','');*/
                if (this.checked) {
                    $('#'+medioPago).attr('disabled', false);
                    $('#'+medioPago).show();
                }else{
                    $('#'+medioPago).attr('disabled', true);
                    $('#'+medioPago).hide();
                }
            });

        function editCompany(a){

            if($('#company-modify-config-json').val().length !=0){
                $('#btn-modify-submit').prop('disabled',false);
            }else{
                $('#btn-modify-submit').prop('disabled',true);
            }

            $("#modifyModal").modal();
        }

        $('.replicator-url-input').attr('disabled', true);
        $('.replicator-url-input-div').hide();
        $('.enable-replicator').prop('checked', false);
        $('.enable-replicator-url').prop('checked', false);

        function IsJsonString(str) {
            try {
                JSON.parse(str);
            } catch (e) {
                return false;
            }
            return true;
        }

    </script>
@endpush
