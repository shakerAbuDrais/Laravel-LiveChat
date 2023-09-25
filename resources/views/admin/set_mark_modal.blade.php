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
        <div id="SetMarkModal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- Remove the form element -->
                    <div class="modal-header">
                        <h4 class="modal-title">Set Mark</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Subject</label>
                            <select class="form-control" name="subject_id" id="subj_id">
                                <option value="">Select a subject</option>
                                <?php foreach ($subjects as $subject) : ?>
                                <option value="<?php echo $subject->id; ?>"><?php echo $subject->subject; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>User</label>
                            <select class="form-control" name="user_id" id="user__id">
                                <option value="">Select a user</option>
                                <?php foreach ($users as $user) : ?>
                                <option value="<?php echo $user->id; ?>"><?php echo $user->name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <!-- Add the obtained mark input field -->
                        <div class="form-group">
                            <label>Obtained Mark</label>
                            <input type="number" class="form-control" name="obtained_mark" id="obtained_mark"
                                placeholder="Enter obtained mark">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-success" id="SetMarkButton" value="Add">
                    </div>

                </div>
            </div>
        </div>

        <!-- Success message container -->
        <div id="successMessage" style="display: none;" class="alert alert-success">
            Mark Set Successfully.
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#user__id').on('change', function() {
                var userId = $(this).val();

                $.ajax({
                    url: '/getUnassignedSubjects',
                    type: 'GET',
                    data: {
                        user_id: userId
                    },
                    success: function(response) {
                        // Clear the subject dropdown
                        $('#subj_id').empty();

                        response.forEach(function(subject) {
                            // Add options to the subject dropdown
                            $('#subj_id').append('<option value="' + subject.id +
                                '">' + subject.subject + '</option>');
                        });
                    }
                });
            });

            $('#subj_id').on('change', function() {
                var subjectId = $(this).val();

                $.ajax({
                    url: '/getUsersWithoutSubject',
                    type: 'GET',
                    data: {
                        subject_id: subjectId
                    },
                    success: function(response) {
                        // Clear the user dropdown
                        $('#user__id').empty();

                        response.forEach(function(user) {
                            // Add options to the user dropdown
                            $('#user__id').append('<option value="' + user.id + '">' +
                                user.name + '</option>');
                        });
                    }
                });
            });

            // Handle the click event of the "Set Mark" button
            $(document).ready(function() {
                // Handle the click event of the "Set Mark" button
                $('#SetMarkButton').click(function() {
                    var formData = new FormData();

                    // Get the selected user ID, subject ID, and obtained mark
                    var userId = $('#user__id').val();
                    var subjectId = $('#subj_id').val();
                    var obtainedMark = $('#obtained_mark').val();

                    // Validate the obtained mark
                    if (userId === '' || subjectId === '' || obtainedMark === '') {
                        alert('Please select a user, subject, and enter the obtained mark.');
                        return;
                    }

                    // Add the selected values to the FormData object
                    formData.append('user_id', userId);
                    formData.append('subject_id', subjectId);
                    formData.append('obtained_mark', obtainedMark);

                    $.ajax({
                        url: '/storeMark',
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

                                // Clear the selected options and obtained mark in both dropdowns
                                $('#user__id, #subj_id, #obtained_mark').val('');

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
        });
    </script>

</body>

</html>
