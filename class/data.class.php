<?php
/**
 * Class  Data
 *
 * Permet de gérer l'accès au BDD
 *
 * Auteur : MOI
 * Date : 24-07-20232
 * Version : 1.0
 */
class Data{
    // Varaibles de classe
    private $link = null;
    private $database_name = SERVEUR_BDD;
    private $user_bdd = USER_BDD;
    private $password_bdd = PASSWORD_BDD;
    private $name_bdd = NAME_BDD;
    private $error_serveur =  ERROR_SERVEUR;
    private $error_bdd = ERROR_BDD;

    // Constructeur
    public function __construct(){
        // Connexion au serveur de BDD
        $this->link = mysqli_connect($this->database_name, $this->user_bdd,$this->password_bdd) or die($this->error_serveur);

        // Selection de la base de données
        mysqli_select_db($this->link,$this->name_bdd) or die($this->error_bdd);

        // Gestion de l'Encodage
        $sql = "SET CHARACTER SET 'utf8mb4';";
        mysqli_query($this->link, $sql);

        $sql = "SET collation_connection = 'utf8mb4_general_ci';";
        mysqli_query($this->link, $sql);
    }

    // Methode Public (comprendre fonction)
    public function query($query){
        if(!empty($query)){
            $result = mysqli_query($this->link, $query);
            if(!$result) {
                return false;
            } else {
                return $result;
            }
        }
    }

    public function squery($sql){
        $result=$this->query($sql);
        if(@mysqli_num_rows($result)==1){
            $r=@mysqli_fetch_row($result);
            return $r[0];
        }
        if(@mysqli_num_rows($result)>1){
            $r=array();
            while($row=@mysqli_fetch_assoc($result)) $r[]=$row;
            return $r;
        }
        return FALSE;
    }

    // build_r_from_id => recuperer une ligne dans une table a partir d'un ID
    public function build_r_from_id($table, $id, $id_field_name='id'){
        $sql = "SELECT * FROM ".$table." WHERE `".$id_field_name."`='".$id."' LIMIT 1;";
        $result = $this->query($sql);
        return mysqli_fetch_assoc($result);
    }

    // getData => recuperer des ingformations depuis la BDD a partir d'une requete SQL (simple ou avancée...)
    public function getData($sql){
        if(!empty($sql)){
            $result = $this->query($sql);
            if(!$result || !mysqli_num_rows($result)){
                return false;
            }else{
                $r = array();
                while($row = @mysqli_fetch_assoc($result)){
                    $r[] = $row;
                }
                return $r;
            }
        }
    }

    // delete => supprimer
    public function sql_delete($table, $id, $id_field_name='id'){
        $sql = "DELETE FROM ".$table." WHERE `".$id_field_name."`='".$id."' LIMIT 1;";
        return $this->query($sql);
    }

    // update => updater
    public function sql_update($table, $id, $r, $id_field_name='id'){
        foreach($r as $key=>$val){
            $tmp_set[] = $key."='".addslashes($val)."'";
        }
        $sql = "UPDATE ".$table." SET ".implode(', ',$tmp_set)." WHERE `".$id_field_name."`='".$id."' LIMIT 1;";
        return $this->query($sql);
    }

    // insert => inserer
    public function sql_insert($table, $r){
        foreach($r as $key=>$val){
            $insert[] = '`'.$key.'`';
            $value[] = "'".addslashes($val)."'";
        }
        $sql = "INSERT INTO ".$table." (".implode(', ',$insert).") VALUES (".implode(', ',$value).");";
        $this->query($sql);
        // Retourne l'ID de la ligne insérée
        return @((is_null($___mysqli_res = mysqli_insert_id($this->link))) ? false : $___mysqli_res);
    }

}

?>