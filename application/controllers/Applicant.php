<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Applicant extends CI_Controller
{
    public function __construct(){

        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('Applicant_m','person');

    }

    public function Login()
    {
        $this->load->view('Includes/Applicant/header');
        $this->load->view('Pages/Applicant/Login');
        //$this->load->view('Includes/footer_contact');
    }

    public function Signup()
    {
        $this->load->view('Includes/Applicant/header');
        $this->load->view('Pages/Applicant/Signup');
        //$this->load->view('Includes/footer_contact');
    }

    public function form_validation()
    {
        //echo 'OK';
        $this->load->library('form_validation');
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|min_length[6]|matches[password]');

        if ($this->form_validation->run()) {
            //true
            $this->load->model('Applicant_m');
            $data = array(
                'username' => $this->input->post('username'),
                'password' => base64_encode(strrev(md5($this->input->post('password')))),
                'email' => $this->input->post('email')
            );

            $this->Applicant_m->insert_data($data);

            redirect(base_url() . 'Applicant/inserted');

        } else {
            //false
            $this->Signup();
        }
    }

    public function inserted()
    {
        $this->Login();
    }

    public function createProfile()
    {
        $this->load->view('Pages/Applicant/createProfile');
    }

    function login_validation()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('email', 'E-mail', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');

        if ($this->form_validation->run()) {
            //true

            $email = $this->input->post('email');
            $password = base64_encode(strrev(md5($this->input->post('password'))));

            $this->load->model('Applicant_m');
            if ($this->Applicant_m->can_login($email, $password)) {
                $session_data = array(
                    'email' => $email
                );
                $this->session->set_userdata($session_data);
                redirect(base_url() . 'Applicant/enter');
            } else {
                $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Invalid email or password!</div>');
                redirect(base_url() . 'Applicant/Login');
            }

        } else {
            //false
            $this->Login();
        }
    }

<<<<<<< HEAD

    function enter()
    {
        if ($this->session->userdata('email') != '') {
            $email = $this->session->userdata('email');
=======
    function enter(){
        if ($this->session->userdata('email') != ''){
            $email=$this->session->userdata('email');
>>>>>>> master
            $this->load->model('Applicant_m');
            $data= $this->Applicant_m->get_data($email);
            $this->load->view('Pages/Applicant/dashboard',$data);
//            log_message('debug', var_export($data));
        } else {
            redirect(base_url() . 'Applicant/Login');
        }
    }

    function dashboard()
    {
        $this->load->view('Pages/Applicant/dashboard');
    }

    function profile()
    {

        $email = $this->session->userdata('email');
        $this->load->model('Applicant_m');
        $data = $this->Applicant_m->get_data($email);
        $this->load->view('Pages/Applicant/profile', $data);
    }

    function logout()
    {
        $this->session->unset_userdata('email');
        redirect(base_url() . 'Applicant/Login');
    }


    function employers()
    {
        $this->load->view('Pages/Applicant/employers');
    }

    function interviewRequests()
    {
        $this->load->view('Pages/Applicant/interviewRequests');
    }

    function notifications()
    {
        $this->load->view('Pages/Applicant/notifications');
    }




    public function ajax_edit()
    {
        $email = $this->session->userdata('email');
        $data = $this->person->get_by_email($email);
        echo json_encode($data);
    }

    public function ajax_add()
    {
        $data = array(
            'full_name' => $this->input->post('full_name'),
            'username' => $this->input->post('username'),
            'dob' => $this->input->post('dob'),
            'gender' => $this->input->post('gender'),
        );
        $insert = $this->person->save($data);
        echo json_encode(array("status" => TRUE));
    }

    public function ajax_update()
    {
        $data = array(
            'full_name' => $this->input->post('full_name'),
            'username' => $this->input->post('username'),
            'dob' => $this->input->post('dob'),
            'gender' => $this->input->post('gender'),
        );
        $this->person->update(array('applicant_id' => $this->input->post('applicant_id')), $data);
        echo json_encode(array("status" => TRUE));
    }

}