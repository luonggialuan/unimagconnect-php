<head>
    <meta name="viewport" content="width=device-width">
    <title>Submit Article</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
        integrity="sha384-YHM+0q4qoym3zT4iuZUzRSVfLRtTjxiN0PF/Kea0I3ftgXg5H2iM3uT4vKl3gjFj" crossorigin="anonymous">

</head>

<?php
$idArticle = $_GET['id'];
$userId = $_SESSION['userid'];

if (isset ($_SESSION['returnError']) && $_SESSION['returnError'] !== null) {
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

if (isset ($_SESSION['return']) && $_SESSION['return'] !== null) {
    $title = $_SESSION['return']; // Tạo tiêu đề có chứa $_SESSION['return']
    echo "<script>";
    echo "Swal.fire({";
    echo "    position: 'center',";
    echo "    icon: 'success',";
    echo "    title: '$title',";
    echo "    showConfirmButton: false,";
    echo "    timer: 3000";
    echo "});";
    echo "</script>";

    unset($_SESSION['return']);
}

$sql_user_artile = "SELECT * FROM articles WHERE authorId = $userId AND articleId = $idArticle";
$result = $conn->query($sql_user_artile);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
}

$articleId = $row["articleId"];
$sql_article_files = "SELECT * FROM files WHERE articleId = $articleId";
$resultFile = $conn->query($sql_article_files);


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
                                    <h5 class="card-title fw-semibold mb-4 text-center">Update Article</h5>
                                    <div class="mb-3">
                                        <label for="article_title" class="form-label">Title:</label>
                                        <input type="hidden" name="articleId" id="articleId"
                                            value=<?= $row['articleId'] ?>>
                                        <input type="text" class="form-control" id="article_title" name="article_title"
                                            value="<?= $row['title'] ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="article_content" class="form-label">Content:</label>
                                        <textarea class="form-control" id="article_content" name="article_content"
                                            rows="4" value="<?= $row['content'] ?>"
                                            required><?= $row['content'] ?></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="files" class="form-label">Upload Files:</label>
                                        <input type="file" class="form-control" id="files" name="files[]" value=""
                                            accept=".docx, .doc" multiple style="display: none;">
                                        <button type="button" class="btn btn-primary"
                                            onclick="document.getElementById('files').click();">Add File</button>
                                        <p id="selectedFiles"></p>
                                        <?php
                                        if ($resultFile->num_rows > 0) {
                                            while ($rowFile = $resultFile->fetch_assoc()) {
                                                ?>
                                        <p>
                                            <?= basename($rowFile['filePath']) ?>
                                        </p>
                                        <?php
                                            }
                                        }
                                        ?>

                                    </div>
                                    <div class="mb-3">
                                        <label for="image" class="form-label">Upload Image:</label>
                                        <input type="file" class="form-control" id="thumb" name="image"
                                            accept=".jpg, .png">

                                    </div>
                                    <div class="mb-3">
                                        <div class="col-md-4">
                                            <img id="preview" src="<?= $row['image'] ?>" alt="Image Preview" style="display:<?php (isset ($row['image'])) ?
                                                  "block" : "none" ?>; max-width: 200px;">
                                        </div>
                                    </div>

                                    <input type="hidden" id="magazineId" name="magazineId" value="<?= $idMagazine ?>">
                                    <div class="mb-3 form-check">
                                        <input type="checkbox" class="form-check-input" id="confirmation_checkbox"
                                            name="confirmation_checkbox" required>
                                        <label class="form-check-label" for="confirmation_checkbox">I confirm that
                                            the information provided is accurate</label>
                                    </div>
                                    <button id="Update" name="Update" type="submit"
                                        class="btn btn-primary d-block mx-auto">Update</button>

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
document.getElementById('files').addEventListener('change', function() {
    // const input = document.getElementById('files');
    const input = $files;
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
        removeButton.addEventListener('click', function() {
            fileDiv.remove();
        });
        fileDiv.appendChild(removeButton);

        selectedFilesDiv.appendChild(fileDiv);
    }
});
</script>