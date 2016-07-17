<?php
namespace Application\AbstractClasses;

/*
 * This Abstract Class is used for operations for different users data type
 */

abstract class users
{
    private $user_types = array(
                                'engineers',
                                'internal',
                                'external',
                                );
    abstract protected function get_html_data();
    abstract protected function makeHTMLTable($fields,$data);
    abstract protected function getColumns();
    abstract protected function removeColumns($columns_to_remove);

    /*
         * This function is used to take array of all users and split them into types and return in 3 arrays.
         */    
    public function getUserData($records = array())
    {
        $usersData = array();
        foreach($records as $key=>$val)
        {
            foreach($this->user_types as $k => $usertype)
            {
                if($key == $usertype)
                {
                 ${$usertype} = $val;
                }
            }
        }
        foreach($this->user_types as $k => $usertype)
        {
            $usersData[$usertype] = ${$usertype};
        }
        return $usersData;
    }
    
    
    /*
         * This function is used to get API Response save it in cache file if not already saved and return response data.
         */
    public function getAPIResponse()
    {   
        //Calling API and Fetching records from it with url and Credentials defined in config file
        $url = MGF_API_URL;
        $data = unserialize(MGF_CREDENTIALS);
        
        //If there is no cache already call API and get live data
        if(filesize(dirname(__FILE__)."/../../cache.txt") == 0)
        {            
            $response = $this->getUsers($data,$url);
            $this->cacheData($response);
            $datamsg = 'This data is live';
            $live = true;
        }
        else 
        {
            $datamsg = 'This data is cached';  
            $response = file_get_contents(dirname(__FILE__)."/../../cache.txt");
            $live = false;
        }
        return $response;
    }
    
    /*
         * This function is used to save API response data in a cache.txt file.
         */
    private function cacheData($data)
    {
        $file = dirname(__FILE__)."/../../cache.txt";
        $fileHandler = fopen($file, "w") or die("Unable to open file!");
        fwrite($fileHandler, $data);
        fclose($fileHandler);
    }    
    
    /*
         * This function is used to empty data in a cache.txt file.
         */
    private function resetCache()
    {   
        $file = dirname(__FILE__)."/../../cache.txt";
        $f = @fopen($file, "r+");
        if ($f !== false) 
        {
            ftruncate($f, 0);
            fclose($f);
        }
    }
    
        
    /*
         This function is internally used for unsetting columns which are not required
         */
    public function unsetColumns($columns = array(),$table_columns)
    {   
        if(count($columns)>0)
        {
            foreach($columns as $key=>$column_to_be_deleted)
            {            
                if(($key = array_search($column_to_be_deleted, $table_columns)) !== false) 
                {
                    unset($table_columns[$key]);
                }
            }
        }
        return $table_columns;
    }
    
    /*
         This function is used to call API using curl with given credentials as Data and url and return response
         */
    private function getUsers($data,$url)
    {   
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_POST,true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $resp = curl_exec($ch);
        curl_close($ch);   
        return $resp;
    }

    /*
         This function is internally used for to convert obj recursively into array
         */
    public function toArray($obj)
    {   
        if (is_object($obj)) 
            $obj = (array)$obj;
        if (is_array($obj)) 
        {
            $new = array();
            foreach ($obj as $key => $val) 
            {
                $new[$key] = $this->toArray($val);
            }
        } 
        else 
        {
            $new = $obj;
        }
        return $new;
    }    
 
}
/*
End of class
 *  */
?>
