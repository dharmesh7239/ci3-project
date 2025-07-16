<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model', 'user');
        $this->load->library(['session', 'form_validation']);
        $this->load->helper(['url', 'form']);

        $free_methods = ['login_view', 'login'];
        if (!in_array($this->router->method, $free_methods)) {
            if ($this->session->userdata('role') !== 'admin') {
                show_error('Access denied. Admin only.', 403);
            }
        }
    }

    public function login_view() {
        $this->load->view('admin/login_view');
    }

    public function login() {
        $input = json_decode(file_get_contents("php://input"), true);

        if (!is_array($input)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
            return;
        }

        $user = $this->user->login($input['email'], $input['password']);

        if ($user && $user->role === 'admin') {
            $this->session->set_userdata([
                'user_id' => $user->id,
                'role' => $user->role,
                'logged_in' => true
            ]);
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid credentials']);
        }
    }

    public function index() {
        $data['users'] = $this->user->get_all_users(['user']);
        $this->load->view('admin/dashboard_view', $data);
    }

    public function get_users() {
        $users = $this->user->get_all_users(['user']); // Only normal users
        echo json_encode($users);
    }

    public function create_user() {
        $input = json_decode(file_get_contents("php://input"), true);

        $this->form_validation->set_data($input);
        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[5]');

        if (!$this->form_validation->run()) {
            echo json_encode(['status' => 'error', 'message' => validation_errors()]);
            return;
        }

        $input['role'] = 'user';
        $saved = $this->user->register($input);
        echo json_encode(['status' => $saved ? 'success' : 'error']);
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect('admin');
    }


    public function toggle_status($id) {

    $this->load->model('User_model', 'user');
    $user = $this->user->get_by_id($id);

    if (!$user || $user->role !== 'user') {
        echo json_encode(['status' => 'error', 'message' => 'Invalid user']);
        return;
    }

    $new_status = $user->status === 'active' ? 'inactive' : 'active';
    $this->user->update_user($id, ['status' => $new_status]);

    echo json_encode(['status' => 'success', 'new_status' => $new_status]);
}

}
?>