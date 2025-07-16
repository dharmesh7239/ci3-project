<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

   public function __construct() {
    parent::__construct();
    $this->load->model('User_model', 'user');
    $this->load->library(['form_validation', 'session']);
  
    $this->load->helper(['url', 'form']);
}


    public function login_view() {
        $this->load->view('auth/login_view');
    }

    public function register_view() {
        $this->load->view('auth/register_view');
    }

    
   public function login() {
    $input = json_decode(file_get_contents("php://input"), true);

    if (!is_array($input)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
        return;
    }

    $this->form_validation->set_data($input);
    $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
    $this->form_validation->set_rules('password', 'Password', 'required');

    if (!$this->form_validation->run()) {
        echo json_encode([
            'status' => 'error',
            'errors' => validation_errors()
        ]);
        return;
    }

    $user = $this->user->login($input['email'], $input['password']);
    if ($user) {
        $this->session->set_userdata([
            'user_id' => $user->id,
            'role'    => $user->role,
            'logged_in' => true
        ]);
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid credentials']);
    }
}


  public function register() {
    $input = json_decode(file_get_contents("php://input"), true);

    if (!is_array($input)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
        return;
    }

    $this->form_validation->set_data($input);
    $this->form_validation->set_rules('name', 'Name', 'required');
    $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
    $this->form_validation->set_rules('password', 'Password', 'required|min_length[5]');
    $this->form_validation->set_rules('role', 'Role', 'required');

    if (!$this->form_validation->run()) {
        echo json_encode([
            'status' => 'error',
            'errors' => validation_errors()
        ]);
        return;
    }

    $saved = $this->user->register($input);
    echo json_encode([
        'status' => $saved ? 'success' : 'error',
        'message' => $saved ? 'User registered successfully.' : 'Failed to save user.'
    ]);
}


   

public function session_info() {
    $user = $this->user->get_by_id($this->session->userdata('user_id'));
    
    echo json_encode([
        'user_id' => $user->id,
        'name'    => $user->name,
        'email'   => $user->email,
        'role'    => $user->role
    ]);
}

public function logout() {
    $this->session->sess_destroy();
    echo json_encode(['status' => 'success', 'message' => 'Logged out']);
}
}


?>