<?php defined( 'BASEPATH' ) OR exit( 'No direct script access allowed' );

class Person extends CI_Controller {

	var $session_user;
	var $relationship;
	public function __construct() {
		parent::__construct();

		Utils::no_cache();

		if ( ! $this->session->userdata('logged_in') ) {
			redirect(base_url('auth/login'));
			exit;
		}
		$this->session_user = $this->session->userdata('logged_in');

		$user_id = $this->session_user['id'];
		$email = $this->session_user['email'];

		$this->load->model( 'person_model', 'person' );

		$this->relationship = $this->person->verify_relation($user_id, $email);


	}

	public function index() {
		$data['title'] = 'Welcome to Phonebook';
		$data['session_user'] = $this->session_user;

		$data['main_content'] = 'persons/person';
		$this->load->view('includes/template', $data);
	}

	public function ajax_list() {
		$this->load->helper( 'url' );

		$list = $this->person->get_datatables( $this->relationship->id );
		$data = array();
		$no   = $this->input->post['start'];
		foreach ( $list as $person ) {
			$no ++;
			$row   = array();
			$row[] = $person->first_name;
			$row[] = $person->last_name;
			$row[] = $person->gender;
			$row[] = $person->address;
			$row[] = $person->dob;
			if ( $person->photo ) {
				$row[] = '<a href="' . base_url( 'upload/' . $person->photo ) . '" target="_blank"><img src="' . base_url( 'upload/' . $person->photo ) . '" class="img-responsive" /></a>';
			} else {
				$row[] = '(No photo)';
			}

			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_person(' . "'" . $person->id . "'" . ')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
                  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_person(' . "'" . $person->id . "'" . ')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';

			$data[] = $row;
		}

		$output = array(
			"draw"            => $this->input->post['draw'],
			"recordsTotal"    => $this->person->count_all(),
			"recordsFiltered" => $this->person->count_filtered(),
			"data"            => $data,
		);
		//output to json format
		echo json_encode( $output );
	}

	public function ajax_edit( $id ) {
		$data      = $this->person->get_by_id( $id );
		$data->dob = ( $data->dob == '0000-00-00' ) ? '' : $data->dob; // datepicker fix
		echo json_encode( $data );
	}

	public function ajax_add() {
		$this->_validate();

		$data = array(
			'first_name' => $this->input->post( 'first_name' ),
			'last_name'  => $this->input->post( 'last_name' ),
			'gender'    => $this->input->post( 'gender' ),
			'address'   => $this->input->post( 'address' ),
			'dob'       => $this->input->post( 'dob' ),
			//piggyback the owner user_id from the server session
			'user_id'   => $this->session_user['id'],
		);

		if ( ! empty( $_FILES['photo']['name'] ) ) {
			$upload        = $this->_do_upload();
			$data['photo'] = $upload;
		}

		$insert = $this->person->save( $data );

		echo json_encode( array( "status" => true ) );
	}

	public function ajax_update() {
		$this->_validate();
		$data = array(
			'first_name' => $this->input->post( 'first_name' ),
			'last_name'  => $this->input->post( 'last_name' ),
			'gender'    => $this->input->post( 'gender' ),
			'address'   => $this->input->post( 'address' ),
			'dob'       => $this->input->post( 'dob' ),
			//
			'user_id'   => $this->session_user['id'],
		);

		if ( $this->input->post( 'remove_photo' ) ) // on remove image checked
		{
			if ( file_exists( 'upload/' . $this->input->post( 'remove_photo' ) ) && $this->input->post( 'remove_photo' ) ) {
				unlink( 'upload/' . $this->input->post( 'remove_photo' ) );
			}
			$data['photo'] = '';
		}

		if ( ! empty( $_FILES['photo']['name'] ) ) { // point of upload
			$upload = $this->_do_upload();

			//delete file
			$person = $this->person->get_by_id( $this->input->post( 'id' ) );
			if ( file_exists( 'upload/' . $person->photo ) && $person->photo ) {
				unlink( 'upload/' . $person->photo );
			}

			$data['photo'] = $upload;
		}

		$this->person->update( array( 'id' => $this->input->post( 'id' ) ), $data );
		echo json_encode( array( "status" => true ) );
	}

	public function ajax_delete( $id ) {
		//delete file
		$person = $this->person->get_by_id( $id );
		if ( file_exists( 'upload/' . $person->photo ) && $person->photo ) {
			unlink( 'upload/' . $person->photo );
		}

		$this->person->delete_by_id( $id );
		echo json_encode( array( "status" => true ) );
	}

	private function _do_upload() {
		$config['upload_path']   = 'upload/';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size']      = 100; //max size allowed in kb
		$config['max_width']     = 1000; // max width  allowed
		$config['max_height']    = 1000; // max height allowed
		$config['file_name']     = round( microtime( true ) * 1000 ); // can't beat micro-second  time-stamps for
		// uniqueness

		$this->load->library( 'upload', $config );

		if ( ! $this->upload->do_upload( 'photo' ) ) //upload and validate
		{
			$data['inputerror'][]   = 'photo';
			$data['error_string'][] = 'Upload error: ' . $this->upload->display_errors( '', '' ); //show ajax error
			$data['status']         = false;
			echo json_encode( $data );
			exit();
		}

		return $this->upload->data( 'file_name' );
	}

	private function _validate() {
		$data                 = array();
		$data['error_string'] = array();
		$data['inputerror']   = array();
		$data['status']       = true;

		if ( $this->input->post( 'first_name' ) == '' ) {
			$data['inputerror'][]   = 'first_name';
			$data['error_string'][] = 'First name is required';
			$data['status']         = false;
		}

		if ( $this->input->post( 'last_name' ) == '' ) {
			$data['inputerror'][]   = 'last_name';
			$data['error_string'][] = 'Last name is required';
			$data['status']         = false;
		}

		if ( $this->input->post( 'dob' ) == '' ) {
			$data['inputerror'][]   = 'dob';
			$data['error_string'][] = 'Date of Birth is required';
			$data['status']         = false;
		}

		if ( $this->input->post( 'gender' ) == '' ) {
			$data['inputerror'][]   = 'gender';
			$data['error_string'][] = 'Please select gender';
			$data['status']         = false;
		}

		if ( $this->input->post( 'address' ) == '' ) {
			$data['inputerror'][]   = 'address';
			$data['error_string'][] = 'Addess is required';
			$data['status']         = false;
		}

		if ( $data['status'] === false ) {
			echo json_encode( $data );
			exit();
		}
	}

}