<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Katadesa_ai extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->modul_ini = 'katadesa_ai';
    }

    public function index()
    {
        $data = $this->header_model->get_data();
        $this->render('katadesa_ai/index', $data);
    }
}
