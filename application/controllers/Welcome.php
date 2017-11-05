<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("Usuario_model");
    }

    /*
     * Initial Method
     */
    public function index()
    {
        if ($this->session->userdata("id")) {
            $idUser = $this->session->userdata("id");
            $data['userLikes'] = $this->Usuario_model->getLikesByUser($idUser);
        }
        $data['row'] = $this->Usuario_model->getImages();
        $this->load->view('index', $data);
    }

}
