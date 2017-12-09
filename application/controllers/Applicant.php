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
        $this->form_validation->set_rules('email', 'Email', 'required|trim|is_unique[intern_applicant.email]|is_unique[job_applicant.email]',
            array(
            'required'      => 'You have not provided %s.',
            'is_unique'     => 'This %s already exists.'
        ));
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
            $applicanttype = $this->input->post('applicanttype');
            if ($applicanttype =='1'){
                $this->load->model('Applicant_m');
                $this->Applicant_m->insert_intern($data);
            }elseif ($applicanttype=='2'){
                $this->load->model('Applicant_m');
                $this->Applicant_m->insert_job($data);
            }


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

    function enter(){
        if ($this->session->userdata('email') != ''){
            $email=$this->session->userdata('email');
            $this->load->model('Applicant_m');
            $data= $this->Applicant_m->get_data($email);
            $this->load->view('Pages/Applicant/dashboard',$data);
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

    public function ajax_list_company()
    {
        $this->load->model('Applicant_m','person');
        $list = $this->person->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $person) {
            $no++;
            $row = array();
            $row[] = $person->company_name;
            $row[] = $person->country;
            $row[] = $person->email;
            $row[] = $person->address;
            $row[] = $person->contact_no;

            //add html for action
            $row[] = '<a class="btn btn-sm btn-default" href="javascript:void(0)" title="View" onclick="view_person('."'".$person->company_id."'".')"><i class="glyphicon glyphicon-file"></i> View</a>';

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->person->count_all(),
            "recordsFiltered" => $this->person->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }


    public function list_by_id_company($company_id){
        $data['output'] = $this->person->get_by_id_view($company_id);
        $this->load->view('Pages/Applicant/view_Detail', $data);
    }


    public function ajax_edit()
    {
        $email = $this->session->userdata('email');
        $data = $this->person->get_by_email($email);
        echo json_encode($data);
    }

    public function ajax_update_personal_info()
    {
        $data = array(
            'full_name' => $this->input->post('full_name'),
            'username' => $this->input->post('username'),
            'dob' => $this->input->post('dob'),
            'gender' => $this->input->post('gender'),
        );

        $email = $this->session->userdata('email');

        $this->person->update(array('email' =>$email), $data);
        echo json_encode(array("status" => TRUE));
    }

    public function ajax_update_contact_info()
    {
        $data = array(
            'address' => $this->input->post('address'),
            'contact' => $this->input->post('contact'),
            'linkedin' => $this->input->post('linkedin'),
            'website' => $this->input->post('website'),
        );

        $email = $this->session->userdata('email');

        $this->person->update(array('email' =>$email), $data);
        echo json_encode(array("status" => TRUE));
    }

    public function ajax_update_project_data()
    {
        $data = array(
            'address' => $this->input->post('address'),
            'contact' => $this->input->post('contact'),
            'linkedin' => $this->input->post('linkedin'),
            'website' => $this->input->post('website'),
        );

        $email = $this->session->userdata('email');

        $this->person->update(array('email' =>$email), $data);
        echo json_encode(array("status" => TRUE));
    }

}