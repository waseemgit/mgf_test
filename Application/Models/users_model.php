<?php
namespace Application\Models;
use Application\Db\db;
use PDO;
/*
 * You can use this class if you have database access for engineers,internal and external table on removeColumns() 
 * function in users Controller you just have to replace $this with $objUsersModel where $objUsersModel is object of this class
 */
class users_model
{
    /*
         * This function is used to get engineers columns from database
         */
    public function getEngineersColumns()
    {
        $db = db::getInstance();
        $q = $db->prepare("DESCRIBE engineers");
        $q->execute();
        $table_fields = $q->fetchAll(PDO::FETCH_COLUMN);
        return $table_fields;
    }	
    
    /*
         * This function is used to get Internal columns from database
         */
    public function getInternalColumns()
    {
        $db = db::getInstance();
        $q = $db->prepare("DESCRIBE internal");
        $q->execute();
        $table_fields = $q->fetchAll(PDO::FETCH_COLUMN);
        return $table_fields;
    }	
    
    /*
         * This function is used to get external columns from database
         */
    public function getExternalColumns()
    {
        $db = db::getInstance();
        $q = $db->prepare("DESCRIBE external");
        $q->execute();
        $table_fields = $q->fetchAll(PDO::FETCH_COLUMN);
        return $table_fields;
    }	

}
?>
