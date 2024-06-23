@php
    $user = auth()->user();
    $role = $user->roles ?? '';
@endphp

@extends('layouts.app', [
    'namePage' => 'Table List',
    'class' => 'sidebar-mini',
    'activePage' => 'table',
])

@if($role !== 'manager')
    <style>
        body {
            overflow: hidden;
        }
    </style>
    <div class="content d-flex justify-content-center align-items-center" style="height: 100vh;">
        <div class="alert alert-danger text-center">
            <strong>Error:</strong> You cannot view this page.
        </div>
    </div>
@endif

@if($role === 'manager')
    @section('content')
        <div class="panel-header panel-header-sm">
        </div>
        <div class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Cashier List</h4>
                        <a href="#" id="sendEmailBtn" class="btn btn-info" data-toggle="modal" data-target="#emailModal">
                            Compose Email <i class="fas fa-envelope"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                            
                            <div class="table-responsive">
                                <form id="searchForm" action="{{ route('table.search') }}" method="GET" class="form-inline">
                                    <div class="input-group">
                                        <input type="text" id="searchInput" name="search" class="form-control" placeholder="Search by name">
                                        <div class="input-group-append">
                                            <button type="submit" id="searchButton" class="btn btn-primary">Search</button>
                                        </div>
                                </form>
                            </div>
                        </td>
                        
                                <table class="table">
                                    <thead class="text-primary">
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($addcashiers as $addcashier)
                                            @if($addcashier->roles !== 'manager')
                                                <tr id="user-{{ $addcashier->id }}">
                                                    <td>{{ $addcashier->name }}</td>
                                                    <td>{{ $addcashier->email }}</td>
                                                    <td>
                                                    <div class="btn-group align-items-center" role="group">
                                                    <form class="delete-form" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-danger delete-btn" style="height: 38px; width: 100px;" data-encrypted-id="{{ Crypt::encrypt($addcashier->id) }}">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                    
                                                <a href="#" class="btn btn-primary edit-btn" onclick="openEditModal('{{ Crypt::encrypt($addcashier->id) }}', '{{ $addcashier->first_name }}', '{{ $addcashier->middle_name }}', '{{ $addcashier->last_name }}', '{{ $addcashier->email }}')" style="margin-left: 5px; height: 38px; width: 100px;">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                                                                
                                                    </div>
                                                </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                                </div>
                                <!-- Pagination Links -->
                        <div class="d-flex justify-content-center">
                    {{ $addcashiers->links('vendor.pagination.custom') }}
                </div>
                            </div>
                        </div>
                    </div>
                <!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="editUserForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="editUserFirstName">First Name</label>
                        <input type="text" class="form-control" id="editUserFirstName" name="first_name" required>
                    </div>
                    <div class="form-group">
                        <label for="editUserMiddleName">Middle Name</label>
                        <input type="text" class="form-control" id="editUserMiddleName" name="middle_name">
                    </div>
                    <div class="form-group">
                        <label for="editUserLastName">Last Name</label>
                        <input type="text" class="form-control" id="editUserLastName" name="last_name" required>
                    </div>
                    <div class="form-group">
                        <label for="editUserEmail">Email</label>
                        <input type="email" class="form-control" id="editUserEmail" name="email" required readonly>
                        <small class="form-text text-muted">This email has been used for account registration. It can't be changed.</small>
                    </div>
                    <div class="form-group">
                        <label for="editUserPassword">Password</label>
                        <input type="password" class="form-control" id="editUserPassword" name="password">
                    </div>
                    <div class="form-group">
                        <label for="editUserConfirmPassword">Confirm Password</label>
                        <input type="password" class="form-control" id="editUserConfirmPassword" name="password_confirmation">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>


        <div id="emailModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Send Email</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
                <form id="emailForm">
                <div class="modal-body">
                    <div class="form-group">
                    <label for="fromEmail">From:</label>
                    <input type="text" class="form-control" id="fromEmail" value="{{ Auth::user()->email }}" readonly>
                    </div> 
                    <div class="form-group">   
                    <label for="recipientEmail">To:</label>
                        <input type="email" class="form-control" id="recipientEmail" name="recipientEmail" placeholder="example@email.com" required>
                    </div>
                    <div class="form-group">
                        <label for="emailSubject">Subject</label>
                        <input type="text" class="form-control" id="emailSubject" name="emailSubject" placeholder="Email Subject" required>
                    </div>
                    <div class="form-group">
                        <label for="emailContent">Content</label>
                        <textarea class="form-control" id="emailContent" name="emailContent" placeholder="Compose your email..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Send Email</button>
                </div>
            </form>
        </div>
    </div>
</div>

      
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <!-- jQuery CDN -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <script>
   document.addEventListener("DOMContentLoaded", function() {
        const deleteButtons = document.querySelectorAll('.delete-btn');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault();

                const form = button.closest('.delete-form');
                const encryptedId = button.getAttribute('data-encrypted-id');
                form.action = '/table/' + encryptedId;

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You will not be able to recover this user!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                         // Submit the form after confirmation
                    }
                });
            });
        });
    });
    @if(Session::has('delete_message'))
        Swal.fire(
            'Success!',
            '{{ Session::get('delete_message') }}',
            'success'
        );
    @endif

    @if(Session::has('error'))
        Swal.fire(
            'Error',
            '{{ Session::get('error') }}',
            'error'
        );
    @endif

    @if(Session::has('edit_message'))
        Swal.fire(
            'Success!',
            '{{ Session::get('edit_message') }}',
            'success'
        );
    @endif

    @if(Session::has('edit_errormessage'))
        Swal.fire(
            'Error!',
            '{{ Session::get('edit_errormessage') }}',
            'error'
        );
    @endif


    function openEditModal(userId, userFirstName, userMiddleName, userLastName, userEmail) {
        $('#editUserModal').modal('show');
        $('#editUserForm').attr('action', '/table/' + userId);
        $('#editUserFirstName').val(userFirstName);
        $('#editUserMiddleName').val(userMiddleName);
        $('#editUserLastName').val(userLastName);
        $('#editUserEmail').val(userEmail);
        $('#editUserPassword').val('');
        $('#editUserConfirmPassword').val('');
    }
</script>


        <script>
            // Function to handle search form submission
            $('#searchForm').on('submit', function(event) {
                // Prevent the default form submission behavior
                event.preventDefault();

                // Get the search query from the input field
                var searchQuery = $('#searchInput').val();

                // AJAX request to search endpoint
                $.ajax({
                    url: '{{ route("table.search") }}',
                    type: 'GET',
                    data: { search: searchQuery },
                    success: function(data) {
                        // Display search results in the table body
                        $('#searchResults').html(data);
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });

            // Function to handle search button click
            $('#searchButton').on('click', function() {
                // Trigger form submission when search button is clicked
                $('#searchForm').submit();
            });
        </script>
        <script>
         document.addEventListener('DOMContentLoaded', function () {
        const emailForm = document.getElementById('emailForm');

        emailForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const recipientEmail = document.getElementById('recipientEmail').value;
            const emailSubject = document.getElementById('emailSubject').value;
            const emailContent = document.getElementById('emailContent').value;

            Swal.fire({
                title: 'Send Email',
                text: 'Are you sure you want to send the email?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, send it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Send email using AJAX
                    fetch('/send-email', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            recipientEmail: recipientEmail,
                            emailSubject: emailSubject,
                            emailContent: emailContent
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: "Success",
                                text: data.success,
                                icon: "success"
                            });
                        } else if (data.error) {
                            Swal.fire({
                                title: "Error",
                                text: data.error,
                                icon: "error"
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                }
            });
        });
    });
</script>
    @endsection
@endif

@push('scripts')
@endpush
