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
                    <form id="addStudentForm">
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
                            <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                            <input type="submit" class="btn btn-success" value="Add">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#user_id').on('change', function() {
                var userId = $(this).val();

                $.ajax({
                    url: '/getunassignedsubjects',
                    type: 'GET',
                    data: {
                        user_id: userId
                    },
                    success: function(response) {
                        $('#subject_id').html('');

                        response.forEach(function(subject) {
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
                        $('#user_id').html('');

                        response.forEach(function(user) {
                            $('#user_id').append('<option value="' + user.id + '">' +
                                user.name + '</option>');
                        });
                    }
                });
            });

            $('#addSubjectForm').submit(function(event) {
                event.preventDefault();

                var formData = new FormData(this);

                $.ajax({
                    url: '/assignSubject',
                    type: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            $('#addSubjectModal').modal('hide');

                            // Get the selected user ID and subject ID
                            var userId = $('#user_id').val();
                            var subjectId = $('#subject_id').val();

                            // Send an AJAX request to update the relationship
                            $.ajax({
                                url: '/assignSubject',
                                type: 'POST',
                                data: {
                                    user_id: userId,
                                    subject_id: subjectId
                                },
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                        'content')
                                },
                                success: function(assignmentResponse) {
                                    if (assignmentResponse.success) {
                                        console.log(
                                            'Subject assigned successfully.');
                                    } else {
                                        console.log('Error assigning subject: ' +
                                            assignmentResponse.message);
                                    }
                                }
                            });
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
