<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Applicant extends CI_Controller {

    public function Login(){
        $this->load->view('Includes/Applicant/header');
        $this->load->view('Pages/Applicant/Login');
        //$this->load->view('Includes/footer_contact');
    }

    public function Signup(){
        $this->load->view('Includes/Applicant/header');
        $this->load->view('Pages/Applicant/Signup');
        //$this->load->view('Includes/footer_contact');
    }

    public function form_validation(){
        //echo 'OK';
        $this->load->library('form_validation');
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|min_length[6]|matches[password]');

        if($this->form_validation->run()){
            //true
            $this->load->model('Applicant_m');
            $data = array(
                'username' => $this->input->post('username'),
                'password' => base64_encode(strrev(md5($this->input->post('password')))),
                'email' => $this->input->post('email')
            );

            $this->Applicant_m->insert_data($data);

            redirect(base_url().'Applicant/inserted');

        }else{
            //false
            $this->Signup();
        }
    }

    public function inserted(){
        $this->Login();
    }

    public function createProfile(){
        $this->load->view('Pages/Applicant/createProfile');
    }

    function login_validation(){
        $this->load->library('form_validation');
        $this->form_validation->set_rules('email', 'E-mail', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');

        if($this->form_validation->run()){
            //true

            $email = $this->input->post('email');
            $password = base64_encode(strrev(md5($this->input->post('password'))));

            $this->load->model('Applicant_m');
            if ($this->Applicant_m->can_login($email, $password)){
                $session_data = array(
                    'email' => $email
                );
                $this->session->set_userdata($session_data);
                redirect(base_url().'Applicant/enter');
            }else {
                $this->session->set_flashdata('error', '<div class="alert alert-danger" role="alert">Invalid email or password!</div>');
                redirect(base_url().'Applicant/Login');
            }

        }else{
            //false
            $this->Login();
        }
    }

    function enter(){
        if ($this->session->userdata('email') != ''){
            $header_data['title'] = 'Dashboard';
            $this->load->view('Pages/Applicant/loggedInHeader',$header_data);
            $this->load->view('Pages/Applicant/sideBar');
            $this->load->view('Pages/Applicant/dashboard');
            $this->load->view('Pages/Applicant/footer');
        } else {
            redirect(base_url().'Applicant/Login');
        }
    }

    function dashboard(){
        $header_data['title'] = 'Dashboard';
        $this->load->view('Pages/Applicant/loggedInHeader',$header_data);
        $this->load->view('Pages/Applicant/sideBar');
        $this->load->view('Pages/Applicant/dashboard');
        $this->load->view('Pages/Applicant/footer');
    }

    function profile(){

        $email = $this->session->userdata('email');
        $this->load->model('Applicant_m');
        $data = $this->Applicant_m->get_data($email);

        $header_data['title'] = $data['full_name'];

        $this->load->view('Pages/Applicant/loggedInHeader',$header_data);
        $this->load->view('Pages/Applicant/sideBar');
        $this->load->view('Pages/Applicant/profile',$data);
        $this->load->view('Pages/Applicant/footer');
    }

    function logout(){
        $this->session->unset_userdata('email');
        redirect(base_url().'Applicant/Login');
    }


    function employers(){
        $header_data['title'] = 'Companies';
        $this->load->view('Pages/Applicant/loggedInHeader',$header_data);
        $this->load->view('Pages/Applicant/sideBar');
        $this->load->view('Pages/Applicant/employers');
        $this->load->view('Pages/Applicant/footer');
    }

    function interviewRequests(){
        $header_data['title'] = 'Requests';
        $this->load->view('Pages/Applicant/loggedInHeader',$header_data);
        $this->load->view('Pages/Applicant/sideBar');
        $this->load->view('Pages/Applicant/interviewRequests');
        $this->load->view('Pages/Applicant/footer');
    }

    function notifications(){
        $header_data['title'] = 'Notifications';
        $this->load->view('Pages/Applicant/loggedInHeader',$header_data);
        $this->load->view('Pages/Applicant/sideBar');
        $this->load->view('Pages/Applicant/notifications');
        $this->load->view('Pages/Applicant/footer');
    }
}