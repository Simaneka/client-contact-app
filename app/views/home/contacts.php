<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phonebook - Contacts</title>
    <link rel="stylesheet" href="../public/css/bootstrap.min.css">
    <link rel="stylesheet" href="../public/css/style.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="./">PhoneBook</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarColor01">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="./client">Clients</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./contact">Contacts</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container d-flex flex-column justify-content-center align-items-center mt-5">
        <h1>Contacts</h1>
        <?php if (count($data[0]) > 0) : ?>
            <p>List of Clients</p>
            <table>
                <tr>
                    <th>Contact Name</th>
                    <th>Contact Surame</th>
                    <th>Contact Email</th>
                    <th class="text-center">Clients Linked</th>
                </tr>

                <?php foreach ($data[0] as $contact) : ?>
                    <tr>
                        <td><?= $contact['contact_name'] ?></td>
                        <td><?= $contact['contact_surname'] ?></td>
                        <td><?= $contact['contact_email'] ?></td>
                        <td class="text-center"><?= $contact['clients_linked'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else : ?>
            <p>No contact(s) found.</p>
        <?php endif; ?>
    </div>


    <button type="button" class="btn btn-primary createClient" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
        Create Client
    </button>
    <button type="button" class="btn btn-success linkContacts" data-bs-toggle="modal" data-bs-target="#staticBackdrop2">
        Link
    </button>

    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Add Contact</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab" aria-controls="general" aria-selected="true">General</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="clients-tab" data-bs-toggle="tab" data-bs-target="#clients" type="button" role="tab" aria-controls="clients" aria-selected="false">Clients(s)</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                            <div class="mb-3 mt-3">
                                <input type="text" class="form-control" id="clientName" placeholder="Client Name">
                            </div>
                            <div class="mb-3 mt-3">
                                <input type="text" class="form-control" id="clientSurname" placeholder="Client Surname">
                            </div>
                            <div class="mb-3">
                                <input type="email" class="form-control" id="clientEmail" placeholder="Email Address">
                            </div>
                            <div class="mb-3">
                                <div class="alert d-none" role="alert">
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="clients" role="tabpanel" aria-labelledby="clients-tab">
                            <?php if ($data[2] > 0) : ?>
                                <table class="mt-3">
                                    <tr>
                                        <th>Client Name</th>
                                        <th>Client Code</th>
                                        <th></th>
                                    </tr>

                                    <?php foreach ($data[1] as $client) : ?>

                                        <?php if ($client['contacts'] != 0) : ?>
                                            <tr>
                                                <td><?= $client['client_name'] ?></td>
                                                <td><?= $client['client_code'] ?></td>
                                                <td><a href="./client/unlink/<?= $client['client_id'] ?>">Unlink</a></td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </table>
                            <?php else : ?>
                                <p class="mt-3 text-center">No clients(s) found.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary submitBtn">Create</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="staticBackdrop2" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="linkTitle">Link Contact To Client</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Contacts</label>
                        <select id="contactsOpts" class="form-control">
                            <?php foreach ($data[0] as $contact) : ?>
                                <option class="form-control" value="<?= $contact['contact_id'] ?>"><?= $contact['contact_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Client</label>
                        <select id="clientsOpts" class="form-control" multiple>
                            <?php foreach ($data[1] as $client) : ?>
                                <option class="form-control" value="<?= $client['client_id'] ?>"><?= $client['client_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3 mt-1">
                        <div class="alert alert2 d-none" role="alert">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary saveBtn">Save</button>
                </div>
            </div>
        </div>
    </div>

    <script src="../public/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js" integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $('.submitBtn').click(function() {
            var clientName = $('#clientName').val();
            var clientSurname = $('#clientSurname').val();
            var clientEmail = $('#clientEmail').val();
            var submit = false;

            if (clientName == '') {
                submit = false
                $('#clientName').css('border', 'solid 1px #ce2021');
            } else {
                submit = true
                $('#clientName').css('border', 'solid 1px #e1e1e1');
            }
            if (clientSurname == '') {
                submit = false
                $('#clientSurname').css('border', 'solid 1px #ce2021');
            } else {
                submit = true
                $('#clientSurname').css('border', 'solid 1px #e1e1e1');
            }
            if (clientEmail == '') {
                submit = false
                $('#clientEmail').css('border', 'solid 1px #ce2021');
            } else {
                submit = true
                $('#clientEmail').css('border', 'solid 1px #e1e1e1');
            }
            if (!clientEmail.includes('@')) {
                submit = false
                $('#clientEmail').css('border', 'solid 1px #ce2021');
            } else {
                submit = true
                $('#clientEmail').css('border', 'solid 1px #e1e1e1');
            }
            if (!clientEmail.includes('.')) {
                submit = false
                $('#clientEmail').css('border', 'solid 1px #ce2021');
            } else {
                submit = true
                $('#clientEmail').css('border', 'solid 1px #e1e1e1');
            }



            if (submit) {

                $.ajax({
                    url: 'contact/validateEmail',
                    type: "POST",
                    data: {
                        clientEmail: clientEmail,
                    },
                    success: function(response) {
                        response = JSON.parse(response)
                        if (response.status) {
                            $('#clientEmail').css('border', 'solid 1px #ce2021');
                        } else {
                            $('#clientEmail').css('border', 'solid 1px #e1e1e1');
                            $(".submitBtn").attr("disabled", true);
                            $.ajax({
                                url: 'contact/add',
                                type: "POST",
                                data: {
                                    clientName: clientName,
                                    clientSurname: clientSurname,
                                    clientEmail: clientEmail,
                                },
                                success: function(response) {
                                    response = JSON.parse(response)
                                    $('.alert').removeClass('d-none');
                                    $('.alert').addClass('alert-' + response.color);
                                    $('.alert').html(response.message);
                                    setTimeout(function() {
                                        $('.alert').addClass('d-none');
                                        $(".submitBtn").attr("disabled", false);
                                        $('#clientName').val('');
                                        $('#clientEmail').val('');
                                    }, 3000);
                                },
                                error: function(jqXHR, textStatus, errorThrown) {
                                    $('.alert').addClass('alert-danger');
                                    $('.alert').removeClass('d-none');
                                    $('.alert').html(errorThrown);
                                    $('.alert').delay(10000).addClass('d-none');
                                    // $('.alert').delay(10000).addClass('d-none');
                                }
                            });
                        }
                    },
                });
            }
        })

        $('.saveBtn').click(function() {
            var contact = $('#contactsOpts').val();
            var clients = $('#clientsOpts').val();

            $(".saveBtn").attr("disabled", true);
            $.ajax({
                url: 'contact/link',
                type: "POST",
                data: {
                    contacts: contact,
                    client: clients,
                },
                success: function(response) {
                    response = JSON.parse(response)
                    $('.alert').removeClass('d-none');
                    $('.alert').addClass('alert-' + response.color);
                    $('.alert').html(response.message);
                    setTimeout(function() {
                        $('.alert').addClass('d-none');
                        $(".saveBtn").attr("disabled", false);
                    }, 3000);
                }
            });
        })
    </script>
</body>

</html>