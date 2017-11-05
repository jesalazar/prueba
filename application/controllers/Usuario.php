<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuario extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("Usuario_model");
    }

    /*
     * Metodo para realizar login
     */
    public function login()
    {
        if ($this->input->is_ajax_request()) {
            if ($this->setSession($this->input->post('email'), sha1($this->input->post('pass')))) {
                echo true;
            } else {
                echo false;
            }
        }
    }

    /*
     * Metodo para almacenar datos en session
     */
    private function setSession($correo, $password)
    {
        $res = $this->Usuario_model->login($correo, $password);
        if (!$res) {
            return false;
        } else {
            $data = [
                "id" => $res->id,
                "nombre" => $res->nombre
            ];
            $this->session->set_userdata($data);
            return true;
        }
    }

    /*
     * Metodo para realizar logout
     */
    public function logout()
    {
        $this->session->sess_destroy();
        header('Location: /prueba');
        exit;
    }

    /*
     * Metodo para registrar un usario
     */
    public function newUser()
    {
        $datos = $this->input->post(); // Recibe datos via post
        $datos['password'] = sha1($this->input->post('password'));

        $this->form_validation->set_rules('correo', '--Correo ya existe--', 'trim|required|valid_email|is_unique[usuario.correo]');
        $this->form_validation->set_rules('identificacion', '--Identificación ya existe--', 'trim|required|is_unique[usuario.identificacion]');

        if ($this->form_validation->run() == true) {
            if ($this->Usuario_model->insert("usuario", $datos) == true) {
                $this->setSession($datos['correo'], $datos['password']);
                echo true;
            } else {
                echo false;
            }
        } else {
            echo validation_errors("<li>","</li>");
        }
    }

    /*
     * Metodo para subir foto
     */
    public function upload()
    {
        // Valida si puede realizar esta accion
        if ($this->Usuario_model->canUpload()) {
            $date = new DateTime();
            $date = $date->getTimestamp();
            $foto = $_FILES['foto']['tmp_name'];
            $ruta = $_SERVER["DOCUMENT_ROOT"].'/prueba/assets/img/'.$date.$_FILES['foto']['name'];
            $rutaDB = 'assets/img/'.$date.$_FILES['foto']['name'];
            move_uploaded_file($foto, $ruta);
            $datos = [
                "foto"        => $rutaDB,
                "descripcion" => $this->input->post('descripcion'),
                "usuario_id"  => $this->session->userdata("id")
            ];
            if ($this->Usuario_model->insert("usuario_fotos", $datos)) {
                echo true;
            }
        } else {
            echo false;
        }
    }

    /*
     * Metodo para dar like
     */
    public function likes()
    {
        if ($this->input->is_ajax_request()) {
            $datos = $this->input->post();
            $datos['usuario_vota_id'] = $this->session->userdata("id");
            if ($this->Usuario_model->likes($datos)) {
                echo true;
            } else {
                echo false;
            }
        }
    }

}
