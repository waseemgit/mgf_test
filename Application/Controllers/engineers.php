<?php
namespace Application\Controllers;
use Application\AbstractClasses\users;

class engineers extends users
{
    private  $type = 'engineers';
    private $columns = array(
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
    
    //This function is used to get html data
    public function get_html_data()
    {  
        //If you want to remove any column from fields of user you can pass into argument as array
        $fields = $this->removeColumns(array('id',));        
        $response_data = $this->getAPIResponse();
        $response = json_decode($response_data);
        $records = $this->toArray($response);
        $data = $this->getUserData($records);
        $html = '';        
        $html .= $this->makeHTMLTable($fields,$data[$this->type]);       
        return $html;
    }
    
    /*
         * This function is used to make HTML table for given fields array and data array.
         */
    public function makeHTMLTable($fields,$data)
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
         * These are supposed columns for this user type
         */
    public function getColumns()
    {        
        return $this->columns;
    }
    
     /*
         * This function is used to remove any columns from data Response of API on given columns array as param.
         */
    public function removeColumns($columns_to_remove)
    {   
        $columns = $this->unsetColumns($columns_to_remove,$this->columns);
        return $columns;
    }
    
}