<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Usuario_model');
    }

    /*
     * Initial Method
     */
    public function index()
    {
        $data['userId'] = '';
        $data['userName'] = '';
        $data['userLikes'] = '';
        if ($this->session->userdata("id")) {
            $data['userId'] = $this->session->userdata('id');
            $data['userName'] = $this->session->userdata('nombre');
            $data['userLikes'] = $this->Usuario_model->getLikesByUser($data['userId']);
        }
        $data['row'] = $this->Usuario_model->getImages();
        $this->load->view('index', $data);
    }

}
