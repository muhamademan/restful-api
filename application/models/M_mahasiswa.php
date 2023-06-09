<?php

class M_mahasiswa extends CI_Model
{
    public function getAll($id = null)
    {
        if ($id === null) {
            return $this->db->get('tb_mahasiswa')->result_array();
        } else {
            return $this->db->get_where('tb_mahasiswa', ['id' => $id])->result_array();
        }
    }

    public function deleteMahasiswa($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('tb_mahasiswa');
        return $this->db->affected_rows();
    }


    public function insertData($data)
    {
        $this->db->insert('tb_mahasiswa', $data);
        return $this->db->affected_rows();
    }

    public function updateMahasiswa($data, $id)
    {
        $this->db->update('tb_mahasiswa', $data, ['id' => $id]);
        return $this->db->affected_rows();
    }
}