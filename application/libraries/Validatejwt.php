<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Validatejwt
{
    function cek_token()
    {
         $result['isValid'] = FALSE;
         $result['message'] = 'This service required token';

         $headers = getallheaders();

         if(isset($headers['x-user'])){
          $key = getenv('TOKEN_SECRET');
     
          try {
               $decoded = JWT::decode($headers['x-user'], new Key($key, 'HS256'));
               $result['isValid'] = TRUE;
               $result['message'] = 'Oke';
          } catch (Exception $ex) {
               $result['message'] = 'Invalid user token';
          }
         }

         return $result;
    }
}
?>