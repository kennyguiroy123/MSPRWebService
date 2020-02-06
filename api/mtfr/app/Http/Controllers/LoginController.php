<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login(Request $request){
        $util = $request->all();
        $db = app('db')->connection('mysql');
        $res = $db->selectOne("SELECT login, password FROM utilisateur WHERE login = '".$util['username']."' LIMIT 1;");
        $res = json_decode(json_encode($res),true);
        /*var_dump($res);
        die;*/

        if(isset($res["login"])){
            if (($res["password"] == $util['password'])) {
                $token = bin2hex(random_bytes(16));
                $db->update("UPDATE utilisateur set token = '".$token."' WHERE login = '".$util['username']."' LIMIT 1;");
                return response()->json(
                    array(
                        'token' =>  $token,
                    ), 200
                );
            }else{
                return response()->json(   
                    array(
                        "error" => true,
                        "description" => "Bad password",
                    ), 401
                );
            }
        }else{
            return response()->json(
                array(
                    "error" => true,
                    "description" => "User doesn't exists",
                ), 404
            );
        }
    }
}
