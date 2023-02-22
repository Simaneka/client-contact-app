<?php

class Client extends Controller
{
    public function index($name = '')
    {

        $contacts = $this->model('Contacts');
        $clients = $this->model('Clients');

        $contactssNum = 0;
        foreach ($contacts as $contact) {
            if ($contact['clients_linked'] != 0) {
                $contactssNum++;
            }
        }

        $this->view('home/clients', [
            $clients, $contacts, $contactssNum
        ]);
    }
    public function add()
    {
        if ($this->client->addClient($_POST['clientName'])) {
            echo json_encode(array(
                'status' => true,
                'message' => 'Client Has Been Created!',
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
        if ($this->client->linkContactsWithClient($_POST['client'], $_POST['contacts'])) {
            echo json_encode(array(
                'status' => true,
                'message' => 'Contact(s) Has Been Linked!',
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
    public function unlink($id)
    {
        if ($this->client->unlink($id)) {
            echo json_encode(array(
                'status' => true
            ));
        }

        $root = (isset($_SERVER['HTTPS']) ? "https://" : "http://") . $_SERVER['HTTP_HOST'];
        $root .= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
        header('Location: ' . $root . 'contact');
    }
}
