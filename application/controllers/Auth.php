<?php

defined( 'BASEPATH' ) OR exit( 'No direct script access allowed' );

class Auth extends CI_Controller {

	function __construct() {
		parent::__construct();

		Utils::no_cache();
		// User redirected further to the Persons
		if ( $this->session->userdata( 'logged_in' ) ) {
			redirect( base_url( 'person' ) );
			exit;
		}
	}


	public function index() {
		redirect( base_url( 'auth/login' ) );
	}


	public function login() {
		$data['title'] = 'Login';
		$this->load->model( 'auth_model' );

		if ( count( $this->input->post() ) ) {
			$this->load->helper( 'security' );
			$this->form_validation->set_rules( 'email', 'Email address', 'trim|required|valid_email|xss_clean' );
			$this->form_validation->set_rules( 'password', 'Password', 'trim|required|xss_clean' );

			if ( $this->form_validation->run() == false ) {
				$data['notif']['message'] = validation_errors();
				$data['notif']['type']    = 'danger';
			} else {
				$data['notif'] = $this->auth_model->Authentification();
			}
		}

		if ( $this->session->userdata( 'logged_in' ) ) {
			redirect( base_url( 'person' ) );
			exit;
		}

		$data['main_content'] = 'auth/login';
		$this->load->view( 'includes/template', $data );

	}


	public function register() {
		$data['title'] = 'Register';
		$this->load->model( 'auth_model' );

		if ( count( $this->input->post() ) ) {
			$this->load->helper( 'security' );

			$this->form_validation->set_rules( 'first_name', 'First name', 'trim|required' );
			$this->form_validation->set_rules( 'last_name', 'Last Name', 'trim|required' );
			$this->form_validation->set_rules( 'email', 'Email', 'trim|required|valid_email|is_unique[users.email]' );

			$this->form_validation->set_rules( 'password', 'Password', 'trim|required' );
			$this->form_validation->set_rules( 'confirm_password', 'Password', 'trim|required|matches[password]|min_length[6]|alpha_numeric|callback_password_check' );

			if ( $this->form_validation->run() == false ) {
				$data['notif']['message'] = validation_errors();
				$data['notif']['type']    = 'danger';
			} else {
				$data['notif'] = $this->auth_model->register();
				redirect( base_url( 'auth/login' ) );
			}
		}

		if ( $this->session->userdata( 'logged_in' ) ) {
			redirect( base_url( 'person' ) );
			exit;
		}


		$data['main_content'] = 'auth/register';
		$this->load->view( 'includes/template', $data );
	}

	/**
	 *   Will attempt to send a new randomized password to the user
	 */
	public function forgot_password() {
		$data['title'] = 'Forgot password';
		$this->load->model( 'auth_model' );


		if ( count( $this->input->post() ) ) {

			$this->load->helper( 'security' );
			$this->form_validation->set_rules( 'email', 'Email', 'trim|required|valid_email' );

			if ( $this->form_validation->run() == false ) {

				$data['notif']['message'] = validation_errors();
				$data['notif']['type']    = 'danger';

			} else {

				$result = $this->auth_model->check_email( $this->input->post( 'email' ) );

				if ( $result ) {

					$new_password = $this->random_password();

					$mail = $this->password_recovery_email($this->input->post( 'email' ), $new_password);

					$this->auth_model->change_password($this->input->post( 'email' ), $new_password);
					$data['notif']['message'] = 'Sending email with your new password.Check your inbox shortly.';
					$data['notif']['type']    = 'success';
				} else {
					$data['notif']['message'] = 'This email does not exist in the system!';
					$data['notif']['type']    = 'danger';
				}
			}
		}

		$data['main_content'] = 'auth/forgot_password';
		$this->load->view( 'includes/template', $data );

	}

	public function password_recovery_email( $user_email, $new_password ){

		// loads up the phpmailer based CI extension
		// ( https://github.com/ivantcholakov/codeigniter-phpmailer )
		$this->load->library('email');


		$subject = 'Phonebook.dev Password Recovery';
		$message = 'Your new password for phonebook.dev site:  ' . $new_password;
		$email_body = $this->email->full_html($subject, $message);

		$result = $this->email->from('cihseramsi@gmail.com')->reply_to('i.sheeha@gmail.com')
															->to($user_email)
															->subject($subject)
															->message($email_body)->send();
		return $result;

	}


	public function password_check( $str ) {
		if ( preg_match( '#[0-9]#', $str ) && preg_match( '#[a-zA-Z]#', $str ) ) {
			return true;
		}

		return false;
	}


	public function logout() {
		$this->session->unset_userdata( 'logged_in' );
		$this->session->sess_destroy();
		$this->output->set_header( "Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0" );
		$this->output->set_header( "Pragma: no-cache" );
		redirect( base_url( 'auth/login' ) );
	}

	private function random_password()
	{
		$alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
		$password = array();
		$alpha_length = strlen($alphabet) - 1;
		for ($i = 0; $i < 8; $i++)
		{
			$n = rand(0, $alpha_length);
			$password[] = $alphabet[$n];
		}
		return implode($password);
	}


}
