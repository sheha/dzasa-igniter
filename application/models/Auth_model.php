<?php

defined( 'BASEPATH' ) OR exit( 'No direct script access allowed' );

/*
 * Class Auth_model : Basic auth model - defines authentication properties,
 * checking and registering methods.
 */

class Auth_model extends CI_Model {

	function __construct() {
		parent::__construct();
	}

	/*
	 * Main auth method.Will check for the given email/pass combination,
	 * and return the session object with account data if found, otherwise
	 * returns 'account disabled' or 'account doesn't exist'
	 */
	public function Authenticate() {

		$notif    = array();
		$email    = $this->input->post( 'email' );
		$password = Utils::hash( 'sha1', $this->input->post( 'password' ), AUTH_SALT );

		$this->db->select( '*' );
		$this->db->from( 'users' );
		$this->db->where( 'email', $email );
		$this->db->where( 'password', $password );
		$this->db->limit( 1 );

		$query = $this->db->get();

		if ( $query->num_rows() == 1 )
		{
			$row = $query->row();
			if ( $row->is_active != 1 )
			{
				$notif['message'] = 'Your account is disabled! Contact your sysadmin.';
				$notif['type']    = 'warning';
			} else
			{
				$sess_data = array(
					'id'         => $row->id,
					'first_name' => $row->first_name,
					'last_name'  => $row->last_name,
					'email'      => $row->email
				);
				$this->session->set_userdata( 'logged_in', $sess_data );
				$this->update_last_login( $row->id );
			}
		} else
		{
			$notif['message'] = 'Username or password incorrect !';
			$notif['type']    = 'danger';
		}

		return $notif;
	}

	/*
	 *
	 */

	private function update_last_login( $users_id ) {
		$sql = "UPDATE users SET last_login = NOW() WHERE id=" . $this->db->escape( $users_id );
		$this->db->query( $sql );
	}

	public function change_password( $newpass, $email ) {

		$password = Utils::hash( 'sha1', $newpass, AUTH_SALT );
		$sql = "UPDATE users SET password =" . $this->db->escape( $password ) . " WHERE email=" . $this->db->escape(
			$email );

		$this->db->query( $sql );

	}

	/*
	 * Function covering registration process
	 */

	public function register() {
		$notif = array();
		$data  = array(
			'first_name' => $this->input->post( 'first_name' ),
			'last_name'  => $this->input->post( 'last_name' ),
			'email'      => $this->input->post( 'email' ),
			'password'   => Utils::hash( 'sha1', $this->input->post( 'password' ), AUTH_SALT ),
			'is_active'  => $this->input->post( 'is_active' ) ?: 0
		);
		$this->db->insert( 'users', $data );
		$user_id = $this->db->insert_id();
		if ( $this->db->affected_rows() > 0 )
		{
			$notif['message'] = 'User Creation Successful!';
			$notif['type']    = 'success';
			unset( $this->input->post );
		} else
		{
			$notif['message'] = 'Something wrong !';
			$notif['type']    = 'danger';
		}

		return $notif;
	}

	/*
	 * Check if the input email exists
	 */

	public function check_email( $email ) {
		$sql = "SELECT * FROM users WHERE email = " . $this->db->escape( $email );
		$res = $this->db->query( $sql );
		if ( $res->num_rows() > 0 )
		{
			$row = $res->row();

			return $row;
		}

		return NULL;
	}

}
