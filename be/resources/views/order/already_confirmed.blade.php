<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông Báo</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        h2 {
            font-size: 24px;
            color: #333;
            margin-bottom: 10px;
            text-align: center;
        }

        p {
            font-size: 16px;
            color: #666;
            line-height: 1.5;
            text-align: center;
        }

        .notification {
            background-color: #fff3f3;
            border: 2px solid #dc3545;
            border-radius: 8px;
            padding: 20px;
            max-width: 500px;
            margin: 20px auto;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            animation: fadeIn 0.5s ease-in;
            position: relative;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .notification h2 {
            color: #dc3545;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .notification p {
            color: #721c24;
            margin: 0;
        }

        .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            color: #dc3545;
            transition: color 0.2s ease;
        }

        .close-btn:hover {
            color: #a71d2a;
        }
    </style>
</head>
<body>
    <div class="notification">
        <h2>Thông báo</h2>
        <p>Đơn hàng này đã được xác nhận hoặc hủy trước đó. Bạn không thể thao tác thêm.</p>
        <button class="close-btn">×</button>
    </div>

    <script>
        document.querySelector('.close-btn').addEventListener('click', function() {
            document.querySelector('.notification').style.display = 'none';
        });
    </script>
</body>
</html>