<?php

class Validator
{
    private $error_vorname;
    private $error_nachname;
    private $error_email;

    private $error_class_vorname;
    private $error_class_nachname;
    private $error_class_email;

    private $vornameIsValid;
    private $nachnameIsValid;
    private $emailIsValid;

    public function __construct()
    {
        $this->vornameIsValid = false;
        $this->nachnameIsValid = false;
        $this->emailIsValid = false;
    }

    public function validateInput($conn, $data, $id)
    {
        $this->vornameIsValid = $this->validateVorname($data);
        $this->nachnameIsValid = $this->validateNachname($data);
        $this->emailIsValid = $this->validateMail($conn, $data, $id);

        if (($this->vornameIsValid) && ($this->nachnameIsValid) && ($this->emailIsValid)) {
            return true;
        }
    }

    private function validateVorname($data)
    {
        if (!empty($data['vorname'])) {
            return true;
        } else {
            $this->error_vorname = "Bitte Vorname angeben!";
            $this->error_class_vorname = "input_error";
        }
    }

    private function validateNachname($data)
    {
        if (!empty($data['nachname'])) {
            return true;
        } else {
            $this->error_nachname = "Bitte Nachname angeben!";
            $this->error_class_nachname = "input_error";
        }
    }

    private function validateMail($conn, $data, $id)
    {
        if (empty($data['email'])) {
            $this->error_email = "Bitte Email Adresse angeben!";
            $this->error_class_email = "input_error";
        } elseif (!$this->checkMailPattern($data['email'])) {
            $this->error_email = "Ungültige Email Adresse!";
            $this->error_class_email = "input_error";
        } elseif (!$this->checkUniqueMail($conn, $data, $id)) {
            $this->error_email = "Email Adresse wird bereits verwendet!";
            $this->error_class_email = "input_error";
        } else {
            return true;
        }
    }

    private function checkMailPattern($email)
    {
        $pattern =
            "/^[a-zA-Z0-9!#$&_*?^{}~-]+(\.?[a-zA-Z0-9!#$&_*?^{}~-]+)*@+([a-z0-9]+([a-z0-9]*)\.)+[a-zA-Z]+$/i";
        return preg_match($pattern, $email);
    }

    private function checkUniqueMail($conn, $data, $id)
    {
        $email = $data['email'];
        $qry = mysqli_query(
            $conn,
            "select * from adressbuch where email='$email' AND NOT id=$id"
        );
        $result = mysqli_fetch_array($qry);

        if ($result == 0) {
            return true;
        }
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
}
