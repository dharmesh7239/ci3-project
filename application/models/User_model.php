<?php
class User_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function login($email, $password) {
        $user = $this->db->get_where('users', ['email' => $email])->row();

        if ($user && password_verify($password, $user->password)) {
            return $user;
        }
        return false;
    }

    public function register($data) {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        return $this->db->insert('users', $data);
    }

    public function get_by_id($id) {
        return $this->db->get_where('users', ['id' => $id])->row();
    }

    public function get_all_users() {
        return $this->db->get('users')->result();
    }

    // public function delete_user($id) {
    //     return $this->db->delete('users', ['id' => $id]);
    // }

    public function update_user($id, $data) {
        if (!empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['password']);
        }
        return $this->db->where('id', $id)->update('users', $data);
    }
}
?>