<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\Response as STATUS;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Log;
use MongoDB\BSON\UTCDateTime;
use Spatie\Permission\Models\Role;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    private $client;
    private $apiKey;
    private $companyTransactionToken;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->apiKey = Auth::user()->api_key;
            //dd(Auth::user());
            $this->client = $client = new Client([
                'base_uri' => env('API_SIMP2_URI'),
                'timeout'  => 20,
                "headers" => ['X-API-KEY' => $this->apiKey]
            ]);
            return $next($request);
        });
    }

    public function dashboard(Request $request){
        return view('dashboard');
    }

    public function companies(Request $request){

        $response = [];

        if ($request->method() == 'POST')
        {
            $all = $request->all();
            try {
                $r = $this->client->post('company', ["form_params" => $request->all()]);
                //dd($r,$r->getBody()->getContents(),json_encode($all));
                $response["successMsg"] = "The company ".$all["name"]." was created successfully.";
            }catch (ClientException $e){
                if($e->getResponse()->getStatusCode()==409){
                    $response["errorMsg"] = "The selected name ".$all["name"]." is already taken.";
                }
            }catch (ServerException $e){
                if($e->getResponse()->getStatusCode()==500){
                    $response["errorMsg"] = "An unexpected error has ocurred please retry.";
                }
            }catch (GuzzleException $e){
                $response["warningMsg"] = "There was an error in the connection, please retry.";
            }
        }

        if ($request->method() == 'PUT')
        {
            $all = $request->all();
            $name = $all["name"];
            $jsonConfig = $all["company-modify-config-json"];
            //dd($all);
            $enabled = isset($all["enabled"]);

            try{
                //dd( json_encode(["name"=>$name, "enabled"=>$enabled,"json_config"=>$jsonConfig]));
                $response_guzzle = $this->client->put('company', ["form_params" => ["name"=>$name, "enabled"=>$enabled,"json_config"=>$jsonConfig]]);
                $response["successMsg"] = "The company ".$all["name"]." was modified successfully.";
                //var_dump($response_guzzle->getBody()->getContents());
            }catch (ClientException $e) {
                //var_dump($e);
                if ($e->getResponse()->getStatusCode() == 404) {
                    $response["errorMsg"] = $all["name"] . " company was not found.";
                }
            }catch (ServerException $e){
                if($e->getResponse()->getStatusCode()==500){
                    $response["errorMsg"] = "An unexpected error has ocurred please retry.";
                }
            }catch (GuzzleException $e){
                $response["warningMsg"] = "There was an error in the connection, please retry.";
            }
        }

        $response["companies"] = json_decode($this->client->get('company')->getBody()->getContents(), true);
        $response["paymentMethods"] = ["pagofacil", "rapipago", "paypal"]; //config("payment_methods_config"
        //dd($response["companies"]);
        return view('companies', ["response"=>$response]);
    }

    public function users(Request $request){
        $response = [];

        if ($request->method() == 'PUT'){
            //dd($request->all());
            if(isset($request->all()["id-user-backoffice-modify"])){
                $req = $request->all();
                $userId = intval($req["id-user-backoffice-modify"]);
                $userRole = $req["role-select-backoffice-modify"];
                $companyId = $req["company-select-backoffice-modify"];
                $apiKey = $req["api-key-backoffice-modify"];
                $enabled = isset($req["checkbox-enable-backoffice-modify"]) ? true : false;

                $user = User::where('id',$userId) -> first();
                $user->company_unique_id = $companyId;
                $user->enabled = $enabled;
                $user->api_key = $apiKey;
                $user->assignRole("user");
                $user->save();

                //dd($userRole,$user);
            }else{
                $enabled = isset($request->all()["enabled"]) ? true : false;
                $userId = $request->get('id-user-apiclient-modify');
                $bodyReq = ["id"=>$userId, "enabled"=>$enabled];

                try{
                    $response_guzzle = $this->client->put('api_clients', ["form_params" =>$bodyReq]);
                    $response["successMsg"] = "The user was modified successfully.";
                }catch (ClientException $e) {
                    if ($e->getResponse()->getStatusCode() == 404) {
                        $response["errorMsg"] = "The user was not found.";
                    }
                }catch (ServerException $e){
                    if($e->getResponse()->getStatusCode()==500){
                        $response["errorMsg"] = "An unexpected error has ocurred please retry.";
                    }
                }catch (GuzzleException $e){
                    $response["warningMsg"] = "There was an error in the connection, please retry.";
                }
            }
        }

        if ($request->method() == 'POST')
        {
            $all = $request->all();
            $name = $all["name"];
            $lastName = $all["last_name"];
            $userRole = $all["user_role_create"];
            $companyUniqueId = (int) $all["user_company_create"];
            $enabled = isset($all["enabled"]) ? true : false;
            $id = !isset($all["id"]) ? null : $all["id"];
            try {
                $formParams = [
                    "name"=>$name,
                    "last_name"=>$lastName,
                    "role"=>$userRole,
                    "company_unique_id"=>$companyUniqueId,
                    "enabled"=>$enabled,
                    "id"=>$id
                ];
                //dd(json_encode($formParams));
                $res = $this->client->post('api_clients',
                    ["form_params" => $formParams]
                );
                //dd($res->getStatusCode());
                $response["successMsg"] = "The user ".$all["name"]." ".$all["last_name"]." was created successfully.";
            }catch (ClientException $e){
                if($e->getResponse()->getStatusCode()==409){
                    $response["errorMsg"] = "The selected name ".$all["name"]." ".$all["last_name"]." is already taken for the company selected.";
                }
            }catch (ServerException $e){
                if($e->getResponse()->getStatusCode()==500){
                    $response["errorMsg"] = "An unexpected error has ocurred please retry.";
                }
            }catch (GuzzleException $e){
                $response["warningMsg"] = "There was an error in the connection, please retry.";
            }
        }

        $response["backoffice_users"] = User::all();
        $response["api_clients"] = json_decode($this->client->get('api_clients')->getBody()->getContents(), true);

        //dd($response["api_clients"]);
        //dd(json_decode($this->client->get('company')->getBody()->getContents(), true));
        $response["companies"] = json_decode($this->client->get('company')->getBody()->getContents(), true);
        $response["roles"] = Role::all();
        //dd($response["api_clients"][0]["_id"]);

        throw new \Exception("");
        return view('users', ["response"=>$response]);
    }

    public function debts(Request $request){
        $response = [];
        /*get the user company id and search whole company debts debts**/
        $user = Auth::user();

        $mongoClient = new \MongoDB\Client($_ENV['MONGODB_CS']);
        //TODO show configurations payment methods from companies
        $paymentMethods = ["rapipago","pagofacil","paypal"];


        //dd($payments);
        if ($request->method() == 'POST')
        {
            if($request->get('from-date-filter') || $request->get('to-date-filter') ||
                $request->get('code-filter') || $request->get('client-origin-filter')){

                $code = $request->get('code-filter');
                $clientId = $request->get('client-id-filter');

                $filter = [
                    'debt.company_id'=>$user["company_unique_id"]
                ];

                if($request->get('from-date-filter') || $request->get('to-date-filter')){
                    $filter["created_at"] = [];
                    if($request->get('from-date-filter')){
                        $fromDate = Carbon::createFromFormat('m/d/Y',$request->get('from-date-filter'))
                            ->setHours(0)->setSeconds(0)->setMinutes(0);
                        $fromDate = new UTCDateTime($fromDate->getTimestamp()*1000);
                        $filter["created_at"]["\$gte"] = $fromDate;
                    }

                    if($request->get('to-date-filter')){
                        $toDate = Carbon::createFromFormat('m/d/Y',$request->get('to-date-filter'))
                            ->setHours(23)->setSeconds(59)->setMinutes(59);
                        $toDate = new UTCDateTime($toDate->getTimestamp() * 1000);
                        $filter["created_at"]["\$lte"] = $toDate;
                    }
                }

                if($code){
                    $filter["debt.code"] = $code;
                }

                if($clientId){
                    $filter["client_origin_id"] = $clientId;
                }

                $db = $mongoClient->simp2;
                $collection = $db->testPayments;
                $pipeline = array(
                    [
                        '$lookup' => [
                            "from" => "testDebts",
                            "localField" => "unique_reference",
                            "foreignField" => "subdebts.unique_reference",
                            "as"=>"debt"
                        ]
                    ],
                    [
                        '$match'=>$filter
                    ],
                );
                $payments = $collection->aggregate($pipeline)->toArray();

                $response["payments"] = $payments;
                $response["payment_methods"] = $paymentMethods;

                return view('debts', ["response"=>$response]);
            }

            $all = $request->all();
            $formParams = [];
            $formParams["code"] = $all["new-code"];
            $formParams["ccf_code"] = $all["new-ccf-code"];
            $formParams["ccf_client_id"] = $all["new-ccf-client-id"];
            $formParams["ccf_client_data"] = [];
            $formParams["ccf_client_data"]["first_name"] = $all["new-first-name"];
            $formParams["ccf_client_data"]["last_name"] = $all["new-last-name"];
            $formParams["ccf_client_data"]["extra"] = explode(" ",$all["new-extra"]);
            $formParams["ccf_extra"] = $all["new-extra"];

            $formParams["payment_methods"] = [];
            foreach ($paymentMethods as $pm){
                $isMethodSet = isset($all["new-payment-switch-".$pm]);
                if($isMethodSet){
                    $newPM = [];
                    $newPM["name"] = $pm;
                    $newPM["submethods"] = [];
                    if (isset($all[$pm."-cash"])) array_push($newPM["submethods"],"cash");
                    if (isset($all[$pm."-credit"])) array_push($newPM["submethods"],"credit_card");
                    if (isset($all[$pm."-debit"])) array_push($newPM["submethods"],"debit_card");

                    if (sizeof($newPM["submethods"])>0) array_push($formParams["payment_methods"],$newPM);
                }
            }

            $formParams["subdebts"] = [];
            foreach ($all as $key => $value){
                //var_dump($value.'<br>');
                if(str_contains($key,'new-subdebt-')){
                    $arr = json_decode($value,true);

                    $arr["due_date"]= date_create_from_format('d/m/Y',$arr["due_date"])->format('Y-m-d H:i:s');
                    //$arr["due_date"]= date_create_from_format('d/m/Y',$arr["due_date"])->format('Y-m-d H:i:s');
                    $arr["texts"] = [];
                    array_push(
                        $formParams["subdebts"],$arr);
                }
            }

            try {

                //dd(json_encode($formParams));
                $res = $this->client->post('debt',
                    ["form_params" => $formParams]
                );
                //dd($res->getStatusCode());
                $response["successMsg"] = "The debt/s were created successfully.";
            }catch (ClientException $e){
                if($e->getResponse()->getStatusCode()==409){
                    $response["errorMsg"] = "One or more unique reference were already in use";
                }
            }catch (ServerException $e){
                if($e->getResponse()->getStatusCode()==500){
                    $response["errorMsg"] = "An unexpected error has ocurred please retry.";
                }
            }catch (GuzzleException $e){
                $response["warningMsg"] = "There was an error in the connection, please retry.";
            }
        }

        if($request->method() == 'DELETE'){
            $uniqueRef = $request->get('delete-payment-debt-unique-reference');

            try {
                $res = $this->client->delete('payments/delete/'.$uniqueRef,[]);
                //dd($res->getStatusCode());
                $response["successMsg"] = "The debt was deleted successfully.";
            }catch (ClientException $e){
                dd($e->getResponse()->getStatusCode());
                $response["errorMsg"] = "An unexpected error has ocurred please retry.";
            }catch (\Exception $e){
                dd($e->getMessage());
                $response["errorMsg"] = "An unexpected error has ocurred please retry.";
            }catch (GuzzleException $e){
                $response["warningMsg"] = "There was an error in the connection, please retry.";
            }

        }


        $debts = $mongoClient->simp2->testDebts->find([
            'company_id'=>$user["company_unique_id"],
            'barcode'=>['$ne'=>null]
        ])->toArray();

        $payments = $mongoClient->simp2->testPayments->find([
            'company_id'=>$user["company_unique_id"]
        ])->toArray();

        $db = $mongoClient->simp2;
        $collection = $db->testPayments;
        $pipeline = array(
            [
                '$lookup' => [
                    "from" => "testDebts",
                    "localField" => "unique_reference",
                    "foreignField" => "subdebts.unique_reference",
                    "as"=>"debt"
                ]
            ],
            [
                '$match'=>[
                    'debt.company_id'=>$user["company_unique_id"]
                ]
            ],
        );
        //dd($pipeline);
        $payments = $collection->aggregate($pipeline)->toArray();
        //dd($payments);
        $subs = [];
        foreach ($debts as $debt){
            foreach ($debt["subdebts"] as $subdebt){
                array_push($subs,$subdebt);
            }
        }

        $debts = sizeof($debts) > 0 ? $debts[0] : [];
        //dd($debts);
        $response["debts"] = $debts;
        $response["debts"]["subdebts"] = $subs == [] ? [] : $subs;
        $response["json_subdebts"] = json_encode($subs,true);
        $response["payment_methods"] = $paymentMethods;
        $response["payments"] = $payments;

        //dd($response);

        return view('debts', ["response"=>$response]);
    }

    public function reverses(Request $request){
        $response = [];

        $mongoClient = new \MongoDB\Client($_ENV['MONGODB_CS']);

        /*get the user company id and search whole company reverses**/
        $user = Auth::user();
        $reverses = $mongoClient->simp2->testPayments->find([
            'company_id'=>$user["company_unique_id"],
            'status'=>new \MongoDB\BSON\Regex("rollback_")
        ])->toArray();

        $response["reverses"] = $reverses;
        return view('reverses', ["response"=>$response]);
    }

    public function warnings(Request $request){
        $response = [];

        $mongoClient = new \MongoDB\Client($_ENV['MONGODB_CS']);

        /*get the user company id and search whole company reverses**/
        $user = Auth::user();
        $reverses = $mongoClient->simp2->testPayments->find([
            'company_id'=>$user["company_unique_id"],
            'created_at'=> ["\$lte"=>new UTCDateTime(Carbon::today()->subDays(4)->getTimestamp()*1000)],
            'status'=>new \MongoDB\BSON\Regex("pending_payment")
        ])->toArray();

        $response["warnings"] = $reverses;
        //dd($response);

        return view('warnings', ["response"=>$response]);
    }

    public function ajaxTable(Request $request){
        $response = [];
        Log::debug($request->all());
        try {
            $response = $this->client->post('payments/ajax/paginated', ["form_params" => $request->all()]);
            $response = $response->getBody()->getContents();
        }catch (\Exception $e){
            if($e->getResponse()->getStatusCode()==409){
                Log::debug($e->getMessage());
            }
        }
        catch (ClientException $e){
            if($e->getResponse()->getStatusCode()==409){
                //$response["errorMsg"] = "The selected name ".$all["name"]." is already taken.";
            }
        }catch (ServerException $e){
            if($e->getResponse()->getStatusCode()==500){
                //$response["errorMsg"] = "An unexpected error has ocurred please retry.";
            }
        }catch (GuzzleException $e){
            //$response["warningMsg"] = "There was an error in the connection, please retry.";
        }

        return new Response($response,200);
    }

}
