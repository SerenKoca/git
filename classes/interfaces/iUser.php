<?php
  namespace Web\XD\Interfaces;

  interface iUser{
      public function login();
      public function canLogin($p_email, $p_password);
  }