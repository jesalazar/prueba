<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuario_model extends CI_Model {

	/*
	 * Metodo para realizar login
	 */
	public function login($correo, $password)
	{
		$this->db->where("correo", $correo);
		$this->db->where("password", $password);
		$res = $this->db->get("usuario");
		if ($res->num_rows()>0) {
			return $res->row();
		} else {
			return false;
		}
	}

	/*
	 * Metodo para insertar datos en una tabla
	 */
	public function insert($table, $data)
	{
		$this->db->insert($table, $data);
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	/*
	 * Metodo para saber si puede subir mas fotos
	 */
	public function canUpload()
	{
		$this->db->where("usuario_id", $this->session->userdata("id"));
		$res = $this->db->get("usuario_fotos");
		if ($res->num_rows()<10) {
			return true;
		} else {
			return false;
		}
	}

	/*
	 * Metodo para obtener registros de la tabla usuario_fotos
	 */
	public function getImages()
	{
		$this->db->select('*');
		$this->db->from('usuario_fotos');
		$consulta = $this->db->get();
		return $consulta->result();
	}

	/*
	 * Metodo para saber los likes del usuario logueado
	 */
	public function getLikesByUser($idUser)
	{
		$this->db->select('foto_id, me_gusta');
		$this->db->from('usuario_votos');
		$this->db->where("usuario_vota_id", $idUser);
		$consulta = $this->db->get();
		return $consulta->result();
	}

	/*
	 * Metodo para insertar/actualizar likes
	 */
	public function likes($data)
	{
		$this->db->where('usuario_vota_id', $data['usuario_vota_id']);
		$this->db->where('foto_id', $data['foto_id']);
		if (array_key_exists('action', $data)) {
			unset($data['action']);
			$this->db->insert('usuario_votos', $data);
		} else {
			$this->db->update('usuario_votos', $data); 
		}
		if ($this->db->affected_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

}
