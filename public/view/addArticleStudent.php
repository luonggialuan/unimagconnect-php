<head>
    <meta name="viewport" content="width=device-width">
    <title>Submit Article</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
        integrity="sha384-YHM+0q4qoym3zT4iuZUzRSVfLRtTjxiN0PF/Kea0I3ftgXg5H2iM3uT4vKl3gjFj" crossorigin="anonymous">

</head>

<?php
$idMagazine = $_GET['id'];

if (isset($_SESSION['returnError']) && $_SESSION['returnError'] !== null) {
    $title = $_SESSION['returnError']; // Tạo tiêu đề có chứa $_SESSION['return']
    echo "<script>";
    echo "Swal.fire({";
    echo "    position: 'center',";
    echo "    icon: 'error',"; // 'Errors' corrected to 'error'
    echo "    title: '" . $title . "',"; // concatenate $title variable
    echo "    showConfirmButton: true,";
    echo "    timer: 3000";
    echo "});";
    echo "</script>";

    unset($_SESSION['returnError']);
}
?>
<div class="container">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 d-flex align-items-stretch">
                        <div class="card w-100">
                            <div class="card-body p-4">
                                <form action="./public/src/upload.php" method="post" enctype="multipart/form-data"
                                    class="p-3 shadow-sm rounded bg-white">
                                    <h5 class="card-title fw-semibold mb-4 text-center">Submit Article</h5>
                                    <div class="mb-3">
                                        <label for="article_title" class="form-label">Title:</label>
                                        <input type="text" class="form-control" id="article_title" name="article_title"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="article_content" class="form-label">Content:</label>
                                        <textarea class="form-control" id="article_content" name="article_content"
                                            rows="4" required></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="files" class="form-label">Upload Files:</label>
                                        <input type="file" class="form-control" id="files" name="files[]"
                                            accept=".docx, .doc" multiple style="display: none;">
                                        <button type="button" class="btn btn-primary"
                                            onclick="document.getElementById('files').click();">Add File</button>
                                        <p id="selectedFiles"></p>
                                    </div>
                                    <div class="mb-3">
                                        <label for="image" class="form-label">Upload Image:</label>
                                        <input type="file" class="form-control" id="thumb" name="image"
                                            accept=".jpg, .png" required>

                                    </div>
                                    <div class="mb-3">
                                        <div class="col-md-4">
                                            <img id="preview" src="" alt="Image Preview"
                                                style="display:none; max-width: 200px; max-height:200px;">
                                        </div>
                                    </div>

                                    <input type="hidden" id="magazineId" name="magazineId" value="<?= $idMagazine ?>">
                                    <div class="mb-3 form-check">
                                        <input type="checkbox" class="form-check-input" id="confirmation_checkbox"
                                            name="confirmation_checkbox" required>
                                        <label class="form-check-label" for="confirmation_checkbox">I agree with Terms
                                            and Conditions</label>
                                    </div>
                                    <button id="SubmitNew" name="SubmitNew" type="submit"
                                        class="btn btn-primary d-block mx-auto">Submit Articles</button>

                                    <span id="submit-error"></span>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById('files').addEventListener('change', function () {
        const input = document.getElementById('files');
        const files = input.files;
        const selectedFilesDiv = document.getElementById('selectedFiles');

        for (let i = 0; i < files.length; i++) {
            const fileDiv = document.createElement('div');
            fileDiv.classList.add('d-flex', 'align-items-center');

            const fileName = document.createElement('span');
            fileName.innerText = files[i].name;
            fileName.classList.add('me-2');
            fileDiv.appendChild(fileName);

            const removeButton = document.createElement('button');
            removeButton.innerHTML = '&times;';
            removeButton.classList.add('btn', 'btn-sm', 'btn-danger');
            removeButton.type = 'button';
            removeButton.addEventListener('click', function () {
                fileDiv.remove();
            });
            fileDiv.appendChild(removeButton);

            selectedFilesDiv.appendChild(fileDiv);
        }
    });
</script>
<script>
    document.getElementById('confirmation_checkbox').addEventListener('click', function () {
        Swal.fire({
            title: 'Terms and Conditions for Report Submission',
            html: `
            <div style="max-width: 100%; margin: 0 auto; padding: 0 20px;">
                <ol style="padding-left: 0;">
                    <li>
                        <h3 style="text-align: left;">Content Ownership:</h3>
                        <p style="text-align: justify;">By submitting a report, you affirm that you are the rightful owner of the content or have the necessary permissions to submit it. You retain ownership of the intellectual property rights to your submitted reports.</p>
                    </li>

                    <li>
                        <h3 style="text-align: left;">Originality and Attribution:</h3>
                        <p style="text-align: justify;">You agree that all reports submitted are original works created by you and do not infringe upon the intellectual property rights of any third party. Proper attribution must be provided for any referenced or cited materials.</p>
                    </li>

                    <li>
                        <h3 style="text-align: left;">Accuracy and Legality:</h3>
                        <p style="text-align: justify;">You are solely responsible for the accuracy and legality of the content submitted. The report must not contain any false, misleading, or unlawful information.</p>
                    </li>

                    <li>
                        <h3 style="text-align: left;">Non-Commercial Use:</h3>
                        <p style="text-align: justify;">Reports submitted must not be used for commercial purposes unless explicit permission is granted by the rightful owner of the content.</p>
                    </li>

                    <li>
                        <h3 style="text-align: left;">Compliance with Guidelines:</h3>
                        <p style="text-align: justify;">All submitted reports must adhere to the formatting and content guidelines provided by the platform. Failure to comply may result in the rejection of the submission.</p>
                    </li>

                    <li>
                        <h3 style="text-align: left;">Use of Submitted Reports:</h3>
                        <p style="text-align: justify;">By submitting a report, you grant the platform the non-exclusive right to use, reproduce, modify, adapt, publish, translate, distribute, and display the content worldwide in any media.</p>
                    </li>

                    <li>
                        <h3 style="text-align: left;">Indemnification:</h3>
                        <p style="text-align: justify;">You agree to indemnify and hold harmless the platform and its affiliates from any claims, damages, liabilities, costs, or expenses arising out of the submission of your report or any breach of these terms and conditions.</p>
                    </li>

                    <li>
                        <h3 style="text-align: left;">Termination of Access:</h3>
                        <p style="text-align: justify;">The platform reserves the right to terminate access to the submission feature for users who violate these terms and conditions or engage in any form of misconduct.</p>
                    </li>
                </ol>

                <p style="text-align: justify;">By submitting a report, you acknowledge that you have read, understood, and agree to abide by all the terms and conditions outlined above regarding the submission of reports.</p>

                <input type="checkbox" id="acceptCheckbox" >
                <label for="acceptCheckbox" style="text-align: left;">I have read and agree to the terms.</label>
            </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Accept',
            cancelButtonText: 'Cancel',
            focusConfirm: false,
            preConfirm: () => {
                if (!document.getElementById('acceptCheckbox').checked) {
                    Swal.showValidationMessage('Please agree to the terms.');
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('articleForm').submit();
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                document.getElementById('confirmation_checkbox').checked = false;
            }
        });
    });
</script>