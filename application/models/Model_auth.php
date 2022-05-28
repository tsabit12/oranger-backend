<?php
class Model_auth extends CI_Model
{
    public function login($body){
        $result['success'] = false;

        $this->db->select('a.username, a.nama_mitra, a.id_mitra, a.jabatan as kd_jabatan, c.keterangan as ket_jabatan, a.kantor, b.NamaKtr as nm_kantor, a.nama_mitra, a.email, a.no_hp, a.jenis_kelamin as gender');
        $this->db->from('t_mitra a');
        $this->db->join('r_kantor b', 'a.kantor = b.nopend');
        $this->db->join('r_jabatan c', 'a.jabatan = c.id_jabatan');
        $this->db->where(array(
            'a.username' => $body['username'],
            'a.password' => md5($body['username'].$body['password']),
            'a.status' => 'S4'
        ));
        $this->db->limit(1);

        $q = $this->db->get();
        
        if($q->num_rows() > 0){
            $result['success'] = true;
            $result['data'] = $q->row_array();
        }

        return $result;
    }
}
?>