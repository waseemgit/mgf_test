<?php
namespace Application\Controllers;
use Application\Lib\Template as T;
use Application\Models\users_model;
use SimpleXMLElement;
use DOMDocument;
use Application\Lib\XMLParser;
use DateTime;
use Application\InterfaceClasses\users;

/*
 * This Class is used for all operations for users mainly getting API response and converting it to different formats and display them on HTML Page. 
 * This class is highly reusable and in current structure its acting as a controller
 */

class view implements users
{
    private $params = array();  
    private $engineers;
    private $internal;
    private $external;
    /*
     * Constructor to gets and set parameters array
     */
    function __construct($params) 
    {
        $this->params   =   $params;
        $this->engineers = new engineers();
        $this->internal = new internal();
        $this->external = new external(); 
    }    
    
    /*
         * This function is used as default function for controller users
         */
    public function index()
    {
        //If there is no cache already call API and get live data
        if(filesize(dirname(__FILE__)."/../../cache.txt") == 0)
        {            
            $datamsg = 'This data is live';
            $live = true;
        }
        else 
        {
            $datamsg = 'This data is cached';  
            $live = false;
        }
        
        
       
        //Prepare Data for default View in grid for file Views/users.php
        $data = array(
                        'engineersHTMLTable' => $this->engineers->get_html_data(),
                        'internalHTMLTable' => $this->internal->get_html_data(),
                        'externalHTMLTable' => $this->external->get_html_data(),
                        'datamsg' => $datamsg,
                        'live' => $live,
            
                    );
        
        $this->showHTML($data);              
    }
    
    public function showHTML($data) 
    {
        //Passing data to Template class
        $objT = new T($data);  
        //Calling main function of Template which have main Template/Theme we can change later on and include Views/users.php in body of main template
        $objT->main('users');
    }
    
    /*
         * This function is used to get API response convert it to xml format and display it on HTML page
         */
    public function get_xml()
    {        
        $response_data = $this->getAPIResponse();        
        $response = json_decode($response_data['data']);
        $records = $this->toArray($response);
        
        $xml = XMLParser::encode($records);
        $dom = new DOMDocument;
        $dom->preserveWhiteSpace = FALSE;
        $dom->loadXML($xml->asXML());
        $dom->formatOutput = TRUE;
        echo "<pre>";echo htmlentities($dom->saveXml());
    }
    
    /*
         * This function is used to get API response and display it on HTML page as JSON
         */
    public function get_json()
    {        
        $response_data = $this->getAPIResponse();
        $data = str_replace("{", "{<br>", $response_data['data']);        
        $data = str_replace("}", "}<br>", $data);
        echo $data;
    }
    
    /*
         * This function is used to get API response convert it to CSV format and display it on HTML page
         */
    public function get_csv()
    {   
        $response_data = $this->getAPIResponse();
        $response = json_decode($response_data['data']);
        $records = $this->toArray($response);
        $split = $this->getSplittedUsers($records);
        $csv_html = '';
        $csv_html .= "<br><span class='csv_users_headings'>Engineers CSV Data</span><br>";
        $csv_html .= $this->makeHTMLCsv($response_data['engineers_fields'],$split['engineers']); 
        $csv_html .= "<br><span class='csv_users_headings'>Internal CSV Data</span><br>";
        $csv_html .= $this->makeHTMLCsv($response_data['internal_fields'],$split['internal']); 
        $csv_html .= "<br><span class='csv_users_headings'>External CSV Data</span><br>";
        $csv_html .= $this->makeHTMLCsv($response_data['external_fields'],$split['external']);        
        echo $csv_html;
    }
    
    /*
         * This function is used to get API response convert it to HTML display it.
         */
    public function get_html()
    {   
        $response_data = $this->getAPIResponse();
        $response = json_decode($response_data['data']);
        $records = $this->toArray($response);
        $split = $this->getSplittedUsers($records);
        $html = '';
        $html .= "<br><span class='html_users_headings'>Engineers</span><br>";
        $html .= $this->makeHTMLTable($response_data['engineers_fields'],$split['engineers']); 
        $html .= "<br><span class='html_users_headings'>Internal</span><br>";
        $html .= $this->makeHTMLTable($response_data['internal_fields'],$split['internal']); 
        $html .= "<br><span class='html_users_headings'>External</span><br>";
        $html .= $this->makeHTMLTable($response_data['external_fields'],$split['external']);        
        echo $html;
    }
    
    /*
         * This function is used to get average salary of selected User type.
         */
    private function get_average_salary($user_type)
    {    
        $response_data = $this->getAPIResponse();
        $response = json_decode($response_data['data']);
        $records = $this->toArray($response);
        $split = $this->getSplittedUsers($records);
        $average = '';
        if($user_type=='engineers')
        {
            //Getting average for Engineers
            $total_sum_eng = array_sum(array_column($split['engineers'], 'salary'));
            $total_engineers = count($split['engineers']);
            $averageEngineers = $total_sum_eng/$total_engineers;
            $average = sprintf ("%.2f", $averageEngineers);
        }
        else if($user_type=='internals')
        {
            //Getting average for Internals
            $total_sum_int = array_sum(array_column($split['internal'], 'salary'));
            $total_internals = count($split['internal']);
            $averageInternals = $total_sum_int/$total_internals;
            $average = sprintf ("%.2f", $averageInternals);
        }
        return $average;        
    }
    
    /*
         * This function is used to get average date with input array of many dates.
         */
    private function get_average_date($dates)
    {   
        $total = 0; 
        foreach ($dates as $date)
        {
            $parts = explode("-", $date);

            $day = $parts[0];
            $month = $parts[1];
            $year = $parts[2];

            if(strlen($year)==2)
            {
                $dt = DateTime::createFromFormat('y', $year);
                $year = $dt->format('Y');            
                $date = $day.'-'.$month.'-'.$year;
            }
            $total += strtotime($date);
        }
        return date('Y-m-d',($total/count($dates))); 
    }
    
    /*
         * This function is used to get average age in Years ,Months and Days of selected User Type.
         */
    private function get_average_age($user_type)
    {   
        $response_data = $this->getAPIResponse();
        $response = json_decode($response_data['data']);
        $records = $this->toArray($response);
        $split = $this->getSplittedUsers($records);
        $average = '';
        
        if($user_type=='engineers')
        {
            //Getting DOB  for all Engineers
            $dates = array_column($split['engineers'], 'DOB');
        }        
        else if($user_type=='internals')
        {
            //Getting DOB  for all internals
            $dates = array_column($split['internal'], 'DOB');
            
        }
        else if($user_type=='externals')
        {
            //Getting DOB  for all external
            $dates = array_column($split['external'], 'DOB');
        }        
        
        $average_date = $this->get_average_date($dates);
        $from = new DateTime($average_date);
        $to   = new DateTime('today');
        return 'Average Age='.$from->diff($to)->y.' Years ,'.$from->diff($to)->m.' Months ,'.$from->diff($to)->d.' Days';        
    }
    
    
    /*
         * This function is used to print average salary of Engineers.
         */
    public function get_average_salary_engineers()
    {   
        echo $this->get_average_salary('engineers');             
    }
    
    
    /*
         * This function is used to print average salary of Internal user.
         */
    public function get_average_salary_internal()
    {   
        echo $this->get_average_salary('internals');             
    }
    
    /*
         * This function is used to print average age of Engineers.
         */
    public function get_average_age_engineers()
    {   
        echo $this->get_average_age('engineers');             
    }
    
    /*
         * This function is used to print average age of Internal users.
         */
    public function get_average_age_internals()
    {   
        echo $this->get_average_age('internals');             
    }
    
    /*
         * This function is used to print average age of External users.
         */
    public function get_average_age_externals()
    {   
        echo $this->get_average_age('externals');             
    }    
    
    /*
         * This function is used to reset cache when clicked on Reset Cache.
         */
    public function reset()
    {   
        $this->resetCache();
        header('location:'.BASE_URL.'/');exit;
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
         * This function is used to make CSV data displayed as HTML for given fields array and data array.
         */
    private function makeHTMLCsv($fields,$data)
    {   
        $csv_data = '';
        $csv_data .= '<strong>'.implode(",",$fields).'</strong><br>';
        foreach($data as $key=>$value)
        {   
            $primary_key = $key;//just for future use
            foreach($fields as $k=>$v)
            {
                $csv_data .= ''.$value[$v].',';
            }
           $csv_data = rtrim($csv_data);
           $csv_data .= '<br>';
        }        
        return $csv_data;
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