<?php
class DbUtility extends CI_Controller{
    public function __construct(){
        parent::__construct();
    }

    /* 
    *   Created BY : Milan Chauhan
    *   Created AT : 18-08-2023
    *   Required Data : password in query param
    *   Note : Export Sql file from live
    */
    public function exportDBfile(){
        if($this->uri->segment(3) == "Nbt-".date("dmY")):
            $NAME=$this->db->database;
            $SQL_NAME = $NAME."_".date("d_m_Y_H_i_s").'.sql';
            $this->load->dbutil();
            $prefs = [
                'format' => 'zip',
                'filename' => $SQL_NAME
            ];
            $backup =& $this->dbutil->backup($prefs);    
            $db_name = $NAME."_".date("d_m_Y_H_i_s").'.zip';    
            $save = 'assets/db/'.$db_name;
            $this->load->helper('file');
            write_file($save, $backup);
            $this->load->helper('download');
            force_download($db_name, $backup); 
        else:
            $this->load->view('page-403');
        endif;
    }

    /* 
    *   Created BY : Milan Chauhan
    *   Created AT : 18-08-2023
    *   Required Data : password
    *   Note : Return SQL Querys from live Database
    */
    public function syncLiveDB($password = ""){
        if($password == "Nbt-".date("dmY")):
            $NAME=$this->db->database;
            $SQL_NAME = $NAME."_".date("d_m_Y_H_i_s").'.sql';
            $this->load->dbutil();
            /* $prefs = [
                'format' => 'text',
                'filename' => $SQL_NAME,
                'newline' => "\r\n"
            ]; */
            /* $backup_temp = $this->dbutil->backup($prefs);
            $backup =& $backup_temp; */

            $prefs = [
                'format' => 'zip',
                'filename' => $SQL_NAME,
                'newline' => "\r\n"
            ];
            $backup_temp = $this->dbutil->backup($prefs);
            $backup =& $backup_temp;
            $db_name = $NAME."_".date("d_m_Y_H_i_s").'.zip';    
            $save = 'assets/db/'.$db_name;
            $this->load->helper('file');
            write_file($save, $backup);

            $zip = new ZipArchive;
            if ($zip->open($save) === TRUE):
                $zip->extractTo('assets/db/');
                $zip->close();

                print json_encode(['status'=>1,'message'=>"extract zip file successfully.",'db_file'=>base_url('assets/db/'.$SQL_NAME)]);exit;
            else:
                print json_encode(['status'=>0,'message'=>"Failed to extract zip file.",'db_file'=>""]);exit;
            endif;            
        else:
            print json_encode(['status'=>0,'message'=>"Invalid Password.",'db_file'=>""]);exit;
        endif;        
    }

    /* 
    *   Created BY : Milan Chauhan
    *   Created AT : 18-08-2023
    *   Post Data : password
    *   Note : Get SQL Querys from live Database and Import in Local Database
    */
    public function syncDbQuery(){
        if($_SERVER['HTTP_HOST'] == 'localhost'):
            $data = $this->input->post();

            $curlSync = curl_init();
            curl_setopt_array($curlSync, array(
                CURLOPT_URL => "https://admix.scubeerp.in/dbUtility/syncLiveDB/".$data['password'],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array('Content-Type: application/json')
            ));

            $response = curl_exec($curlSync);
            $error = curl_error($curlSync);
            curl_close($curlSync);

            if(!empty($error)):
                print json_encode(['status'=>0,'message'=>'Somthing went wrong. cURL Error #: '. $error]);exit;
            else:
                $response = json_decode($response);	
                if($response->status == 0):
                    print json_encode(['status'=>0,'message'=>'Somthing went wrong. Error #: '. $response->message]);exit;
                else:
                    if(!empty($response->db_file)):
                        $this->load->helper('file');
                        $sqlContent = file_get_contents($response->db_file);
                        $this->load->database();

                        if ($sqlContent !== false):
                            $queries = explode('\r\n', $sqlContent);
                        
                            foreach ($queries as $query):
                                if (!empty(trim($query))):
                                    if (!$this->db->query($query)):
                                        $error_message = $this->db->error()['message'];
                                        log_message('error', 'Query error: ' . $error_message);
                                    endif;
                                endif;
                            endforeach;
                        
                            print json_encode(['status'=>1,'message'=>'Database sync successfully.']);exit;
                        else:
                            print json_encode(['status'=>0,'message'=>'Failed to read the SQL file.']);exit;
                        endif;
                    else:
                        print json_encode(['status'=>0,'message'=>'Somthing went wrong. Error #: SQL QUERY not found.']);exit;
                    endif;
                endif;
            endif;   
        else:
            print json_encode(['status'=>0,'message'=>'Something went wrong. Error #: you cant sync. because you are in live project.']);exit;
        endif;
    }

    public function dbForm(){
        $this->load->view("db_form");
    }
}
?>