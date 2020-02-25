<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReductController extends Controller
{
    public function getReduct(Request $request){
        $req = $request->all();
        $db = app('db')->connection('mysql');
        try{
            $res = $db->select("SELECT libelle, pctPromo , dateExpiration FROM promotion WHERE id= '" . $req['id'] . "'");
        }catch( \Exception $e){
            return response()->json('mauvais id renseigné', 401);
        }
        try{
            $userId = $db->selectOne("SELECT id FROM utilisateur WHERE token = '" . $req['token'] . "'");
            $userId = json_decode(json_encode($userId),true);
            /*var_dump($userId);
            die;*/
            if (is_null($userId)){
                return response()->json('mauvais token renseigné', 401);
            }
            $userId = $userId['id'];
        }catch( \Exception $e){
            echo($e);
            return response()->json('mauvais token renseigné', 401);
        }
        $ifPromo = $db->selectOne("SELECT id FROM utilisateurpromotions WHERE idUtilisateur = '".$userId."' AND idPromotion = '".$req['id']."' ");
        if (isset($ifPromo)){
            return response()->json('Promotion déjà scannée', 208);
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
            if (is_null($userId)){
                return response()->json('mauvais token renseigné', 401);
            }
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

        $reqLibellePromo = "SELECT pctPromo , libelle , dateExpiration FROM promotion ";
       // var_dump($idPromotions);die;
        for($i=0; $i < count($idPromotions) ; $i++) {
            $reqLibellePromo .= $i == 0 ? 'WHERE id IN ('. $idPromotions[$i]["idPromotion"] : ','.$idPromotions[$i]["idPromotion"];
        }
        $reqLibellePromo .= ")";
        //echo($reqLibellePromo);die;
        $res = $db->select($reqLibellePromo);

        //var_dump($res);die;
        return response()->json($res, 200);
    }

    public function test(Request $request){
        $req = $request->all();
        //var_dump($req);die;
        return response()->json($req, 200);
    }
}

