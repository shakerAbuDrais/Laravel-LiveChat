<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>

<body>
    <div class="container">
        <div id="AssignSubjectModal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- Remove the form element -->
                    <div class="modal-header">
                        <h4 class="modal-title">Add New User</h4>
                        <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Subject</label>
                            <select class="form-control" name="subject_id" id="subject_id">
                                <option value="">Select a subject</option>
                                <?php foreach ($subjects as $subject) : ?>
                                <option value="<?php echo $subject->id; ?>"><?php echo $subject->subject; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>User</label>
                            <select class="form-control" name="user_id" id="user_id">
                                <option value="">Select a user</option>
                                <?php foreach ($users as $user) : ?>
                                <option value="<?php echo $user->id; ?>"><?php echo $user->name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <!-- Replace the submit input with a regular button -->
                        <input type="button" class="btn btn-success" id="addSubjectButton" value="Add">
                    </div>
                </div>
            </div>
        </div>

        <!-- Success message container -->
        <div id="successMessage" style="display: none;" class="alert alert-success">
            Subject assigned successfully.
        </div>
    </div>

    <script>
    $(document).ready(function() {
        $('#user_id').on('change', function() {
            var userId = $(this).val();

            $.ajax({
                url: '/getUnassignedSubjects',
                type: 'GET',
                data: {
                    user_id: userId
                },
                success: function(response) {
                    // Clear the subject dropdown
                    $('#subject_id').empty();

                    response.forEach(function(subject) {
                        // Add options to the subject dropdown
                        $('#subject_id').append('<option value="' + subject.id +
                            '">' + subject.subject + '</option>');
                    });
                }
            });
        });

        $('#subject_id').on('change', function() {
            var subjectId = $(this).val();

            $.ajax({
                url: '/getUsersWithoutSubject',
                type: 'GET',
                data: {
                    subject_id: subjectId
                },
                success: function(response) {
                    // Clear the user dropdown
                    $('#user_id').empty();

                    response.forEach(function(user) {
                        // Add options to the user dropdown
                        $('#user_id').append('<option value="' + user.id + '">' +
                            user.name + '</option>');
                    });
                }
            });
        });

        // Handle the click event of the "Add" button
        $('#addSubjectButton').click(function() {
            var formData = new FormData();

            // Get the selected user ID and subject ID
            var userId = $('#user_id').val();
            var subjectId = $('#subject_id').val();

            // Add the selected values to the FormData object
            formData.append('user_id', userId);
            formData.append('subject_id', subjectId);

            $.ajax({
                url: '/assignSubject', // Replace with your URL for assigning subjects
                type: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        // Show the success message
                        $('#successMessage').show();

                        // Clear the selected options in both dropdowns
                        $('#user_id, #subject_id').val('');

                        // Hide the success message after 3 seconds
                        setTimeout(function() {
                            $('#successMessage').hide();
                        }, 3000);
                    } else {
                        alert('Error: ' + response.message);
                    }
                }
            });
        });
    });
    </script>
</body>

</html>
