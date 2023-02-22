<?php

class Contact extends Controller
{
    public function index($name = '')
    {
        $contacts = $this->model('Contacts');
        $clients = $this->model('Clients');

        $clientsNum = 0;
        foreach ($clients as $client) {
            if ($client['contacts'] != '') {
                $clientsNum++;
            }
        }
        $this->view('home/contacts', [
            $contacts, $clients, $clientsNum
        ]);
    }
    public function add()
    {
        if ($this->contact->addClient($_POST['clientName'], $_POST['clientSurname'], $_POST['clientEmail'])) {
            echo json_encode(array(
                'status' => true,
                'message' => 'Contact Has Been Created!',
                'color' => 'success'
            ));
        } else {
            echo json_encode(array(
                'status' => false,
                'message' => 'please Try Again!',
                'color' => 'warning'
            ));
        }
    }
    public function link()
    {
        if ($this->contact->linkContactClient($_POST['client'], $_POST['contacts'])) {
            echo json_encode(array(
                'status' => true,
                'message' => 'Contact(s) Have Been Linked!',
                'color' => 'success'
            ));
        } else {
            echo json_encode(array(
                'status' => false,
                'message' => 'Please Try Again!',
                'color' => 'warning'
            ));
        }
    }
    public function validateEmail()
    {
        if ($this->contact->validateEmail($_POST['clientEmail'])) {
            echo json_encode(array(
                'status' => true
            ));
        } else {
            echo json_encode(array(
                'status' => false
            ));
        }
    }
    public function unlink($id)
    {
        if ($this->contact->unlink($id)) {
            echo json_encode(array(
                'status' => true
            ));
        }

        $root = (isset($_SERVER['HTTPS']) ? "https://" : "http://") . $_SERVER['HTTP_HOST'];
        $root .= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
        header('Location: ' . $root);
    }
}
