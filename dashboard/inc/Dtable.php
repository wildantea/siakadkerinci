<?php
//error_reporting(0);
/*
 * Script:    DataTables PDO server-side script for PHP and MySQL
 * CopyLeft: March 2016 - wildantea, wildantea.com
 * Email : wildannudin@gmail.com
 */
class DTable extends Database
{
    private $total_filtered;
    private $record_total;
    public $offset;
    public $data = array();
    public $request;
    public $search_request;
    public $is_numbering = 0;
    public $primary_key;
    public $order_by="";
    public $order_by_custom="";
    public $order_type="";
    public $group_by="";
    public $disable_search=array();
    public $callback = array();
    public $debug = 0;

    public $debug_sql="";

     function __construct($host,$port,$db_username,$db_password,$db_name)
    {
        parent::__construct($host,$port,$db_username,$db_password,$db_name);
    }

    //filter data
    public function get_column($col)
    {
        $col = array_diff($col, $this->disable_search);
        foreach ($col as $key) {
            $keys   = $key . " LIKE ?";
            $mark[] = $keys;
        }

        $im = implode(' OR  ', $mark);
        return $im;
    }

    public function get_value($col, $value)
    {
        $col = array_diff($col, $this->disable_search);
        foreach ($col as $key) {
            $val      = '%' . $value . '%';
            $result[] = $val;
        }

        return $result;
    }


    public function result_data($sql,$prepare_data=null)
    {
        $result = $this->query($sql,$prepare_data);
        if ($this->getErrorMessage()!="") {
            $this->set_callback(array('error_data' => $this->getErrorMessage(),'query_detail_result' => $sql));
        } else {
           return $result;
        }



    }

    public function set_total_record($sql,$prepare_data=null)
    {

        if ($this->group_by!="") {
            $jml_data = $this->query($sql,$prepare_data)->rowCount();
        } else {
            $sql_for_counting = stristr($sql, 'from');
            //$sql_for_counting = str_replace("having", "where", $sql_for_counting);

            $jml_datas = $this->fetch_custom_single("select count(*) as jml ".$sql_for_counting,$prepare_data);
            $jml_data = $jml_datas->jml;
        }

        if ($this->getErrorMessage()!="") {
            $this->set_callback(array('error_data' => $this->getErrorMessage(),'query_detail_total' => "select count(*) as jml ".$sql_for_counting));
        } else {
            //total filtered default
            $this->record_total = $jml_data;
        }


    }


    public function set_total_filtered($sql,$prepare_data=null)
    {
        //echo $sql;
        if ($this->group_by!="") {
            $jml_data = $this->query($sql,$prepare_data)->rowCount();
        } else {
            $sql_for_counting = stristr($sql, 'from');
            //$sql_for_counting = str_replace("having", "where", $sql_for_counting);

            $jml_datas = $this->fetch_custom_single("select count(*) as jml ".$sql_for_counting,$prepare_data);
            $jml_data = $jml_datas->jml;
        }

        if ($this->getErrorMessage()!="") {
            $this->set_callback(array('error_data' => $this->getErrorMessage(),'query_detail_filter' => "select count(*) as jml ".$sql_for_counting));
        } else {
            //total filtered default
            $this->total_filtered = $jml_data;
        }


    }


    public function join_value($search_value,$where_data=null)
    {

        if ($where_data!=null) {
            $where_data = array_values($where_data);
        } else {
            $where_data = array();
        }
        $res = array_merge($where_data,$search_value);
        return $res;
    }


    //create numbering column
    public function number($number)
    {
        $requestData   = $_REQUEST['start']+$number;
        return $requestData;

    }

    public function set_numbering_status($status) {
         $this->is_numbering = $status;
    }

    public function get_numbering_status()
    {
        return $this->is_numbering;
    }

    public function set_order_by_custom($val)
    {
        $this->order_by_custom = $val;
    }
    public function set_order_by($val)
    {
        $this->order_by = $val;
    }
    public function get_order_by_custom()
    {
        return $this->order_by_custom;
    }

     public function set_group_by($val)
    {
        $this->group_by = $val;
    }

    public function get_order_by()
    {
        return $this->order_by;
    }



    public function set_order_type($val)
    {
        $this->order_type = $val;
    }


public function get_custom($sql, $columns, $prepare_data=null)
    {
        if ($prepare_data !== null) {
            $prepare_data = array_values($prepare_data);
        }

        $requestData = $_REQUEST;
        $this->request = $requestData;

        $offset = $requestData['start'];
        $this->offset = $offset ? $offset : 0;

        // Default ordering
        $default_order = $this->order_by_custom ?: $this->order_by;
        $default_order_type = $this->order_type;

        // Determine ordering
        $order_sql = "";
        if (!empty($requestData['order']) && isset($requestData['order'][0])) {
            $order_column_idx = $requestData['order'][0]['column'];
            $order_dir = $requestData['order'][0]['dir'];
            
            // Adjust column index if numbering is enabled
            if ($this->is_numbering && $order_column_idx > 0) {
                $order_column_idx--;
            }
            
            if (isset($columns[$order_column_idx])) {
                $order_sql = " ORDER BY " . $columns[$order_column_idx] . " " . strtoupper($order_dir);
            }
        } elseif ($default_order) {
            // Use default ordering if no client-side ordering is specified
            $order_sql = " ORDER BY " . $default_order . " " . strtoupper($default_order_type);
        }

        // Search handling
        if (!empty($requestData['search']['value'])) {
            $this->search_request = $requestData['search']['value'];
            
            $after_remove = preg_replace('#\((([^()]+|(?R))*)\)#', "", $sql);
            $condition = (strpos(strtolower($after_remove), "where") !== false || 
                         strpos(strtolower($after_remove), "having") !== false) ? "AND" : "WHERE";

            $search_value = $this->get_value($columns, $this->search_request);
            $join = $this->join_value($search_value, $prepare_data);

            $sql .= " $condition (" . $this->get_column($columns) . ")";
            $sql .= " " . $this->group_by . $order_sql;
            
            $this->set_total_filtered($sql, $join);

            $length = ($requestData['length'] < 0) ? "" : 
                     " LIMIT " . $requestData['start'] . "," . $requestData['length'];
            $sql .= $length;

            $result = $this->result_data($sql, $join);
        } else {
            $sql .= " " . $this->group_by . $order_sql;
            
            if ($this->debug == 1) {
                $this->set_callback(array('detail_sql_total' => $sql));
            }

            $this->set_total_record($sql, $prepare_data);
            $this->set_total_filtered($sql, $prepare_data);

            $length = ($requestData['length'] < 0) ? "" : 
                     " LIMIT " . $requestData['start'] . "," . $requestData['length'];
            $sql .= $length;

            $result = $this->result_data($sql, $prepare_data);
        }

        return $result;
    }


    public function get_offset()
    {
        return $this->offset;
    }

    public function set_sql_debug($debug) {
        $this->debug_sql = $debug;
    }
    public function get_sql_debug() {
        return $this->debug_sql;
    }

    public function set_callback($callback) {
        $this->callback = $callback;
    }
    public function get_callback() {
        return $this->callback;
    }

    public function set_debug($debug) {
        $this->debug = $debug;
    }


    public function create_data()
    {
        $data      = $this->data;
        $json_data = array(
            "draw" => intval($this->request['draw']),
            "recordsTotal" => intval($this->record_total),
            "recordsFiltered" => intval($this->total_filtered),
            "data" => $data // total data array
        );
        if (!empty($this->get_callback())) {
            $json_data = array_merge($this->get_callback(),$json_data);
        }
        echo json_encode($json_data);
        // send data as json format
    }

    public function set_data($data)
    {
        $this->data = $data;
    }

}

?>
