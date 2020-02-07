<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReductController extends Controller
{
    public function getReduct(Request $request){
        $req = $request->all();
        $db = app('db')->connection('mysql');
        try{
            $res = $db->select("SELECT libelle FROM promotion WHERE id=  " . $req['id'] . "");
        }catch( \Exception $e){
            return response()->json('mauvais id renseigné', 401);
        }
        try{
            $userId = $db->selectOne("SELECT id FROM utilisateur WHERE token = '" . $req['token'] . "'");
            $userId = json_decode(json_encode($userId),true);
            /*var_dump($userId);
            die;*/
            $userId = $userId['id'];
        }catch( \Exception $e){
            echo($e);
            return response()->json('mauvais token renseigné', 401);
        }
        try{
            $db->insert('INSERT INTO utilisateurpromotions (idUtilisateur , idPromotion) VALUES (?,?)',[$userId ,$req['id']]);
        }catch( \Exception $e){
           echo($e);
        }
        return response()->json($res, 200);
    }
    
    public function getAllReduct(Request $request){
        $req = $request->all();
        $db = app('db')->connection('mysql');
        try{
            $userId = $db->selectOne("SELECT id FROM utilisateur WHERE token = '" . $req['token'] . "'");
            $userId = json_decode(json_encode($userId),true);
            //var_dump($userId);die;
            $userId = $userId['id'];
        }catch( \Exception $e){
            echo($e);
            return response()->json('mauvais token renseigné', 401);
        }
        try {
            $idPromotions = $db->select("SELECT idPromotion FROM utilisateurpromotions WHERE idUtilisateur = ".$userId." ");
            $idPromotions = json_decode(json_encode($idPromotions),true);
        } catch (\Throwable $th) {
            echo($th);
        }
        
        $reqLibellePromo = "SELECT pctPromo FROM promotions ";
       // var_dump($idPromotions);die;
        for($i=0; $i < count($idPromotions) ; $i++) { 
            $reqLibellePromo .= $i == 0 ? 'WHERE id = '. $idPromotions[$i]["idPromotion"] : ','.$idPromotions[$i]["idPromotion"];
        }
        echo($reqLibellePromo);die;
        $res = $db->select($reqLibellePromo);
        
        //var_dump($res);die;
        return response()->json($res[0], 200);
    }

    public function test(Request $request){
        $req = $request->all();
        //var_dump($req);die;
        return response()->json($req, 200);
    }
}

