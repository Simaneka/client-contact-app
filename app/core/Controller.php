<?php

class Controller
{
    public $client = null;
    public $contact = null;

    public function __construct()
    {
        require_once '../app/models/clients.php';
        $this->client = new Clients();

        require_once '../app/models/contacts.php';
        $this->contact = new Contacts();
    }
    public function model($model)
    {
        if ($model == 'Clients') {
            $clients = $this->client->getAll($model);
            return $clients;
        }

        if ($model == 'Contacts') {
            $contacts = $this->contact->getAll($model);
            return $contacts;
        }
    }

    public function view($view, $data = [])
    {
        require_once '../app/views/' . $view . '.php';
    }
}
