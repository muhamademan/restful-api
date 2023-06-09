<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Jurusan extends RestController
{
    function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('M_jurusan', 'jurusan');

        // limit tiap method hanya boleh mengakses 5 kali hit endpoint per jam
        $this->methods['index_get']['limit'] = 5;
        $this->methods['index_post']['limit'] = 5;
        $this->methods['index_delete']['limit'] = 5;
        $this->methods['index_put']['limit'] = 5;
    }

    public function index_get()
    {
        $idJurusan = $this->get('id_jurusan');

        if ($idJurusan === null) {
            $dataJurusan = $this->jurusan->getAll();
        } else {
            $dataJurusan = $this->jurusan->getAll($idJurusan);
        }

        if ($dataJurusan) {
            $this->response([
                'status'    =>  TRUE,
                'data'      => $dataJurusan
            ], RestController::HTTP_OK);
        } else {
            $this->response([
                'status'    => FALSE,
                'message'   => 'Data not found'
            ], RestController::HTTP_BAD_REQUEST);
        }
    }

    public function index_post()
    {
        $this->form_validation->set_rules('nama_jurusan', 'Nama Jurusan', 'is_unique[tb_jurusan.nama_jurusan]');

        if ($this->form_validation->run() == true) {
            $data = [
                'nama_jurusan'  => $this->post('nama_jurusan')
            ];

            $dataInsert = $this->jurusan->insertJurusan($data);

            if ($dataInsert > 0) {
                $this->response([
                    'status'    => TRUE,
                    'message'   => 'data has been created'
                ], RestController::HTTP_CREATED);
            } else {
                $this->response([
                    'status'    => FALSE,
                    'message'   => 'data not created'
                ], RestController::HTTP_BAD_REQUEST);
            }
        } else {
            $this->response([
                'status'    => FALSE,
                'message'   => 'Data not same'
            ], RestController::HTTP_BAD_REQUEST);
        }
    }

    public function index_delete()
    {
        $id = $this->delete('id_jurusan');

        $data = $this->jurusan->deleteJurusan($id);

        if ($id === null) {
            $this->response([
                'status'    => FALSE,
                'message'   => 'provide an id'
            ], RestController::HTTP_NOT_FOUND);
        } else {
            if ($data > 0) {
                $this->response([
                    'status'        => TRUE,
                    'id_jurusan'    => $id,
                    'message'       => 'Data has been deleted'
                ], RestController::HTTP_OK);
            } else {
                $this->response([
                    'status'    => FALSE,
                    'message'   => 'Data not found'
                ], RestController::HTTP_NOT_FOUND);
            }
        }
    }

    public function index_put()
    {
        $id = $this->put('id_jurusan');
        $data = [
            'nama_jurusan'  => $this->put('nama_jurusan')
        ];

        $data = $this->jurusan->updateJurusan($data, $id);

        if ($id === null) {
            $this->response([
                'status'    => FALSE,
                'message'   => 'Id not found'
            ], RestController::HTTP_NOT_FOUND);
        } else {
            if ($data > 0) {
                $this->response([
                    'status'    => TRUE,
                    'message'   => 'Data has been updated'
                ], RestController::HTTP_OK);
            } else {
                $this->response([
                    'status'    => FALSE,
                    'message'   => 'Data not updated'
                ], RestController::HTTP_BAD_REQUEST);
            }
        }
    }
}