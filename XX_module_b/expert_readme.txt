XX_module_b/
├── .htaccess               # Cấu hình để mọi request đổ về index.php (Router)
├── index.php               # Entry point - Bộ điều hướng (Router) chính của cả trang web
├── db.php                  # Chứa kết nối PDO dùng chung cho toàn bộ dự án
├── seed.php                # Script dùng để nạp dữ liệu từ CSV vào CSDL (đã viết ở bước trước)
├── expert_readme.txt       # File hướng dẫn chạy dự án (Bắt buộc theo đề bài) 
├── database_dump.sql       # File xuất dữ liệu từ CSDL sau khi hoàn thành 
├── ER_diagram.png          # Ảnh chụp sơ đồ thực thể liên kết 
│
├── api/                    # Chứa logic xử lý JSON API
│   └── products.php        # Xử lý GET /products.json và /products/[GTIN].json [cite: 661, 663]
│
├── views/                  # Chứa các file giao diện (HTML + PHP Logic)
│   ├── login.php           # Trang đăng nhập passphrase [cite: 587]
│   ├── admin_companies.php # Quản lý danh sách công ty [cite: 595]
│   ├── admin_products.php  # Quản lý danh sách sản phẩm (Admin) [cite: 629]
│   ├── product_form.php    # Form thêm mới/chỉnh sửa sản phẩm 
│   ├── public_product.php  # Trang chi tiết sản phẩm cho khách (Mobile-friendly) [cite: 770, 771]
│   └── bulk_verify.php     # Trang xác thực GTIN hàng loạt [cite: 746]
│
├── assets/                 # Các tài nguyên tĩnh
│   ├── css/                # File CSS (Dùng chung và Mobile-friendly)
│   ├── js/                 # File JS (Xử lý đa ngôn ngữ, Bulk Verify)
│   └── images/             # Chứa icon (green-tick.png) và placeholder mặc định [cite: 659]
│
├── uploads/                # Thư mục chứa ảnh sản phẩm do Admin tải lên 
└── data/                   # Lưu trữ các file CSV gốc được cung cấp (companies.csv, products.csv)


#LoadModule rewrite_module modules/mod_rewrite.so


#login - pass: admin

#run data seeder: php seed.php