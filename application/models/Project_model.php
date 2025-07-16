<?php
 
class Project_model extends CI_Model {
 
    public function __construct() {
        $this->load->database();
        $this->load->helper('url');
    }
 
    public function get_all() {
        $projects = $this->db->get("projects")->result();
        return $projects;
    }
 
    public function store() {
        $data = [
            'name'        => $this->input->post('name'),
            'description' => $this->input->post('description'),
            'user_id'     => $this->session->userdata('user_id')
        ];
        return $this->db->insert('projects', $data);
    }
 
    public function get($id) {
        $this->db->where('id', $id);
        if ($this->session->userdata('role') === 'user') {
            $this->db->where('user_id', $this->session->userdata('user_id'));
        }
        return $this->db->get('projects')->row();
    }
 
    public function update($id) {
        $data = [
            'name'        => $this->input->post('name'),
            'description' => $this->input->post('description')
        ];
 
        $result = $this->db->where('id',$id)->update('projects',$data);
        return $result;
    }
 
    public function delete($id) {
        $this->db->where('id', $id);
        if ($this->session->userdata('role') === 'user') {
            $this->db->where('user_id', $this->session->userdata('user_id'));
        }
        return $this->db->delete('projects');
    }

    public function get_projects($limit, $start) {
        $role = $this->session->userdata('role');
        if ($role === 'user') {
            $this->db->where('user_id', $this->session->userdata('user_id'));
        }
        $this->db->limit($limit, $start);
        return $this->db->get('projects')->result();
    }

    public function get_count() {
        $role = $this->session->userdata('role');
        if ($role === 'user') {
            $this->db->where('user_id', $this->session->userdata('user_id'));
        }
        return $this->db->count_all_results('projects');
    }
}
?>