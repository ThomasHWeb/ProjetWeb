<?php

require_once "class_constructeurManager.php";
require_once "../Manager/class_managerRole.php";

class ConstructeurManagerRole extends ConstructeurManager{

    /*
    *----------------------------------------------------------------
    *BODY
    *----------------------------------------------------------------
    */

    public function Cree($Bd){
        return new ManagerRole($Bd);
    }

}

?>
