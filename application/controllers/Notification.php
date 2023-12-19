<?php
class Notification extends MY_Controller{   

    public function index(){
        $this->load->view('notification');
    }

    public function send(){
        $data = $this->input->post();

        $data['notificationMsg'] = (!empty($data['notificationMsg']))?$data['notificationMsg']:"Notification test successfull.";
        $data['notificationTitle'] = (!empty($data['notificationTitle']))?$data['notificationTitle']:"Test Notification";
        $data['payload'] = [];
        $data['callBack'] = base_url('notification');

        $result = $this->masterModel->notify($data);
        $this->printJson($result);
    }
}
?>