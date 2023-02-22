<?php

require_once '../app/core/Database.php';
class Clients extends Database
{
    public function getAll()
    {
        $clients = [];

        $sql = "SELECT * FROM clients ORDER BY client_name ASC";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $client = array(
                    'client_id' => $row['client_id'],
                    'client_name' => $row['client_name'],
                    'client_code' => $row['client_code'],
                    'contacts' => $row['contacts'],
                    'created_on' => $row['created_on']
                );

                array_push($clients, $client);
            }
        }
        return $clients;
    }
    public function addClient($name)
    {
        //Creating the alpha code of the clietn code
        $alpha = "";
        $alphanumeric = "";
        if (strlen($name) < 3) {
            while (strlen($alpha) < 3) {
                $alphabet = range('A', 'Z');
                $alpha .= $alphabet[rand(0, count($alphabet) - 1)];
            }
        } else {
            if (strpos($name, ' ')) {
                $words = explode(' ', $name);

                foreach ($words as $word) {
                    if (strlen($alpha) < 3) {
                        $alpha .= substr($word, 0, 1);
                    }
                }
            } else {
                $alpha .= substr($name, 0, 3);
            }
        }


        //Creating the numeric code of the clietn code
        $numric = str_pad($this->getLastEntry(), 3, '0', STR_PAD_LEFT);


        // Checking if it is unique
        $alphanumeric = strtoupper($alpha) . $numric;
        $unique = $this->ifUnique($alphanumeric);

        while (!$unique) {
            $unique = $this->ifUnique($alpha . $numric += 1);
            $alphanumeric = $alpha . str_pad($numric, 3, '0', STR_PAD_LEFT);
        }

        // Insert into DB
        $sql = "INSERT INTO Clients (client_name, client_code)
        VALUES ('" . $name . "', '" . $alphanumeric . "');";
        if ($this->conn->query($sql)) {
            return true;
        }
        return false;
    }
    public function getLastEntry()
    {
        $sql = "SELECT client_id FROM clients ORDER BY client_id DESC LIMIT 1";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                return $row['client_id'] + 1;
            }
        } else {
            return 1;
        }
    }
    public function ifUnique($code)
    {

        $sql = "SELECT client_code FROM clients WHERE client_code = '" . $code . "'";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            return false;
        } else {
            return true;
        }
    }
    public function linkContactsWithClient($client, $contacts)
    {
        // Insert into DB
        $status = false;
        $addComma = true;
        $contactUpdate = '';

        $sql = "SELECT * FROM clients WHERE client_id = " . $client . "";
        $result = $this->conn->query($sql);

        $list = '';
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $list = $row['contacts'];
            }
        }

        $list = explode(',', $list);


        if ($list[0] == '') {
            $addComma = false;
        }

        foreach ($contacts as $contact) {
            $found = false;

            foreach ($list as $listItem) {
                if ($listItem == $contact) {
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                if ($addComma) {
                    $contactUpdate = ',' . $contact;
                } else {
                    $contactUpdate = $contact;
                    $addComma = true;
                }

                $sql = "UPDATE clients SET contacts = CONCAT( clients.contacts , '" . $contactUpdate . "') WHERE client_id = " . $client . "";

                if ($this->conn->query($sql)) {
                    $sql = "UPDATE contacts SET clients_linked = clients_linked + 1 WHERE contact_id = " . $contact;
                    if ($this->conn->query($sql)) {
                        $status = true;
                    }
                }
            }
        }
        return $status;
    }
    public function unlink($id)
    {
        $clients = [];
        $sql = "SELECT * FROM clients  WHERE client_id = " . $id . "";
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
                $contacts = '';
                $contacts = explode(',', $client['contacts']);
                foreach ($contacts as $contact) {
                    $sql = "UPDATE contacts SET clients_linked = clients_linked - 1 WHERE contact_id = $contact AND clients_linked > 0";
                    $this->conn->query($sql);
                }
            }

            $sql = "UPDATE clients SET contacts = '' WHERE client_id = " . $id . "";
            $this->conn->query($sql);
        }
    }
}
