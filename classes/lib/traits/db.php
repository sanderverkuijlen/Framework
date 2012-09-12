<?php
trait Db{

    /**
     * @return DbCon
     */
    private function dbCon(){
        return DbCon::getInstance();
    }
}
?>