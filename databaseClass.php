<?php
class DBConnection{
    private $host;
    private $user;
    private $pass;
    private $dbName;
    public function setHost($host){
        switch($host){
            case "127.0.0.1":
                $this->host = "localhost";
            break;
            case "localhost":
                $this->host = "localhost";
            break;
            default:
                $this->host = "localhost";
        }
    }
    public function getHost(){
        return $this->host;
    }
    public function setPass($pass){
        $this->pass = $pass;
    }

    public  function getPass(){
        return $this->pass;
    }
    public function setUser($user){
        $this->user = $user;
    }
    public  function getUser(){
        return $this->user;
    }
    public function setDB($db){
        $this->db = $db;
    }
    public  function getDB(){
        return $this->db;
    }
    public $pdo;
    public function Connection(){
        $host = $this->getHost();
        $db = $this->getDB();
        $dsn = "mysql:host=$host;dbname=$db";
        $this->pdo = new PDO($dsn,$this->getUser(),$this->getPass());
        $this->pdo->exec("SET CHARACTER SET utf8");

        if(!$this->pdo){
            echo "DB Error !";die;
        }
    }

    public function selectDB($data){
        $this->Connection();
        if(is_array($data)){
            switch(count($data)){
                case 3:
                    $tbl = $data[0];
                    $where = $data[1];
                    $akt = $data[2];
                    try{
                        $query = "SELECT * FROM $tbl where $where = '$akt'";
                        $con = $this->pdo;
                        $coon = $con->prepare($query);
                        
                        $coon->execute();
                        return $coon;
                    }catch(Exception $e){
                        echo "Error Data Array";
                        die;
                    }
                break;
                case 1:
                    $tbl = $data[0];
                    
                    try{
                        $query = "SELECT * FROM $tbl";
                        $con = $this->pdo;
                        $coon = $con->query($query);
                        
                        $coon->execute();
                        return $coon;
                    }catch(Exception $e){
                        echo "Error Data Array";
                        die;
                    }
                break;

                default:
                    echo "Error Array : TBL , WHERE , AKT OR TBL";
                    die;
            }
        }
    }
    public function INSERTdata($tbl,$data){
        $this->Connection();
        if(!is_array($data)){
            echo "Error : Data Is Not Array";
        }else{
            $sen='';
            $sen2='';
            if(count($data) == 1 && !empty($data)){
                foreach($data as $k=>$v){
                    $sen = $k;
                    if(is_string($v)){
                        $sen2 = "'".$v."'";
                    }else{
                        $sen2 = $v;
                    }
                }
                $c = $this->pdo;
               
                $cons = $c->prepare("INSERT INTO $tbl (`$sen`) VALUES ($sen2)");
                $cons->execute();
               
            }else{
                $sen= [];
            $sen2= [];
            foreach($data as $l => $r){
                array_push($sen,$l); 
                if(is_string($r)){
                    $r = "'".$r."'";
                    array_push($sen2,$r);
                }else{
                    array_push($sen2,$r);
                }
                
                
            }
                $dataSend = implode("`,`",$sen);
                $dataSend2 = implode(",",$sen2);
                $c = $this->pdo;
                $cons = $c->prepare("INSERT INTO $tbl (`$dataSend`) VALUES ($dataSend2)");
                $cons->execute();
            }
        }
    }


    public function UPDATEdata($tbl,$data,$w1,$w2){
        $this->Connection();
        if(!is_array($data)){
            echo "Error : Data Is Not Array";
        }
        $sen='';
        $sen2='';
        if(count($data) == 1 && !empty($data)){
            foreach($data as $k=>$v){
                $sen = $k;
                if(is_string($v)){
                    $sen2 = "'".$v."'";
                }else{
                    $sen2 = $v;
                }
            }
            if(is_string($w2)){
                $w2 = "'".$w2."'";
            }
            $c = $this->pdo;
            $cons = $c->prepare("UPDATE `$tbl` SET `$sen` = '$sen2' WHERE `$w1` = $w2;");
            $cons->execute();
        }else{
            
            $sen= [];
            $sen2= [];
            foreach($data as $l => $r){
                array_push($sen,$l); 
                if(is_string($r)){
                    $r = "'".$r."'";
                    array_push($sen2,$r);
                }else{
                    array_push($sen2,$r);
                }
                
                
            }
            
            if(is_string($w2)){
                $w2 = "'".$w2."'";
            }
            $check = "";
            $e = count($sen);
            
            for($y=0;$y<$e;$y++){
                $check = $check.",".$sen[$y]."=".$sen2[$y];
            }
            $re = explode(",",$check);
            unset($re[0]);
            $dataSend2 = implode(",",$re);
            $cons = $this->pdo->prepare("UPDATE `$tbl` SET $dataSend2 WHERE `$w1` = $w2;");
            $cons->execute();
            
        }
    }
    public function DELETEdata($tbl,$w1,$w2){
        $this->Connection();
        
        
        if(is_string($w2)){
            $w2 = "'".$w2."'";
        }
        $c = $this->pdo;
        $cons = $c->prepare("DELETE FROM `$tbl` WHERE `$w1` = $w2");
        $cons->execute();
        
    }


}




?>