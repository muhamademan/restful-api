<?php
class M_jurusan extends CI_Model
{
    public function getAll($idJurusan = null)
    {
        if ($idJurusan === null) {
            return $this->db->get('tb_jurusan')->result_array();
        } else {
            return $this->db->get_where('tb_jurusan', ['id_jurusan' => $idJurusan])->result_array();
        }
    }

    public function insertJurusan($data)
    {
        $this->db->insert('tb_jurusan', $data);
        return $this->db->affected_rows();
    }

    public function deleteJurusan($id)
    {
        $this->db->where('id_jurusan', $id);
        $this->db->delete('tb_jurusan');
        return $this->db->affected_rows();
    }

    public function updateJurusan($data, $id)
    {
        $this->db->update('tb_jurusan', $data, ['id_jurusan' => $id]);
        return $this->db->affected_rows();
    }
}