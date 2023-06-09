<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Mahasiswa extends RestController
{
    function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('M_mahasiswa', 'mhs');

        // limit tiap method hanya boleh mengakses 5 kali hit endpoint per jam
        $this->methods['index_get']['limit'] = 5;
        $this->methods['index_post']['limit'] = 5;
        $this->methods['index_delete']['limit'] = 5;
        $this->methods['index_put']['limit'] = 5;
    }

    public function index_get()
    {
        $id = $this->get('id');

        if ($id === null) {
            $dataMhs = $this->mhs->getAll();
        } else {
            $dataMhs = $this->mhs->getAll($id);
        }

        if ($dataMhs) {
            $this->response([
                'status'    => TRUE,
                'message'   => $dataMhs
            ], RestController::HTTP_OK);
        } else {
            $this->response([
                'status'    => FALSE,
                'message'   => 'Data not found'
            ], RestController::HTTP_NOT_FOUND);
        }
    }

    public function index_delete()
    {
        $id = $this->delete('id');

        $data = $this->mhs->deleteMahasiswa($id);

        if ($id === null) {
            $this->response([
                'status'    => FALSE,
                'message'   => 'Provide an id!'
            ], RestController::HTTP_BAD_REQUEST);
        } else {
            if ($data > 0) {
                $this->response([
                    'status'    => TRUE,
                    'id'        => $id,
                    'message'   => 'Data has been deleted'
                ], RestController::HTTP_OK);
            } else {
                $this->response([
                    'status'    => FALSE,
                    'message'   => 'Data not found!'
                ], RestController::HTTP_NOT_FOUND);
            }
        }
    }

    public function index_post()
    {
        $this->form_validation->set_rules('nrp', 'NRP', 'is_unique[tb_mahasiswa.nrp]');

        $this->form_validation->set_rules('nama', 'Nama', 'is_unique[tb_mahasiswa.nama]');

        if ($this->form_validation->run() == true) {
            $data = [
                'nrp'        => $this->post('nrp'),
                'nama'       => $this->post('nama'),
                'email'      => $this->post('email'),
                'jurusan'    => $this->post('jurusan'),
            ];
            $insertData = $this->mhs->insertData($data);

            if ($insertData > 0) {
                $this->response([
                    'status'    => TRUE,
                    'message'   => 'New mahasiswa has been created'
                ], RestController::HTTP_CREATED);
            } else {
                $this->response([
                    'status'    => FALSE,
                    'message'   => 'Failed to create new data'
                ], RestController::HTTP_BAD_REQUEST);
            }
        } else {
            $this->response([
                'status'    => FALSE,
                'message'   => 'nrp or name not same'
            ], RestController::HTTP_BAD_REQUEST);
        }
    }

    public function index_put()
    {

        $id = $this->put('id');
        $data = [
            'nrp'       => $this->put('nrp'),
            'nama'      => $this->put('nama'),
            'email'     => $this->put('email'),
            'jurusan'   => $this->put('jurusan'),
        ];

        $data = $this->mhs->updateMahasiswa($data, $id);

        if ($data > 0) {
            $this->response([
                'status'    => TRUE,
                'message'   => 'Data has been updated'
            ], RestController::HTTP_OK);
        } else {
            $this->response([
                'status'    => FALSE,
                'message'   => 'Failed to update data'
            ], RestController::HTTP_BAD_REQUEST);
        }
    }
}