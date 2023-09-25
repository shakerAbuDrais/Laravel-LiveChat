<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit User Modal</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div id="editUserModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editUserForm" method="POST" action="{{ route('updateUser') }}">
                    @csrf <!-- Add CSRF token for security -->
                    <div class="modal-header">
                        <h4 class="modal-title">Edit User</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Select User</label>
                            <select id="userSelect" name="user_id" class="form-control">
                                <option value="">Select a user</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input id="userEmail" type="email" name="email" class="form-control" required>
                        </div>
                        <!-- Add a checkbox for user status -->
                        <div class="form-group">
                            <label>Status</label>
                            <input type="checkbox" name="status" value="active"> Active
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                        <input type="submit" class="btn btn-info" value="Save">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('.edit-icon').click(function() {
                var userId = $(this).data('userid');
                var userName = $(this).data('username');
                var userEmail = $(this).data('useremail');


                // Set the selected user in the dropdown based on the user's ID
                $('#userSelect').val(userId);

                // Populate the name and email fields
                $('#userName').val(userName);
                $('#userEmail').val(userEmail);

                $('#editUserModal').modal('show');
            });
        });
    </script>
</body>

</html>
