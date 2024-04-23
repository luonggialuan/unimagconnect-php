<?php
include_once ("./connect.php");
session_start();
if (!isset ($_SESSION['username'])) {
    header("Location: public/view/signin.php");
    exit();
}
include_once ("public/view/home/header.php");

?>
<?php
if (isset ($_GET['page'])) {
    $page = $_GET['page'];
    if ($page == "profile") {
        include_once ("./profile/profile.php");
    } elseif ($page == "dangky") {
        include_once ("./dang_ky.php");
    } elseif ($page == "statistics") {
        include_once ("./public/view/statistics.php");
    } elseif ($page == "magazineStudent") {
        include_once ("public/view//magazineStudent.php");
    } elseif ($page == "addArticleStudent") {
        include_once ("public/view/addArticleStudent.php");
    } elseif ($page == "updateArticleStudent") {
        include_once ("public/view/updateArticleStudent.php");
    } elseif ($page == "your-articles") {
        include_once ("public/view/your-articles.php");
    } elseif ($page == "signin") {
        include_once ("public/view/signin.php");
    } elseif ($page == "logout") {
        include_once ("public/view/logout.php");
    } elseif ($page == "upload.php") {
        include_once ("./test-upload.php");
    }
} else {
    include_once ("public/view/home/home.php");
}
?>


<!-- footer -->
<!-- <footer>
    <div class="container-xl">
        <div class="footer-inner">
            <div class="row d-flex align-items-center gy-4">
                <div class="col-md-4">
                    <span class="copyright">© 2024</span>
                </div>
                <div class="col-md-4 text-center">
                </div>
                <div class="col-md-4">
                    <a href="#" id="return-to-top" class="float-md-end"><i class="icon-arrow-up"></i>Back to Top</a>
                </div>
            </div>
        </div>
    </div>
</footer> -->
<?php
include_once ("public/view/home/script.php");
?>
<script>
    function confirmLogout() {
        // Sử dụng SweetAlert2 thay vì hàm confirm
        Swal.fire({
            title: 'Are you sure?',
            text: 'Are you sure you want to log out?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '?page=logout';
            }
        });
    }

    const fileImageInput = document.getElementById('thumb');
    const previewImage = document.getElementById('preview');

    fileImageInput.addEventListener('change', function (e) {
        const file = e.target.files[0]; // Get the selected file

        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();

            reader.onload = function (e) {
                previewImage.src = e.target.result; // Set the image source
                previewImage.style.display = 'block'; // Show the preview
            };

            reader.readAsDataURL(file); // Read the file as data URL
        } else {
            previewImage.src = ""; // Clear preview if not an image
            previewImage.style.display = 'none';
        }
    });


    const fileWordInput = document.getElementById('files');
    const selectedFilesSpan = document.getElementById('selectedFiles');

    fileWordInput.addEventListener('change', function (e) {
        const files = e.target.files;

        if (files.length > 0) {
            let selectedFileNames = "";
            for (let i = 0; i < files.length; i++) {
                selectedFileNames += files[i].name + (i < files.length - 1 ? "<br>" : "");
            }
            selectedFilesSpan.innerHTML = selectedFileNames; // Use innerHTML for HTML tags
        } else {
            selectedFilesSpan.textContent = "No files chosen";
        }
    });
</script>
<footer style="position: relative; bottom: 0; width: 100%;">
    <div class="container-xl">
        <div class="footer-inner">
            <div class="row d-flex align-items-center gy-4">
                <!-- copyright text -->
                <div class="col-md-4">
                    <span class="copyright">© 2024</span>
                </div>

                <!-- social icons -->
                <div class="col-md-4 text-center">
                    <ul class="social-icons list-unstyled list-inline mb-0">
                        <li class="list-inline-item"><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                        <li class="list-inline-item"><a href="#"><i class="fab fa-twitter"></i></a></li>
                        <li class="list-inline-item"><a href="#"><i class="fab fa-instagram"></i></a></li>
                        <li class="list-inline-item"><a href="#"><i class="fab fa-pinterest"></i></a></li>
                        <li class="list-inline-item"><a href="#"><i class="fab fa-medium"></i></a></li>
                        <li class="list-inline-item"><a href="#"><i class="fab fa-youtube"></i></a></li>
                    </ul>
                </div>

                <!-- go to top button -->
                <div class="col-md-4">
                    <a href="#" id="return-to-top" class="float-md-end"><i class="icon-arrow-up"></i>Back to Top</a>
                </div>
            </div>
        </div>
    </div>
</footer>

</html>
<script>
    // Chờ 3 giây sau đó gửi yêu cầu HTTP để xóa session
    setTimeout(function() {
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "./public/src/deleteSession.php", true);
        xhr.send();
    }, 3000); // 3 giây
</script>

<?php
include_once ("./public/src/logAccess.php");
?>