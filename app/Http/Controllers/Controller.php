<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    private $client;

    public function __construct()
    {
        $this->client = $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => env('API_SIMP2_URI'),
            // You can set any number of default request options.
            'timeout'  => 10,
            "headers" => ['X-API-KEY' => env('API_KEY_SIMP2')]
        ]);
        //$this->$client->setDefaultOption('X-API-KEY', env('API_KEY_SIMP2'));
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
                $this->client->post('company', ["form_params" => $request->all()]);
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
            $enabled = isset($all["enabled"]);

            try{
                $response_guzzle = $this->client->put('company', ["form_params" => ["name"=>$name, "enabled"=>$enabled]]);
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

        return view('companies', ["response"=>$response]);
    }

    public function debts(Request $request){}

    public function users(Request $request){}
}
