<?php
  namespace Kocas\Git\interfaces;

  interface iUser{
    public function getId();
    public function setId($id);
    public function getEmail();
    public function setEmail($email);
    public function getPassword();
    public function setPassword($password);
    public function emailExists();
    public function canLogin($p_email, $p_password);
    public function changePassword($currentPassword, $newPassword);
    public function save();
    public function getBalance();
    public function setBalance($balance);
    public function initializeBalance();
    public function deductBalance($amount);
  }