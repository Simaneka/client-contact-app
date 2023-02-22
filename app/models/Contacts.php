<?php

require_once '../app/core/Database.php';
class Contacts extends Database
{
    public function getAll()
    {
        $contacts = [];

        $sql = "SELECT * FROM contacts ORDER BY contact_surname ASC";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $contact = array(
                    'contact_id' => $row['contact_id'],
                    'contact_name' =>  $row['contact_name'],
                    'contact_surname' => $row['contact_surname'],
                    'contact_email' => $row['contact_email'],
                    'clients_linked' => $row['clients_linked'],
                    'created_on' => $row['created_on']
                );

                array_push($contacts, $contact);
            }
        }

        return $contacts;
    }
    public function addClient($name, $surname, $email)
    {
        $sql = "INSERT INTO contacts (contact_name, contact_surname, contact_email) VALUES ('" . $name . "', '" . $surname . "','" . $email . "');";
        if ($this->conn->query($sql)) {
            return true;
        }
        return false;
    }

    public function validateEmail($email)
    {
        $sql = "SELECT * FROM contacts WHERE contact_email = '" . $email . "'";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            return true;
        }

        return false;
    }
    public function unlink($id)
    {
        $sql = "UPDATE contacts SET clients_linked = 0 WHERE contact_id = " . $id . "";
        $this->conn->query($sql);


        $clients = [];

        $sql = "SELECT * FROM clients  WHERE `contacts` LIKE '%" . $id . "%'";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $client = array(
                    'contacts' => $row['contacts'],
                    'client_id' => $row['client_id'],
                );

                array_push($clients, $client);
            }

            foreach ($clients as $client) {
                $haystack = '';
                $haystack = explode(',', $client['contacts']);
                $update = '';
                foreach ($haystack as $key) {
                    if ($key != $id) {
                        $update .= ',' . $key;
                    }
                }
                $update = substr($update, 1);
                $sql = "UPDATE clients SET contacts = '" . $update . "' WHERE client_id = " . $client['client_id'];
                $this->conn->query($sql);
            }
        }
    }

    public function linkContactClient($clients, $contact)
    {
        // Insert into DB
        foreach ($clients as $client) {
            $sql = "SELECT * FROM clients WHERE client_id = " . $client . "";
            $result = $this->conn->query($sql);
            $contactString = '';

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $contactString = $row['contacts'];
                }

                if (!strpos($contactString, $contact)) {
                    $update = '';
                    if ($contactString == '') {
                        $update = $contact;
                    } else {
                        $update = ',' . $contact;
                    }

                    $sql = "UPDATE clients SET contacts = CONCAT( clients.contacts , '" . $update . "') WHERE client_id = " . $client . "";
                    $result = $this->conn->query($sql);
                }
            }
        }

        // $sql = "SELECT * FROM clients WHERE client_id = " . $client . "";
        $sql = "SELECT * FROM clients  WHERE `contacts` LIKE '%" . $contact . "%'";
        $result = $this->conn->query($sql);


        if ($result->num_rows > 0) {
            $sql = "UPDATE contacts SET clients_linked = " . $result->num_rows . " WHERE contact_id = " . $contact . "";
            if ($this->conn->query($sql)) {
                return true;
            }
        }
        return false;
    }
}
