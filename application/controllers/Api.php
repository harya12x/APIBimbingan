<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, Authorization, Accept, x-requested-with ');
header("Content-Type: application/json; charset=utf-8");
defined('BASEPATH') or exit('No direct script access allowed');
class Api extends CI_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->model('M_data');
  }

  public function index()
  {
    $status = array(
      'status' => 'Ok', 'result' => null

    );
    json_encode($status);
  }

  public function upload()
  {
    $config["upload_path"] = "./bukti/";
    $config["allowed_types"] = "jpg|png|jpeg";
    $config["overwrite"] = true;
    $config["max_size"] = 5000;
    $tgl1 = date("Y-m-d", strtotime(urldecode($this->input->post('dentry'))));
    $data = [
      "kd_bimbingan" => urldecode($this->input->post('kd_bimbingan')),
      "thn_akademik"=> urldecode($this->input->post('thn_akademik')),
      "keterangan" => urldecode($this->input->post('keterangan')),
      "dentry" => $tgl1,
    ];
    $this->load->library("upload", $config);
    if ($data) {
      $uploadfile = $this->upload->do_upload("file");
      if (!$uploadfile) {
        $error = ["error" => $this->upload->display_errors()];
        echo json_encode($error);
      } else {
        $img = $this->upload->data();
        $data["photo"] = $img["file_name"];

        $this->db->insert("bimbingan_capture", $data);
        $status = ["message" => "success"];
        echo json_encode($status);
      }
    } else {
      header("Content-type: application/json");
      $data["foto"] = null;
      $this->db->insert("crud", $data);
      $status = ["message" => "success"];
      echo json_encode($status);
    }
  }

  //GetDataBimbinganInformasi
  public function getakademik(){
   // $kd_informasi = urldecode($this->uri->segment(3));
    $query = $this->M_data->get_session("bimbingan_informasi")->result();
    echo json_encode($query);
  }

  //GetTableSetting
  public function getsetting(){
    $query = $this->M_data->GetData("setting")->result();
    echo json_encode($query);
  }

  // public function getsetting($id){
  //   $id = urldecode($this->uri->segment(3));
  //   $query = $this->M_data->GetData("setting", "id", $id)->result();
  //   echo json_encode($query);
  // }

// GetNamebyID
  public function GetName($kd_bimbingan)
  {

    $npm = urldecode($this->uri->segment(3));
    $query = $this->M_data->GetData("user", "kd_bimbingan", $kd_bimbingan)->result();
    echo json_encode($query);
  }

  //GetDetailHistory
  public function detailhistory($kd_capture){

    $kd_capture = urldecode($this->uri->segment(3));
    $query = $this->M_data->GetData('namatable', "namacolomn", $kd_capture);
    echo json_encode($query);
  }
  
  
  // GetCaptureByID
  public function detailbimbim($kd_capture){

    $kd_capture = urldecode($this->uri->segment(3));
    $query = $this->M_data->GetData('bimbingan_capture', "kd_capture", $kd_capture)->result();
    echo json_encode($query);

  }

  //GetKdBimbim = Kd_bimbim
  public function getbyidbimbim($kd_bimbingan)
  {
    $kd_bimbingan = urldecode($this->uri->segment(3));
    $query = $this->M_data->GetData('bimbingan_capture', "kd_bimbingan", $kd_bimbingan)->result();
    echo json_encode($query); 
  }
  // GetJoinTahunAkademik From Tabel bimbingan_data
  public function sess()
  {
    $query = $this->M_data->sess_thakademik_capture('bimbingan_capture')->result();
    echo json_encode($query);
  }

//GetJoinDataBimbingan Capture
  public function getbimbingan($kd_bimbingan)
  {
    $query = $this->M_data->get_bimbingan('bimbingan_data', $kd_bimbingan)->result();
    echo json_encode($query);
  }

  //API untuk Ionic
  public function Login()
  {
    $npm = urldecode($this->uri->segment(3));
    $password = sha1(urldecode($this->uri->segment(4)));

    $query = $this->M_data->CheckLogin("user", $npm, $password);
    if ($query) {
      $status = [
        "status" => "Ok",
        "result" => $query,
      ];
      echo json_encode($status);
    } else {
      $status = [
        "status" => "Error",
      ];
      echo json_encode($status);
    }
  }

  public function getcapture($kd_capture){

    $kd_capture = urldecode($this->uri->segment(3));
    $query = $this->M_data->GetData('bimbingan_capture', "kd_capture", $kd_capture)->result();
    echo json_encode($query);
  
  }
 
}
