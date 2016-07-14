<?php
namespace Application\AbstractClasses;

/*
 * This Abstract Class is used for operations for different users data type
 */

abstract class users
{
    private $type = '';
    /*
         * This function is used to get API response convert it to HTML display it for given user type data.
         */
    public function get_html_data($type)
    {   
        $response_data = $this->getAPIResponse();
        $response = json_decode($response_data['data']);
        $records = $this->toArray($response);
        $split = $this->getSplittedUsers($records);
        $html = '';        
        $html .= $this->makeHTMLTable($response_data[$type.'_fields'],$split[$type]);       
        return $html;
    }
    
    /*
         * This function is used to take array of all users and split them into engineers ,internal and external and return in 3 arrays.
         */    
    private function getSplittedUsers($records = array())
    {
        $engineers = '';
        $internal = '';
        $external = ''; 
        
        foreach($records as $key=>$val)
        {
            switch($key)
            {
                case 'engineers':
                    $engineers = $val;
                break;         

                case 'internal': 
                    $internal = $val;
                break;

                case 'external':
                    $external = $val;
                break;
            }
        }
        return array('engineers' => $engineers,'internal' => $internal,'external' => $external);
    }
    
    
    /*
         * This function is used to get API Response save it in cache file if not already saved. And return data with all 
         * fields of engineers,internal and external users which are assumed in functions 
         * getEngineerColumns(),getInternalColumns(),getExternalColumns() functions respectively. 
         * 
         * Note: We can also remove any fields before displaying them, we just have to pass them in removeColumns as array
         */
    private function getAPIResponse()
    {   
        //If you want to remove any column from fields of engineers,internal or external you can pass into second argument as array
        $engineers_fields = $this->removeColumns('engineers',array('id',));
        $internal_fields = $this->removeColumns('internal',array('id',));
        $external_fields = $this->removeColumns('external',array('id',));
        
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
        $response_data = array(
                                'datamsg' => $datamsg,
                                'data' => $response,
                                'is_live' => $live,
                                'engineers_fields' => $engineers_fields,
                                'internal_fields' => $internal_fields,
                                'external_fields' => $external_fields
                            );
        return $response_data;
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
         * This function is used to make HTML table for given fields array and data array.
         */
    private function makeHTMLTable($fields,$data)
    {   
        $table = '<table border="1">';
        $table .= '<tr class="header">';
        foreach($fields as $k=>$v)
        {
            $table .= '<td><strong>'.strtoupper($v).'<strong></td>';
        }
        $table .= '</tr>';
        foreach($data as $key=>$value)
        {
            $table .= '<tr>';
            foreach($fields as $k=>$v)
            {
                $table .= '<td>'.$value[$v].'</td>';
            }
           $table .= '</tr>';
        }
        $table .= '</table>';
        return $table;
    }
    
    
    /*
         * This function is used to remove any columns from data Response of API on given user type and columns array as params.
         */
    private function removeColumns($type,$columns_to_remove)
    {   
        switch($type)
        {
            case 'engineers':
                $columns = $this->getEngineersColumns(); 
                break;
            case 'internal':
                $columns = $this->getInternalColumns(); 
                break;
            case 'external':
                $columns = $this->getExternalColumns(); 
                break;
        }  
        
        $columns = $this->unsetColumns($columns_to_remove,$columns);
        return $columns;
    }
    
    /*
         * These are supposed columns for Engineers, 
          * There is one function with same name in users model also if we have database access we can fetch columns from engineers table.
          *  But for now we are just supposing static columns
         */
    private function getEngineersColumns()
    {    
        $columns = array(
                        'firstName',
                        'lastName',
                        'DOB',
                        'email',
                        'password',
                        'qualifications',
                        'depot',
                        'field',
                        'level',
                        'salary',
                        'payrollID',
                        );
        return $columns;
    }
    
    /*
         * These are supposed columns for Internal Users, 
          * There is one function with same name in users model also if we have database access we can fetch columns from internal table.
          *  But for now we are just supposing static columns
         */
    private function getInternalColumns()
    {   
         $columns = array(
                        'firstName',
                        'lastName',
                        'DOB',
                        'email',
                        'password',
                        'jobTitle',
                        'salary',
                        'location',
                        'payrollID',
                        );
        return $columns;
    }
    
    /*
         * These are supposed columns for External, 
          * There is one function with same name in users model also if we have database access we can fetch columns from external table.
          *  But for now we are just supposing static columns
         */
    private function getExternalColumns()
    {   
        $columns = array(
                        'firstName',
                        'lastName',
                        'DOB',
                        'email',
                        'password',
                        'ipAddress',
                        'company',
                        'jobTitle',
                        );
        return $columns;
    }
    
    /*
         This function is internally used for unsetting columns which are not required
         */
    private function unsetColumns($columns = array(),$table_columns)
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
    private function toArray($obj)
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
