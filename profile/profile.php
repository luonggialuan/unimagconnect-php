<?php

if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    }
} else {
    echo "Error.";
}

if (isset($_POST['UpdateProfile'])) {
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
        $name = $_POST['name'];
        $address = $_POST['address'];
        $aboutYou = $_POST['aboutYou'];

        // Kiểm tra xem có tệp ảnh đã được tải lên không
        if (isset($_FILES['avt']) && $_FILES['avt']['error'] !== UPLOAD_ERR_NO_FILE) {
            $file_name = $_FILES['avt']['name'];
            $file_tmp = $_FILES['avt']['tmp_name'];
            $file_destination = "./uploads/" . $file_name; // Đường dẫn đến thư mục uploads

            // Di chuyển tệp ảnh tải lên vào thư mục 'uploads'
            if (move_uploaded_file($file_tmp, $file_destination)) {
                // Nếu di chuyển thành công, thực hiện cập nhật thông tin trong CSDL
                $updateSql = "UPDATE users SET address=?, name=?, avatar=?, aboutYou=? WHERE username=?";
                $stmt = $conn->prepare($updateSql);
                $stmt->bind_param("sssss", $address, $name, $file_destination, $aboutYou, $username);
                if ($stmt->execute()) {
                    echo "<script>";
                    echo "Swal.fire({";
                    echo "    icon: 'success',";
                    echo "    title: 'The information has been updated successfully',";
                    echo "})";
                    echo "</script>";
                    echo "<script>window.history.go(-1);</script>";
                } else {
                    echo "Lỗi: " . $conn->error;
                    echo "<script>window.history.go(-1);</script>";
                }
            } else {
                // Nếu di chuyển không thành công, thông báo lỗi
                echo "<script>alert('Error uploading image file')</script>";
                echo "<script>";
                echo "Swal.fire({";
                echo "    icon: 'error',";
                echo "    title: 'The information has been updated successfully',";
                echo "})";
                echo "</script>";
                echo "<script>window.history.go(-1);</script>";
            }
        } else {
            // Nếu không có tệp ảnh được tải lên, chỉ thực hiện cập nhật thông tin trong CSDL
            $updateSql = "UPDATE users SET address=?, name=?, aboutYou=? WHERE username=?";
            $stmt = $conn->prepare($updateSql);
            $stmt->bind_param("ssss", $address, $name, $aboutYou, $username);
            if ($stmt->execute()) {
                echo "<script>alert('Thông tin đã được cập nhật thành công')</script>";
                echo "<script>window.history.go(-1);</script>";
            } else {
                echo "Lỗi: " . $conn->error;
                echo "<script>window.history.go(-1);</script>";
            }
        }
    } else {
        echo "Lỗi: Tên người dùng không tồn tại trong phiên.";
        echo "<script>window.history.go(-1);</script>";
    }
}


if (isset($_POST['changePassword'])) {
    $username = $_SESSION['username'];
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $repeatPassword = $_POST['repeatPassword'];

    // Lấy mật khẩu đã băm của người dùng từ cơ sở dữ liệu
    $sql = "SELECT password FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashedPassword = $row['password'];

        // Kiểm tra mật khẩu hiện tại đã băm có khớp với mật khẩu trong cơ sở dữ liệu hay không
        if (password_verify($currentPassword, $hashedPassword)) {
            // Kiểm tra xem mật khẩu mới và mật khẩu lặp lại có khớp nhau không
            if ($newPassword === $repeatPassword) {
                // Băm mật khẩu mới trước khi cập nhật vào cơ sở dữ liệu
                $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $updateSql = "UPDATE users SET password='$hashedNewPassword' WHERE username='$username'";
                if ($conn->query($updateSql) === TRUE) {
                    echo "<script>alert('Thông tin đã được cập nhật thành công')</script>";
                    echo "<script>window.history.go(-1);</script>";
                } else {
                    echo "Lỗi: " . $conn->error;
                }
            } else {
                echo "<script>alert('NewPassword and ConfirmPassword do not match')</script>";
                echo "<script>window.history.go(-1);</script>";
            }
        } else {
            echo "<script>alert('Sai mật khẩu hiện tại')</script>";
            echo "<script>window.history.go(-1);</script>";
        }
    } else {
        echo "<script>alert('Người dùng không tồn tại')</script>";
        echo "<script>window.history.go(-1);</script>";
    }
}

?>

<body>
    <div class="container">
        <!-- Your existing HTML content here -->

        <style>
        body {
            background: lightgrey
        }

        .sttngs p {
            color: #000;
        }

        .sttngs {
            max-width: 1064px;
            margin: 0px auto;
            background: #fff;
            padding: 15px;
            height: auto;
            font-family: "HelveticaNeue", "Helvetica Neue", Helvetica, Arial, sans-serif;
            border-radius: 3px;
            padding-top: 30px;
            -webkit-box-shadow: 0 0 50px 0 rgba(0, 0, 0, 0.2);
        }

        .sttngs h2 {
            letter-spacing: 2px;
            margin: 20px;
            color: #000;
        }


        .sttngs .tabordion {
            color: #FFF;
            display: block;
            mardgin: auto;
            position: relative;
            height: 670px;
            width: 100%;
            backgrdound: red;
        }

        .sttngs.tabordion input[name="sections"] {
            display: none;
        }

        .sttngs.tabordion section {
            display: block;
        }

        .tabordion section label {
            border-right: 1px solid lightgrey;
            display: block;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            padding: 14px 20px;
            color: #999;
            letter-spacing: 1px;
            position: relative;
            width: 130px;
            z-index: 100;
        }


        .trr {
            cursor: pointer;
        }


        .ver {
            color: rgb(205, 50, 184);
        }

        .tabordion section article {
            display: none;
            backgdround: red;
            padding-left: 200px;
            max-width: 100%;
            position: absolute;
            top: -50px;
            opascity: .7;
        }


        /*
.tabordion section article:after {
  
  bottom: 0;
  content: "";
  display: block;
  left:-229px;
  position: relative;
  top: 0;
  width: 220px;
  z-index:1;
}
*/
        .tabordion input[name="sections"]:checked+label {
            border-right: 3px solid rgb(199, 49, 166);
            color: rgb(205, 50, 192);
            font-weifght: bold;
        }

        .tabordion input[name="sections"]:checked~article {
            display: block;
        }




        .social {
            display: inline-block;
            width: 32.7%;
        }

        .r1,
        .r2 {
            mfargin-left: 4%;
        }





        :root {
            back3ground-color: #FCFEFD;
            font-family: helvetica, arial, sans-serif;
            font-size: 15px;
        }



        input,
        select,
        textarea {
            display: block;
            box-sizing: border-box;
            width: 100%;
            outline: none;
            border: none;
            border-radius: 2px;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }

        .frm .label {
            display: block;
            width: 100%;
            margin-bottom: 0.25em;
            font-size: 10px;
            text-align: left;
            font-weight: 900;
            padding: 0;
            color: #111;
            letter-spacing: 1px;
            border: none;
        }

        .input,
        .select,
        .textarea {
            padding: 10px;
            border: 1px solid lightgray;
            background-color: white;
            color: #aaa;
            letter-spacing: .7px;
        }

        .input:focus,
        .textarea:focus {
            border-color: gray;
        }


        .textarea {
            min-height: 100px;
            resize: vertical;
        }



        .tr {

            padding-top: 50px;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;

            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            max-wridth: 600px;
        }

        .tr .label {

            margin-bottom: 0.25em;
            width: 100%;
            font-size: 10px;
            text-align: left;
            font-yweight: 900;
            padding: 0;
            color: #111;
            letter-sfpacing: 2px;
            border: none;
        }



        .p {
            padding: 30px;
        }




        .input,
        .checkbox-label:before,
        .radio-label:before,
        .checkbox-label:after,
        .radio-label:after,
        .select,
        .textarea,
        .checkbox-label,
        .radio-label {
            margin-bottom: 1em;
        }

        .r {
            height: 250px;
            width: 250px;
            background: red;
            border-radius: 50%;
            float: left;
            margin-right: 30px;
        }






        .icon {
            width: 75px;
            height: 75px;

            margin: 0;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);


        }


        .camera4 {
            display: block;
            width: 70%;
            height: 50%;
            position: absolute;
            top: 30%;
            left: 15%;
            background-color: #000;
            border-radius: 5px;
        }

        .camera4:after {
            content: "";
            display: block;
            width: 15px;
            height: 15px;
            border: 7px solid #fff;
            position: absolute;
            top: 15%;
            left: 25%;
            background-color: #000;
            border-radius: 15px;
        }

        .camera4:before {
            content: "";
            display: block;
            width: 50%;
            height: 10px;
            position: absolute;
            top: -16%;
            left: 25%;
            background-color: #000;
            border-radius: 10px;
        }



        #profile-upload {
            background-image: url('');
            background-size: cover;
            background-position: center;
            height: 230px;
            width: 230px;
            border: 1px solid #bbb;
            position: relative;
            top: 20px;
            border-radius: 50%;
            overflow: hidden;


            float: left;
            margin-right: 30px;
            margin-bottom: 0px;

        }

        #profile-upload:hover input.upload {
            display: block;
        }

        #profile-upload:hover {
            border: 1px solid gray
        }

        #profile-upload:hover .hvr-profile-img {
            opacity: 1;
        }

        .hvr-profile-img {
            position: relative;
            display: inline-block;
            overflow: hidden;
            opacity: .3;
        }

        .hvr-profile-img input[type=file] {
            position: absolute;
            font-size: 100px;
            opacity: 0;
            right: 0;
            top: 0;
            cursor: pointer;
        }

        .hvr-profile-img img {
            display: block;
            width: 100%;
            height: auto;
            transition: opacity 0.3s ease;
        }

        .hvr-profile-img .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0);
            transition: background-color 0.3s ease;
        }

        .hvr-profile-img:hover .overlay {
            background-color: transparent;
        }

        .icon {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        #profile-upload input.upload {
            z-index: 1;
            left: 0;
            margin: 0;
            bottom: 0;
            top: 0;
            padding: 0;
            opacity: 0;
            outline: none;
            cursor: pointer;
            position: absolute;

            width: 100%;
            display: none;
        }

        #count {
            font-size: 12px;
            display: inline-block;
            text-align: center;
            color: grey;
            text-transform: none;
            font-weight: 600;
            letter-spacing: 0;
        }


        .e {
            max-width: 880px;
        }




        .select {
            position: relative;
            z-index: 1;
            padding-right: 40px;
        }

        .select::-ms-expand {
            display: none;
        }

        .select-wrap {
            position: relative;
        }

        .select-wrap:after {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            z-index: 2;
            padding: 0 15px;
            width: 10px;
            height: 100%;
            background-image: url("data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20version%3D%221.1%22%20x%3D%220%22%20y%3D%220%22%20width%3D%2213%22%20height%3D%2211.3%22%20viewBox%3D%220%200%2013%2011.3%22%20enable-background%3D%22new%200%200%2013%2011.3%22%20xml%3Aspace%3D%22preserve%22%3E%3Cpolygon%20fill%3D%22%23424242%22%20points%3D%226.5%2011.3%203.3%205.6%200%200%206.5%200%2013%200%209.8%205.6%20%22%2F%3E%3C%2Fsvg%3E");
            background-position: center;
            background-size: 10px;
            background-repeat: no-repeat;
            content: "";
            pointer-events: none;
        }

        .frm {
            padding: 3%;
        }


        .tr span {
            display: inline-block;
            color: grey;
            font-size: 11px;
            letter-spacing: 0;
            font-weight: 700;
            text-transform: none;
        }

        #texte {
            display: inline-block;
            color: grey;
            text-transfgorm: none;
            letter-spacing: 0;
        }

        .sttngs button {
            text-transform: uppercase;
            font-weight: 800;
            letter-spacing: 1px;
            outline: 0;
            background: rgb(252, 59, 123);
            width: 200px;
            border: 0;
            padding: 15px;
            color: #FFFFFF;
            font-size: 14px;
            -webkit-transition: all 0.3 ease;
            transition: all 0.3 ease;
            cursor: pointer;
            border-radius: 3px;
            margin-top: 30px;
            position: relative;
            left: 50%;
            transform: translate(-50%, 0px);
        }

        .sttngs button:hover,
        .sttngs button:active,
        .sttngs button:focus {
            background: #fc00fc;
        }



        @media (max-width: 600px) {
            #profile-upload {
                float: none;
                margin: auto;


            }

            .sttngs .tabordion {
                height: 1020px;
            }


            .sttngs button {
                margin-top: -40px;
            }

            .social {
                display: block;
                width: 100%;
            }

            .sttngs {
                padding: 0;
                padding-top: 19px;
            }

            .sttngs h2 {
                text-align: center;
            }

            .tabordion section article {
                border-top: 1px solid #eee;
            }


        }


        @media (min-width: 768px) {
            .tabordion {
                height: 600px;
            }
        }



        @media (max-width: 768px) {
            .social {
                margin: 0;
            }


            button {
                margin-top: -40px;
            }

            .tabordion section label {
                float: left;
                disptlay: inline-block;
                width: 17%;
                margin: auto;
                padding: 12px;
                font-size: 9px;
                border-right: none;
                text-align: center;

            }

            .wwq {
                padding: 10px;
            }

            .b {
                height: 170px;
                width: 170px;
            }

            .tabordion input[name="sections"]:checked+label {
                border-bottom: 3px solid rgb(187, 56, 137);
                border-right: none;
            }




            .tabordion section article {
                left: 0;
                top: 38px;
                border-top: 1px solid #eee;
                padding: 0;
            }
        }

        @media only screen and (max-width: 600px) {
            .tabordion section label {
                float: none;
                width: auto;
                display: inline-block;
                border: none;
                text-align: center;
            }

            .tabordion section article {
                position: static;
                display: none;
                padding-left: 0;
                border: none;
                padding-top: 20px;
            }

            .tabordion input[name="sections"]:checked+label {
                border-bottom: none;
                border-right: none;
            }

            .sttngs .tabordion {
                height: auto;
            }

            .sttngs button {
                margin-top: 20px;
            }

            .sttngs .tr {
                display: block;
            }

            .select-wrap.e {
                max-width: 100%;
            }

            #profile-upload {
                float: none;
                margin: auto;
            }
        }
        </style>
        <div class="sttngs">
            <h2>SETTINGS</h2>
            <div class="tabordion">
                <section id="section1" style="position: static;">
                    <input class="t" type="radio" name="sections" id="option1" checked>
                    <label for="option1" class="trr"> Account</label>
                    <article>
                        <div class="frm">
                            <form action="#" method="POST" enctype="multipart/form-data">
                                <div id='profile-upload'>
                                    <div class="hvr-profile-img">
                                        <?php
                                        // Kiểm tra xem người dùng có hình ảnh (avt) hay không trong cơ sở dữ liệu
                                        if (!empty($row['avatar'])) {
                                            // Nếu có, hiển thị hình ảnh của họ
                                            echo '<img src="' . $row['avatar'] . '" alt="Avatar">';
                                        } else {
                                            // Nếu không có, hiển thị một hình ảnh mặc định hoặc thông báo rỗng
                                            echo '<img src="./uploads/user-1.jpg" alt="Default Avatar">';
                                        }
                                        ?>
                                        <input type="file" name="avt" id="getval" class="upload" id="avt">
                                        <div class="icon">

                                        </div>
                                    </div>

                                </div>
                                <div class="tr">
                                    <label class="label" for="input">User name</label>
                                    <input class="input" type="text" id="input" value="<?php echo $row['username'] ?>"
                                        readonly>
                                    <label class="label" for="input">Name
                                        <!-- <div id="texte"></div>
                                </label> -->
                                        <input class="input" type="text" id="name" name="name"
                                            value="<?php echo $row['name'] ?>">
                                        <label class="label" for="input">HOMETOWN</label>
                                        <input class="input" type="text" id="address" name="address"
                                            value="<?php echo $row['address'] ?>">
                                </div>
                                <br>
                                <label class="label" for="select">Faculty</label>
                                <?php
                                $sqlFaculties = "SELECT u.facultyId, f.facultyName 
                FROM users u 
                LEFT JOIN faculties f ON u.facultyId = f.facultyId
                WHERE u.username = '$username'";
                                $resultFaculties = $conn->query($sqlFaculties);
                                if ($resultFaculties->num_rows > 0) {
                                    $rowFaculties = $resultFaculties->fetch_assoc();
                                }
                                ?>
                                <input class="input" type="text" id="faculty" name="faculty"
                                    value="<?php echo $rowFaculties['facultyName'] ?>" readonly>
                                <label class="label" for="textarea">SHORT BIOGRAPHY <p id="count"></p></label>
                                <textarea class="textarea e" rows="7" cols="25" id="aboutYou" name="aboutYou"
                                    maxlength="500"><?php echo $row['aboutYou'] ?></textarea>
                                <!-- <div class="social">
                                <label class="label" for="input">FACEBOOK URL</label>
                                <input class="input e" type="text" id="input">
                            </div>
                            <div class="social r1">
                                <label class="label" for="input">TWITTER HANDLE</label>
                                <input class="input e" type="text" id="input">
                            </div>
                            <div class="social r2">
                                <label class="label" for="input">Instagram Username</label>
                                <input class="input e" type="text" id="input">
                            </div> -->
                                <button id="UpdateProfile" name="UpdateProfile">Update profile</button>
                            </form>
                        </div>
                    </article>
                </section>

                <section id="section3" style="position: static;">
                    <input class="t" type="radio" name="sections" id="option3">
                    <label for="option3" class="trr">Password</label>
                    <article>
                        <form action="#" method="POST" enctype="multipart/form-data">
                            <div class="tr wwq">
                                <label class="label" for="input">current Password</label>
                                <input class="input e" type="password" id="currentPassword" name="currentPassword">

                                <label class="label" for="input">new password</label>
                                <input class="input e" type="password" id="newPassword" name="newPassword">

                                <label class="label" for="input">confirm password</label>
                                <input class="input e" type="password" id="repeatPassword" name="repeatPassword">

                            </div>
                            <button id="changePassword" name="changePassword">Change Password</button>
                        </form>
                    </article>
                </section>
                <section id="section4" style="position: static;"><a href="#" onclick="confirmLogout()"
                        style="text-decoration: none;">
                        <label for="option4" class="trr">Log out</label></a>
                </section>
            </div>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script>
        function confirmLogout() {
            var confirmLogout = confirm("Are you sure you want to log out?");
            if (confirmLogout) {
                window.location.href = '?page=logout'; // Chuyển hướng đến trang logout nếu người dùng đồng ý
            }
        }
        $("document").ready(function() {
            var textmax = 500;
            $("#count").text(textmax + ' character left');
            $("#bio").keyup(function() {
                var userlenght = $("#bio").val().length;
                var remain = textmax - userlenght;
                $("#count").text(remain + ' characters left');
            });
        });
        document.getElementById('getval').addEventListener('change', readURL, true);

        function readURL() {
            var file = document.getElementById("getval").files[0];
            var reader = new FileReader();
            reader.onloadend = function() {
                document.getElementById('profile-upload').style.backgroundImage = "url(" + reader.result + ")";
            }
            if (file) {
                reader.readAsDataURL(file);
            } else {}
        }
        $(function() {
            var $text = $('#texte');
            var $input = $('.texte');
            $input.on('keydown', function() {
                setTimeout(function() {
                    $text.html($input.val());
                }, 0);
            });
        })

        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape") {
                history.back();
            }
        });
        </script>
    </div>
</body>

</html>