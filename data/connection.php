<?php
$servername = "localhost";
$username = "root"; // Replace with your MySQL username
$password = "";     // Replace with your MySQL password
$dbname = "global-company";
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// //
// CREATE TABLE `employees` (
//     `id` int(11) NOT NULL,
//     `name` varchar(50) NOT NULL,
//     `image` varchar(255) NOT NULL,
//     `date_of_birth` date NOT NULL,
//     `phone` varchar(15) NOT NULL,
//     `address` text NOT NULL,
//     `criminal_record` varchar(255) NOT NULL,
//     `job` varchar(11) NOT NULL,
//     `daily_reward` decimal(10,2) NOT NULL,
//     `is_permanent` tinyint(1) DEFAULT 0,
//     `is_fixed_worker` tinyint(1) DEFAULT 0,
//     `site` varchar(50) NOT NULL,
//     `start_date` date NOT NULL,
//     `end_date` date NOT NULL
//   ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
  
//   --
//   -- Dumping data for table `employees`
//   --
  
//   INSERT INTO `employees` (`id`, `name`, `image`, `date_of_birth`, `phone`, `address`, `criminal_record`, `job`, `daily_reward`, `is_permanent`, `is_fixed_worker`, `site`, `start_date`, `end_date`) VALUES
//   (17, 'اسامة محمد مرسى حسن عبدالكريم ', '../images/678ac012dd99f3.65540829.png', '1997-02-06', '01111254780', 'عزيز المصري', '../Criminal_record/678ac012ddd654.39609584.jpeg', 'مهندس', 300.00, 1, 0, 'amryah', '2025-01-01', '2026-01-31'),
//   (18, 'اسامة حسن عبيد', '../images/678ae7ff9522e7.79145586.jpg', '1990-01-01', '01110426265', 'شارع بلال بجوار صيدلية دكتور عاطف عبداللطيف', '../Criminal_record/678ae7ff959441.47342161.jpg', 'مشرف سقالات', 200.00, 0, 1, 'amryah', '2025-01-01', '2026-10-01'),
//   (19, 'محمد حسن ', '../images/678ae88b582a32.32726613.png', '1996-01-01', '01151112654', 'عزيز المصري', '../Criminal_record/678ae88b58ca96.56797241.jpg', 'مدير موقع', 350.00, 0, 1, 'amryah', '2025-01-01', '2025-01-31'),
//   (20, 'حسين محمد حسن', '../images/678aec812523f0.44724829.jpg', '1995-01-01', '01110748386', 'محافظة البحيرة مركز ادكو شارع عزيز المصرى', '../Criminal_record/678aec81254db1.06819916.jpg', 'مدير موقع', 200.00, 0, 1, 'amryah', '2025-01-01', '2025-01-01'),
//   (21, 'احمد مسعد محمد خميس', '../images/678b055d8ce5d8.00764333.jpeg', '1995-01-01', '01110426265', 'شارع بلال بجوار صيدلية دكتور عاطف عبداللطيف', '../Criminal_record/678b055d8d15c7.88246832.jpg', 'مدير موقع', 350.00, 0, 1, 'amryah', '2025-01-01', '2025-01-31'),
//   (22, 'شيماء السيد حسين ', '../images/678bbacfc87f19.58907824.jpg', '1991-01-02', '01210547683', 'ادكو شارع محمد عبده', '../Criminal_record/678bbacfc8c013.49532767.jpg', 'مشرف سقالات', 350.00, 0, 1, 'site3', '2025-01-01', '2025-01-30');
  
//   -- --------------------------------------------------------
  
//   --
//   -- Table structure for table `masrofat`
//   --
  
//   CREATE TABLE `masrofat` (
//     `id` int(11) NOT NULL,
//     `bandname` varchar(255) NOT NULL,
//     `amount` decimal(10,2) NOT NULL,
//     `date` date NOT NULL,
//     `comment` text DEFAULT NULL
//   ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
  
//   --
//   -- Dumping data for table `masrofat`
//   --
  
//   INSERT INTO `masrofat` (`id`, `bandname`, `amount`, `date`, `comment`) VALUES
//   (6, 'تغيير زيت العربية الجامبو ', 17.00, '2025-01-16', 'تغيير زيت العربية الجامبو عند ابو احمد'),
//   (7, 'مصاريف كارته', 6895.00, '2025-01-19', 'مصاريف كارته بورسعيد للعربية الجامبو'),
//   (12, 'مصروفات متعددة', 21000.00, '2025-01-09', 'مصروفات متعددة للموقع '),
//   (13, 'مهمات ', 5000.00, '2025-01-19', 'لا يوجد ملاحظات');
  
//   --
//   -- Indexes for dumped tables
//   --
  
//   --
//   -- Indexes for table `employees`
//   --
//   ALTER TABLE `employees`
//     ADD PRIMARY KEY (`id`);
  
//   --
//   -- Indexes for table `masrofat`
//   --
//   ALTER TABLE `masrofat`
//     ADD PRIMARY KEY (`id`);
  
//   --
//   -- AUTO_INCREMENT for dumped tables
//   --
  
//   --
//   -- AUTO_INCREMENT for table `employees`
//   --
//   ALTER TABLE `employees`
//     MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
  
//   --
//   -- AUTO_INCREMENT for table `masrofat`
//   --
//   ALTER TABLE `masrofat`
//     MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
//   COMMIT;
  
