<?php
class Model_register extends CI_Model {
     public function getLastId($oranger){
          $id             = "";
          $this->db->select('RIGHT(username, 6) as id');
          $this->db->from('t_mitra');
          $this->db->where('LEFT(username, 3) =', $oranger);
          $this->db->order_by('username', 'DESC');
          $this->db->limit(1);
  
          $query = $this->db->get();
          if($query->num_rows() > 0){
              $data   = $query->row_array();
              $id     = $data['id'] + 1;
              $id     = str_pad($id, 6, "0", STR_PAD_LEFT);
              $id     = $oranger.$id;
          }else{
              $id     = 1;
              $id     = str_pad($id, 6, "0", STR_PAD_LEFT);
              $id     = $oranger.$id;
          }
  
          return $id;
     }

     public function addmitra($body, $berkas, $id){
          $result['success'] = false;
          $result['msg'] = 'Nothing added';

          $this->db->debug = FALSE;
          
          $datamitra = array(
               'username' => $id,
               'nama_mitra' => $body['fullname'],
               'alamat' => $body['alamat'],
               'no_ktp' => $body['nik'],
               'tempat_lahir' => $body['tempatlahir'],
               'tanggal_lahir' => $body['tanggallahir'],
               'jenis_kelamin' => $body['gender'],
               'agama' => $body['agama'],
               'no_hp' => $body['phone'],
               'email' => $body['email'],
               'jabatan' => 'K01',
               'kantor' => $body['kantor'],
               'npwp' => $body['npwp'],
               'alamat_domisili' => $body['alamatdomisili'],
               'status_kawin' => $body['status']
          );

          $this->db->insert('t_mitra', $datamitra);
          if($this->db->affected_rows() > 0){
               $this->db->insert_batch('t_berkas_mitra', $berkas);
               if($this->db->affected_rows() > 0){
                    $result['success'] = true;
                    $result['msg'] = 'ok';
               }else{
                    $this->db->where('username', $id);
                    $this->db->delete('t_mitra', $datamitra);

                    $result['msg'] = 'Berkas rejected';
               }
          }else{
               $result['msg'] = 'Failed on table mitra';
          }

          return $result;
     }
}
?>