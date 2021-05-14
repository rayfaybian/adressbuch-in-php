<?php

class validator
{
    private $error_vorname;
    private $error_nachname;
    private $error_email;

    private $error_class_vorname;
    private $error_class_nachname;
    private $error_class_email;

    private $vornameIsValid = false;
    private $nachnameIsValid = false;
    private $emailIsValid = false;


    public function setErrorVorname($errorMessage)
    {
        $this->error_vorname = $errorMessage;
    }

    public function setErrorNachname($errorMessage)
    {
        $this->error_nachname = $errorMessage;
    }

    public function setErrorEmail($errorMessage)
    {
        $this->error_email = $errorMessage;
    }

    public function getErrorVorname()
    {
        return $this->error_vorname;
    }

    public function getErrorNachname()
    {
        return $this->error_nachname;
    }

    public function getErrorEmail()
    {
        return $this->error_email;
    }

    public function setErrorClassVorname()
    {
        $this->error_class_vorname = "input-error";
    }

    public function setErrorClassNachname()
    {
        $this->error_class_nachname = "input-error";
    }

    public function setErrorClassEmail()
    {
        $this->error_class_email = "input-error";
    }

    public function getErrorClassVorname()
    {
        return $this->error_class_vorname;
    }

    public function getErrorClassNachname()
    {
        return $this->error_class_nachname;
    }

    public function getErrorClassEmail()
    {
        return $this->error_class_email;
    }

    public function validateVorname()
    {
        $this->vornameIsValid = true;
    }

    public function validateNachname()
    {
        $this->nachnameIsValid = true;
    }

    public function validateEmail()
    {
        $this->emailIsValid = true;
    }

    public function validateInput(){
        if(($this->vornameIsValid) && ($this->nachnameIsValid) && ($this->emailIsValid)){
            return true;
        }
    }

}