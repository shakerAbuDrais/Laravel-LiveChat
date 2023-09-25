<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Delete</title>
</head>

<body>
    <div id="deleteUserModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form>
                    <div class="modal-header">
                        <h4 class="modal-title">Delete User</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete these Records?</p>
                        <p class="text-warning"><small>This action cannot be undone.</small></p>
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                        <input type="submit" class="btn btn-danger" value="Delete">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Delete button click event
            $('.delete').click(function() {
                var userId = $(this).data('userid');
                var subjectId = $(this).data('subjectid');

                // Store a reference to the button
                var $deleteButton = $(this);

                // Set up the AJAX request to delete the relationship
                $.ajax({
                    url: '/deleteUser',
                    method: 'POST',
                    data: {
                        userId: userId,
                        subjectId: subjectId,
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        console.log('this is the closest tr', $deleteButton.closest('tr'));
                        if (response.success) {
                            // Remove the table row using the stored reference
                            $deleteButton.closest('tr').remove();
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                    }
                });

                // Prevent the default link behavior
                return false;
            });
        });
    </script>
</body>

</html>
