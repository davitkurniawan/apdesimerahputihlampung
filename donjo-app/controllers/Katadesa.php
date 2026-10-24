<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * XOSID NEURAL AI AGENT
 * Simplified Controller to bypass underscore routing issues
 */
class Katadesa extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->modul_ini = 'katadesa';
    }

    public function index()
    {
        $data = $this->header_model->get_data();
        $this->render('katadesa_ai/index', $data);
    }
}
