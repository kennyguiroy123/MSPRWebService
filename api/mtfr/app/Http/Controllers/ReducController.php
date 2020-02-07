<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class  ReducController extends Controller
{
    public function getReduct(Request $request){
        $req = $request->all();
        $db = app('db')->connection('mysql');
        try{
            $res = $db->select("SELECT pctPromo FROM promotion WHERE id=  " . $req['id'] . "");
        }catch( \Exception $e){
            return response()->json('mauvais id renseigné', 401);
        }
        try{
            $userId = $db->select("SELECT id FROM utilisateur WHERE token = " . $req['token'] . "");
        }catch( \Exception $e){
            return response()->json('mauvais token renseigné', 401);
        }
        $db->insert("INSERT INTO utilisateurpromotions (idUtilisateur , idPromotion) VALUES (" . $userId . "," . $req['id'] . "");
        return response()->json($res, 200);
    }

    public function getAllReduct(Request $request){
        $req = $request->all();
        $db = app('db')->connection('mysql');
        try{
            $userId = $db->select("SELECT id FROM utilisateur WHERE token = " . $req['token'] . "");
        }catch( \Exception $e){
            return response()->json('mauvais token renseigné', 401);
        }
        $idPromotions = $db->select("SELECT idPromotion FROM utilisateurpromotions WHERE idUtilisateur = ".$userId." ");
        $reqLibellePromo = "SELECT pctPromo FROM promotions ";
        for($i=0; $i < count($idPromotions) ; $i++) {
            $reqLibellePromo += $i == 0 ? 'WHERE id = '. $idPromotions[$i] : ','.$idPromotions[$i];
        }
        $res = $db->select($reqLibellePromo);

        //var_dump($res);die;
        return response()->json($res[0], 200);
    }

    public function test(Request $request){
        $req = $request->all();
        //var_dump($req);die;
        return response()->json($req, 200);
    }

   /* public function getCategories(Request $request){
        $db = app('db')->connection('mysql');
        $res = $db->select("SELECT * FROM categories");
         return response()->json($res, 200);


    }
    public function getmarques(Request $request){
        $db = app('db')->connection('mysql');
        $res = $db->select("SELECT * FROM marque");
         return response()->json($res, 200);


    }
    public function login(Request $request){
        $util = $request->all();
        $db = app('db')->connection('mysql');

        $res = $db->select("SELECT username, password, lastname, firstname FROM user WHERE username = '".$util['username']."' LIMIT 1;");
        //var_dump($res[0]->username);die;
        if(isset($res[0])){
            if (($res[0]->password == $util['password'])) {
                $date = time() + 3600;
                $token = bin2hex(random_bytes(16));
                $db->update("UPDATE user set token = '".$token."',token_valid ='".$date."' WHERE username = '".$util['username']."' LIMIT 1;");
                return response()->json(
                    array(
                        'token' =>  $token,
                        "lastname" => $res[0]->lastname,
                        "firstname" => $res[0]->firstname
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

    }*/
}

