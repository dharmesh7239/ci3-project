<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Project extends CI_Controller {
 
   public function __construct() {
      parent::__construct(); 
      $this->load->library('form_validation');
      $this->load->library('session');
      $this->load->model('Project_model', 'project');
   }
 
   public function index() {
       $data['title'] = "CodeIgniter Project Manager"; 
       $this->load->view('project/index', $data);
   }
 
   public function show($id) {
       $data['project'] = $this->project->get($id);
       $data['title'] = "Show Project";
       $this->load->view('project/show', $data);
   }

   public function create() {
       $this->load->view('project/create'); 
   }
 
   public function store() {
       $this->form_validation->set_rules('name', 'Name', 'required');
       $this->form_validation->set_rules('description', 'Description', 'required');
    
       if (!$this->form_validation->run()) {
           echo json_encode([
               'status' => 'error',
               'errors' => validation_errors()
           ]);
       } else {
           $this->load->model('Project_model');
           $this->Project_model->store();
    
           echo json_encode([
               'status' => 'success',
               'message' => 'Project saved successfully'
           ]);
       }
   }
 
   public function edit($id) {
       $data['project'] = $this->project->get($id);
       $this->load->view('project/edit', $data);
   }
 
   public function update($id) {
       $this->form_validation->set_rules('name', 'Name', 'required');
       $this->form_validation->set_rules('description', 'Description', 'required');
    
       if (!$this->form_validation->run()) {
           $this->session->set_flashdata('errors', validation_errors());
           redirect(base_url('project/edit/' . $id));
       } else {
           $this->project->update($id);
           $this->session->set_flashdata('success', "Updated Successfully!");
           redirect(base_url('project'));
       }
   }
 
   public function delete($id) {
       $this->project->delete($id); 
       echo json_encode(['success' => true]);
   }
  
   public function json_list() {
       $this->load->model('Project_model');
       $total_rows = $this->Project_model->get_count();
       $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
       $offset = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
       $projects = $this->Project_model->get_projects($page,$offset);
       $data['projects'] = $projects;
       $data['total'] = $total_rows; 
       echo json_encode($data);
   }
}