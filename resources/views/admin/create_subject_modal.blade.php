<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>

<body>
    <div class="container">
        <div id="CreateSubjectModal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="addSubjectForm" method="post">
                        @csrf <!-- Add CSRF token -->
                        <div class="modal-header">
                            <h4 class="modal-title">Add New Subject</h4>
                            <button type="button" class="close" data-dismiss="modal"
                                aria-hidden="true">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Subject Name</label>
                                <input type="text" class="form-control" name="subject" required>
                            </div>
                            <div class="form-group">
                                <label>Minimum Mark to Pass</label>
                                <input type="number" class="form-control" name="pass_mark" required>
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
            $('#addSubjectForm').submit(function(event) {
                event.preventDefault();

                var formData = new FormData(this);

                $.ajax({
                    url: '/createSubject',
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
                            // $('#studentTable').append('<tr><td>' + response.user.name +
                            //     '</td><td>' + response.user.email + '</td></tr>');
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
