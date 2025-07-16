<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Superadmin extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model', 'user');
        $this->load->library(['session', 'form_validation']);
        $this->load->helper(['url', 'form']);

       
        $free_methods = ['login_view', 'login'];

        if (!in_array($this->router->method, $free_methods)) {
            if ($this->session->userdata('role') !== 'superadmin') {
                show_error('Access denied. Superadmin only.', 403);
            }
        }
    }

    public function login_view() {
        $this->load->view('superadmin/login_view');
    }

    public function login() {
        $input = json_decode(file_get_contents("php://input"), true);

        if (!is_array($input)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid input.']);
            return;
        }

        $email = $input['email'];
        $password = $input['password'];

        $user = $this->user->login($email, $password);

        if ($user && $user->role === 'superadmin') {
            $this->session->set_userdata([
                'user_id'   => $user->id,
                'role'      => $user->role,
                'logged_in' => true
            ]);
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid credentials or not a superadmin.']);
        }
    }

    
    public function index() {
        $this->load->view('superadmin/users_view');
    }

   
    public function get_users() {
        $users = $this->user->get_all_users();
        echo json_encode($users);
    }

    
    public function toggle_status($id) {
        $user = $this->user->get_by_id($id);
        if (!$user) {
            echo json_encode(['status' => 'error', 'message' => 'User not found']);
            return;
        }

        $new_status = $user->status === 'active' ? 'inactive' : 'active';
        $this->user->update_user($id, ['status' => $new_status]);
        echo json_encode(['status' => 'success', 'new_status' => $new_status]);
    }

    
    public function change_role($id, $role) {
        if (!in_array($role, ['user', 'admin'])) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid role']);
            return;
        }

        $this->user->update_user($id, ['role' => $role]);
        echo json_encode(['status' => 'success']);
    }

    
    public function create_user() {
        $input = json_decode(file_get_contents("php://input"), true);

        if (!is_array($input)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid input.']);
            return;
        }

        $this->form_validation->set_data($input);
        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[5]');
        $this->form_validation->set_rules('role', 'Role', 'required|in_list[user,admin]');

        if (!$this->form_validation->run()) {
            echo json_encode(['status' => 'error', 'message' => validation_errors()]);
            return;
        }

        $saved = $this->user->register($input);
        echo json_encode([
            'status' => $saved ? 'success' : 'error',
            'message' => $saved ? 'User created successfully.' : 'Failed to create user.'
        ]);
    }

    
    public function logout() {
        $this->session->sess_destroy();
        echo json_encode(['status' => 'success', 'message' => 'Logged out.']);
    }

    public function export_users_pdf($type = 'all') {
    // Clean output buffering
    if (ob_get_contents()) ob_end_clean();
    ob_start();

    try {
        // Load PDF library
        $this->load->library('pdf');

        $title = $type === 'admin' ? 'Admin List Report' : 'User List Report';

        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator('CI3 System');
        $pdf->SetAuthor('Superadmin');
        $pdf->SetTitle($title);
        $pdf->SetMargins(15, 25, 15);
        $pdf->SetHeaderMargin(5);
        $pdf->SetFooterMargin(10);
        $pdf->SetAutoPageBreak(TRUE, 25);
        $pdf->AddPage();

        // Add watermark
        // $pdf->SetAlpha(0.5);
        // $pdf->SetFont('times', 'B', 40);
        // $pdf->SetTextColor(255, 245, 0); // Yellow
        // $pdf->StartTransform();
        // $pdf->Rotate(45, $pdf->GetPageWidth() / 2, $pdf->GetPageHeight() / 2);
        // $pdf->Text($pdf->GetPageWidth() / 2 - 50, $pdf->GetPageHeight() / 2, 'Saglus');
        // $pdf->StopTransform();
        // $pdf->SetAlpha(1);
        // $pdf->SetTextColor(0, 0, 0); // Reset text color
        $pdf->SetFont('times', '', 12);

        
        $users = $this->user->get_all_users();
        if ($type !== 'all') {
            $users = array_filter($users, function($user) use ($type) {
                return $user->role === $type;
            });
        }

        // 📝 HTML Table
        $html = '
        <style>
            table {
                border-collapse: collapse;
                width: 100%;
                margin-bottom: 10px;
            }
            th {
                background-color: #f5f5f5;
                font-weight: bold;
                text-align: left;
                padding: 8px;
            }
            td {
                padding: 8px;
                border: 1px solid #619181ff;
            }
            tr:nth-child(even) {
                background-color: #f1f1f1;
            }
        </style>
        
        <table border="1" cellpadding="5">
            <tr>
                <th width="60"><b>S.NO.</b></th>
                <th width="120"><b>Name</b></th>
                <th width="180"><b>Email</b></th>
                <th width="150"><b>Status</b></th>
            </tr>';

        $count = 1;
        foreach ($users as $user) {
            $html .= sprintf('
            <tr>
                <td>%d</td>
                <td>%s</td>
                <td>%s</td>
                <td>%s</td>
            </tr>',
                $count++,
                htmlspecialchars($user->name),
                htmlspecialchars($user->email),
                htmlspecialchars($user->status)
            );
        }

        $html .= '</table>';

        $pdf->writeHTML($html, true, false, true, false, '');

        ob_end_clean(); // Ensure no buffer issues

        $pdf->Output($type.'_list_'.date('Y-m-d').'.pdf', 'D');
        exit();
    } catch (Exception $e) {
        ob_end_clean();
        log_message('error', 'PDF Generation Error: ' . $e->getMessage());
        show_error('Error generating PDF: ' . $e->getMessage());
    }
}

}
?>