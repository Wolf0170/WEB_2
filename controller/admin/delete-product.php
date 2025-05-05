<?php
    include "../../model/thuvien.php";
    $conn = ketnoidb();

    if (!$conn) {
        die("Kết nối cơ sở dữ liệu thất bại: " . mysqli_connect_error());
    }

    if (isset($_GET['id'])) {
        $id = (int) $_GET['id'];

        // Kiểm tra sản phẩm có tồn tại hay không
        $sql = "SELECT * FROM sanpham WHERE MaSP = $id";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);

            // Xóa ảnh sản phẩm
            if (file_exists("../../view/img/product/" . $row['HinhAnh'])) {
                unlink("../../view/img/product/" . $row['HinhAnh']);
            }

            // Xóa dữ liệu liên quan trong bảng chitiethoadon
            $sql1 = "DELETE FROM chitiethoadon WHERE MaSP = $id";
            if (!mysqli_query($conn, $sql1)) {
                die("Lỗi khi xóa dữ liệu liên quan: " . mysqli_error($conn));
            }

            // Xóa sản phẩm khỏi cơ sở dữ liệu
            $sql2 = "DELETE FROM sanpham WHERE MaSP = $id";
            if (mysqli_query($conn, $sql2)) {
                echo "Sản phẩm đã được xóa thành công!";
            } else {
                echo "Lỗi khi xóa sản phẩm: " . mysqli_error($conn);
            }
        } else {
            echo "Không tìm thấy sản phẩm với ID: $id";
        }
    } else {
        echo "ID sản phẩm không được cung cấp!";
    }

    mysqli_close($conn);
?>