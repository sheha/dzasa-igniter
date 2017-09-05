<?php

defined('BASEPATH') OR exit('No direct script access allowed');

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
    public function Authentification() {

        $notif = array();
        $email = $this->input->post('email');
        $password = Utils::hash('sha1', $this->input->post('password'), AUTH_SALT);

        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('email', $email);
        $this->db->where('password', $password);
        $this->db->limit(1);

        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            $row = $query->row();
            if ($row->is_active != 1) {
                $notif['message'] = 'Your account is disabled !';
                $notif['type'] = 'warning';
            } else {
                $sess_data = array(
                    'users_id' => $row->users_id,
                    'first_name' => $row->first_name,
                    'last_name' => $row->last_name,
                    'email' => $row->email
                );
                $this->session->set_userdata('logged_in', $sess_data);
                $this->update_last_login($row->users_id);
            }
        } else {
            $notif['message'] = 'Username or password incorrect !';
            $notif['type'] = 'danger';
        }

        return $notif;
    }

    /*
     * 
     */

    private function update_last_login($users_id) {
        $sql = "UPDATE users SET last_login = NOW() WHERE users_id=" . $this->db->escape($users_id);
        $this->db->query($sql);
    }

    /*
     * Function covering registration process
     */

    public function register() {
        $notif = array();
        $data = array(
            'first_name' => $this->input->post('first_name'),
            'last_name' => $this->input->post('last_name'),
            'email' => $this->input->post('email'),
            'password' => Utils::hash('sha1', $this->input->post('password'), AUTH_SALT),
            'is_active' => $this->input->post('is_active') ? : 0
        );
        $this->db->insert('users', $data);
        $users_id = $this->db->insert_id();
        if ($this->db->affected_rows() > 0) {
            $notif['message'] = 'Saved successfully';
            $notif['type'] = 'success';
            unset($_POST);
        } else {
            $notif['message'] = 'Something wrong !';
            $notif['type'] = 'danger';
        }
        return $notif;
    }

    /*
     * Check if the input email exists
     */

    public function check_email($email) {
        $sql = "SELECT * FROM users WHERE email = " . $this->db->escape($email);
        $res = $this->db->query($sql);
        if ($res->num_rows() > 0) {
            $row = $res->row();
            return $row;
        }
        return null;
    }

    public function check_get_user_creds($email, $password = null ){

    	$result = null;

    	$this->db->select('*');
	    $this->db->from('users');
	    $this->db->where('email', $email);

	    if ( isset( $password ) ) {

	    	$this->db->where('password', $password);
	    	$this->db->limit(1);

	    	return $this->db->get();
	    }

	    $query = $this->db->get();

	    return $result = ( $query->num_rows() > 0 ) ? $query->row() : $result ;

    }

}
