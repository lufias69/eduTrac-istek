<?php
use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CurrencyTable extends AbstractMigration
{

    public function up()
    {
        // Migration for table currency_code
        if (!$this->hasTable('currency_code')) :
            // CREATE table string for table: "currency_code"
            // Migration for table currency_code
            $table = $this->table('currency_code', array('id' => false, 'primary_key' => 'id', 'encoding' => 'utf8mb4', 'collation' => 'utf8mb4_unicode_ci'));
            $table
                ->addColumn('id', 'integer', array('signed' => true, 'identity' => true, 'limit' => 11))
                ->addColumn('country_currency', 'string', array('limit' => 191))
                ->addColumn('currency_code', 'string', array('limit' => 3))
                ->addColumn('code_2000', 'string', array('limit' => 6))
                ->addColumn('arial_unicode_ms', 'string', array('limit' => 6))
                ->addColumn('unicode_decimal', 'string', array('limit' => 25))
                ->addColumn('unicode_hex', 'string', array('limit' => 25))
                ->create();

            $this->execute("INSERT INTO `currency_code` VALUES(1, 'Albania – Lek', 'ALL', 'Lek', 'Lek', '76, 101, 107', '4c, 65, 6b');");
            $this->execute("INSERT INTO `currency_code` VALUES(2, 'Afghanistan – Afghani', 'AFN', '؋', '؋', '1547', '60b');");
            $this->execute("INSERT INTO `currency_code` VALUES(3, 'Argentina – Peso', 'ARS', '$', '$', '36', '24');");
            $this->execute("INSERT INTO `currency_code` VALUES(4, 'Aruba – Guilder', 'AWG', 'ƒ', 'ƒ', '402', '192');");
            $this->execute("INSERT INTO `currency_code` VALUES(5, 'Australia – Dollar', 'AUD', '$', '$', '36', '24');");
            $this->execute("INSERT INTO `currency_code` VALUES(6, 'Azerbaijan – New Manat', 'AZN', 'ман', 'ман', '1084, 1072, 1085', '43c, 430, 43d');");
            $this->execute("INSERT INTO `currency_code` VALUES(7, 'Bahamas – Dollar', 'BSD', '$', '$', '36', '24');");
            $this->execute("INSERT INTO `currency_code` VALUES(8, 'Barbados – Dollar', 'BBD', '$', '$', '36', '24');");
            $this->execute("INSERT INTO `currency_code` VALUES(9, 'Belarus – Ruble', 'BYR', 'p.', 'p.', '112, 46', '70, 2e');");
            $this->execute("INSERT INTO `currency_code` VALUES(10, 'Belize – Dollar', 'BZD', 'BZ$', 'BZ$', '66, 90, 36', '42, 5a, 24');");
            $this->execute("INSERT INTO `currency_code` VALUES(11, 'Bermuda – Dollar', 'BMD', '$', '$', '36', '24');");
            $this->execute("INSERT INTO `currency_code` VALUES(12, 'Bolivia – Boliviano', 'BOB', '\$b', '\$b', '36, 98', '24, 62');");
            $this->execute("INSERT INTO `currency_code` VALUES(13, 'Bosnia and Herzegovina – Convertible Marka', 'BAM', 'KM', 'KM', '75, 77', '4b, 4d');");
            $this->execute("INSERT INTO `currency_code` VALUES(14, 'Botswana – Pula', 'BWP', 'P', 'P', '80', '50');");
            $this->execute("INSERT INTO `currency_code` VALUES(15, 'Bulgaria – Lev', 'BGN', 'лв', 'лв', '1083, 1074', '43b, 432');");
            $this->execute("INSERT INTO `currency_code` VALUES(16, 'Brazil – Real', 'BRL', 'R$', 'R$', '82, 36', '52, 24');");
            $this->execute("INSERT INTO `currency_code` VALUES(17, 'Brunei Darussalam – Dollar', 'BND', '$', '$', '36', '24');");
            $this->execute("INSERT INTO `currency_code` VALUES(18, 'Cambodia – Riel', 'KHR', '៛', '៛', '6107', '17db');");
            $this->execute("INSERT INTO `currency_code` VALUES(19, 'Canada – Dollar', 'CAD', '$', '$', '36', '24');");
            $this->execute("INSERT INTO `currency_code` VALUES(20, 'Cayman Islands – Dollar', 'KYD', '$', '$', '36', '24');");
            $this->execute("INSERT INTO `currency_code` VALUES(21, 'Chile – Peso', 'CLP', '$', '$', '36', '24');");
            $this->execute("INSERT INTO `currency_code` VALUES(22, 'China – Yuan Renminbi', 'CNY', '¥', '¥', '165', 'a5');");
            $this->execute("INSERT INTO `currency_code` VALUES(23, 'Colombia – Peso', 'COP', '$', '$', '36', '24');");
            $this->execute("INSERT INTO `currency_code` VALUES(24, 'Costa Rica – Colon', 'CRC', '₡', '₡', '8353', '20a1');");
            $this->execute("INSERT INTO `currency_code` VALUES(25, 'Croatia – Kuna', 'HRK', 'kn', 'kn', '107, 110', '6b, 6e');");
            $this->execute("INSERT INTO `currency_code` VALUES(26, 'Cuba – Peso', 'CUP', '₱', '₱', '8369', '20b1');");
            $this->execute("INSERT INTO `currency_code` VALUES(27, 'Czech Republic – Koruna', 'CZK', 'Kč', 'Kč', '75, 269', '4b, 10d');");
            $this->execute("INSERT INTO `currency_code` VALUES(28, 'Denmark – Krone', 'DKK', 'kr', 'kr', '107, 114', '6b, 72');");
            $this->execute("INSERT INTO `currency_code` VALUES(29, 'Dominican Republic – Peso', 'DOP', 'RD$', 'RD$', '82, 68, 36', '52, 44, 24');");
            $this->execute("INSERT INTO `currency_code` VALUES(30, 'East Caribbean – Dollar', 'XCD', '$', '$', '36', '24');");
            $this->execute("INSERT INTO `currency_code` VALUES(31, 'Egypt – Pound', 'EGP', '£', '£', '163', 'a3');");
            $this->execute("INSERT INTO `currency_code` VALUES(32, 'El Salvador – Colon', 'SVC', '$', '$', '36', '24');");
            $this->execute("INSERT INTO `currency_code` VALUES(33, 'Euro Member Countries – Euro', 'EUR', '€', '€', '8364', '20ac');");
            $this->execute("INSERT INTO `currency_code` VALUES(34, 'Falkland Islands (Malvinas) – Pound', 'FKP', '£', '£', '163', 'a3');");
            $this->execute("INSERT INTO `currency_code` VALUES(35, 'Fiji – Dollar', 'FJD', '$', '$', '36', '24');");
            $this->execute("INSERT INTO `currency_code` VALUES(36, 'Ghana – Cedi', 'GHS', '¢', '¢', '162', 'a2');");
            $this->execute("INSERT INTO `currency_code` VALUES(37, 'Gibraltar – Pound', 'GIP', '£', '£', '163', 'a3');");
            $this->execute("INSERT INTO `currency_code` VALUES(38, 'Guatemala – Quetzal', 'GTQ', 'Q', 'Q', '81', '51');");
            $this->execute("INSERT INTO `currency_code` VALUES(39, 'Guernsey – Pound', 'GGP', '£', '£', '163', 'a3');");
            $this->execute("INSERT INTO `currency_code` VALUES(40, 'Guyana – Dollar', 'GYD', '$', '$', '36', '24');");
            $this->execute("INSERT INTO `currency_code` VALUES(41, 'Honduras – Lempira', 'HNL', 'L', 'L', '76', '4c');");
            $this->execute("INSERT INTO `currency_code` VALUES(42, 'Hong Kong – Dollar', 'HKD', '$', '$', '36', '24');");
            $this->execute("INSERT INTO `currency_code` VALUES(43, 'Hungary – Forint', 'HUF', 'Ft', 'Ft', '70, 116', '46, 74');");
            $this->execute("INSERT INTO `currency_code` VALUES(44, 'Iceland – Krona', 'ISK', 'kr', 'kr', '107, 114', '6b, 72');");
            $this->execute("INSERT INTO `currency_code` VALUES(45, 'India – Rupee', 'INR', '', '', '', '');");
            $this->execute("INSERT INTO `currency_code` VALUES(46, 'Indonesia – Rupiah', 'IDR', 'Rp', 'Rp', '82, 112', '52, 70');");
            $this->execute("INSERT INTO `currency_code` VALUES(47, 'Iran – Rial', 'IRR', '﷼', '﷼', '65020', 'fdfc');");
            $this->execute("INSERT INTO `currency_code` VALUES(48, 'Isle of Man – Pound', 'IMP', '£', '£', '163', 'a3');");
            $this->execute("INSERT INTO `currency_code` VALUES(49, 'Israel – Shekel', 'ILS', '₪', '₪', '8362', '20aa');");
            $this->execute("INSERT INTO `currency_code` VALUES(50, 'Jamaica – Dollar', 'JMD', 'J$', 'J$', '74, 36', '4a, 24');");
            $this->execute("INSERT INTO `currency_code` VALUES(51, 'Japan – Yen', 'JPY', '¥', '¥', '165', 'a5');");
            $this->execute("INSERT INTO `currency_code` VALUES(52, 'Jersey – Pound', 'JEP', '£', '£', '163', 'a3');");
            $this->execute("INSERT INTO `currency_code` VALUES(53, 'Kazakhstan – Tenge', 'KZT', 'лв', 'лв', '1083, 1074', '43b, 432');");
            $this->execute("INSERT INTO `currency_code` VALUES(54, 'Korea (North) – Won', 'KPW', '₩', '₩', '8361', '20a9');");
            $this->execute("INSERT INTO `currency_code` VALUES(55, 'Korea (South) – Won', 'KRW', '₩', '₩', '8361', '20a9');");
            $this->execute("INSERT INTO `currency_code` VALUES(56, 'Kyrgyzstan – Som', 'KGS', 'лв', 'лв', '1083, 1074', '43b, 432');");
            $this->execute("INSERT INTO `currency_code` VALUES(57, 'Laos – Kip', 'LAK', '₭', '₭', '8365', '20ad');");
            $this->execute("INSERT INTO `currency_code` VALUES(58, 'Lebanon – Pound', 'LBP', '£', '£', '163', 'a3');");
            $this->execute("INSERT INTO `currency_code` VALUES(59, 'Liberia – Dollar', 'LRD', '$', '$', '36', '24');");
            $this->execute("INSERT INTO `currency_code` VALUES(60, 'Macedonia – Denar', 'MKD', 'ден', 'ден', '1076, 1077, 1085', '434, 435, 43d');");
            $this->execute("INSERT INTO `currency_code` VALUES(61, 'Malaysia – Ringgit', 'MYR', 'RM', 'RM', '82, 77', '52, 4d');");
            $this->execute("INSERT INTO `currency_code` VALUES(62, 'Mauritius – Rupee', 'MUR', '₨', '₨', '8360', '20a8');");
            $this->execute("INSERT INTO `currency_code` VALUES(63, 'Mexico – Peso', 'MXN', '$', '$', '36', '24');");
            $this->execute("INSERT INTO `currency_code` VALUES(64, 'Mongolia – Tughrik', 'MNT', '₮', '₮', '8366', '20ae');");
            $this->execute("INSERT INTO `currency_code` VALUES(65, 'Mozambique – Metical', 'MZN', 'MT', 'MT', '77, 84', '4d, 54');");
            $this->execute("INSERT INTO `currency_code` VALUES(66, 'Namibia – Dollar', 'NAD', '$', '$', '36', '24');");
            $this->execute("INSERT INTO `currency_code` VALUES(67, 'Nepal – Rupee', 'NPR', '₨', '₨', '8360', '20a8');");
            $this->execute("INSERT INTO `currency_code` VALUES(68, 'Netherlands Antilles – Guilder', 'ANG', 'ƒ', 'ƒ', '402', '192');");
            $this->execute("INSERT INTO `currency_code` VALUES(69, 'New Zealand – Dollar', 'NZD', '$', '$', '36', '24');");
            $this->execute("INSERT INTO `currency_code` VALUES(70, 'Nicaragua – Cordoba', 'NIO', 'C$', 'C$', '67, 36', '43, 24');");
            $this->execute("INSERT INTO `currency_code` VALUES(71, 'Nigeria – Naira', 'NGN', '₦', '₦', '8358', '20a6');");
            $this->execute("INSERT INTO `currency_code` VALUES(72, 'Korea (North) – Won', 'KPW', '₩', '₩', '8361', '20a9');");
            $this->execute("INSERT INTO `currency_code` VALUES(73, 'Norway – Krone', 'NOK', 'kr', 'kr', '107, 114', '6b, 72');");
            $this->execute("INSERT INTO `currency_code` VALUES(74, 'Oman – Rial', 'OMR', '﷼', '﷼', '65020', 'fdfc');");
            $this->execute("INSERT INTO `currency_code` VALUES(75, 'Pakistan – Rupee', 'PKR', '₨', '₨', '8360', '20a8');");
            $this->execute("INSERT INTO `currency_code` VALUES(76, 'Panama – Balboa', 'PAB', 'B/.', 'B/.', '66, 47, 46', '42, 2f, 2e');");
            $this->execute("INSERT INTO `currency_code` VALUES(77, 'Paraguay – Guarani', 'PYG', 'Gs', 'Gs', '71, 115', '47, 73');");
            $this->execute("INSERT INTO `currency_code` VALUES(78, 'Peru – Nuevo Sol', 'PEN', 'S/.', 'S/.', '83, 47, 46', '53, 2f, 2e');");
            $this->execute("INSERT INTO `currency_code` VALUES(79, 'Philippines – Peso', 'PHP', '₱', '₱', '8369', '20b1');");
            $this->execute("INSERT INTO `currency_code` VALUES(80, 'Poland – Zloty', 'PLN', 'zł', 'zł', '122, 322', '7a, 142');");
            $this->execute("INSERT INTO `currency_code` VALUES(81, 'Qatar – Riyal', 'QAR', '﷼', '﷼', '65020', 'fdfc');");
            $this->execute("INSERT INTO `currency_code` VALUES(82, 'Romania – New Leu', 'RON', 'lei', 'lei', '108, 101, 105', '6c, 65, 69');");
            $this->execute("INSERT INTO `currency_code` VALUES(83, 'Russia – Ruble', 'RUB', 'руб', 'руб', '1088, 1091, 1073', '440, 443, 431');");
            $this->execute("INSERT INTO `currency_code` VALUES(84, 'Saint Helena – Pound', 'SHP', '£', '£', '163', 'a3');");
            $this->execute("INSERT INTO `currency_code` VALUES(85, 'Saudi Arabia – Riyal', 'SAR', '﷼', '﷼', '65020', 'fdfc');");
            $this->execute("INSERT INTO `currency_code` VALUES(86, 'Serbia – Dinar', 'RSD', 'Дин.', 'Дин.', '1044, 1080, 1085, 46', '414, 438, 43d, 2e');");
            $this->execute("INSERT INTO `currency_code` VALUES(87, 'Seychelles – Rupee', 'SCR', '₨', '₨', '8360', '20a8');");
            $this->execute("INSERT INTO `currency_code` VALUES(88, 'Singapore – Dollar', 'SGD', '$', '$', '36', '24');");
            $this->execute("INSERT INTO `currency_code` VALUES(89, 'Solomon Islands – Dollar', 'SBD', '$', '$', '36', '24');");
            $this->execute("INSERT INTO `currency_code` VALUES(90, 'Somalia – Shilling', 'SOS', 'S', 'S', '83', '53');");
            $this->execute("INSERT INTO `currency_code` VALUES(91, 'South Africa – Rand', 'ZAR', 'R', 'R', '82', '52');");
            $this->execute("INSERT INTO `currency_code` VALUES(92, 'Korea (South) – Won', 'KRW', '₩', '₩', '8361', '20a9');");
            $this->execute("INSERT INTO `currency_code` VALUES(93, 'Sri Lanka – Rupee', 'LKR', '₨', '₨', '8360', '20a8');");
            $this->execute("INSERT INTO `currency_code` VALUES(94, 'Sweden – Krona', 'SEK', 'kr', 'kr', '107, 114', '6b, 72');");
            $this->execute("INSERT INTO `currency_code` VALUES(95, 'Switzerland – Franc', 'CHF', 'CHF', 'CHF', '67, 72, 70', '43, 48, 46');");
            $this->execute("INSERT INTO `currency_code` VALUES(96, 'Suriname – Dollar', 'SRD', '$', '$', '36', '24');");
            $this->execute("INSERT INTO `currency_code` VALUES(97, 'Syria – Pound', 'SYP', '£', '£', '163', 'a3');");
            $this->execute("INSERT INTO `currency_code` VALUES(98, 'Taiwan – New Dollar', 'TWD', 'NT$', 'NT$', '78, 84, 36', '4e, 54, 24');");
            $this->execute("INSERT INTO `currency_code` VALUES(99, 'Thailand – Baht', 'THB', '฿', '฿', '3647', 'e3f');");
            $this->execute("INSERT INTO `currency_code` VALUES(100, 'Trinidad and Tobago – Dollar', 'TTD', 'TT$', 'TT$', '84, 84, 36', '54, 54, 24');");
            $this->execute("INSERT INTO `currency_code` VALUES(101, 'Turkey – Lira', 'TRY', '', '', '', '');");
            $this->execute("INSERT INTO `currency_code` VALUES(102, 'Tuvalu – Dollar', 'TVD', '$', '$', '36', '24');");
            $this->execute("INSERT INTO `currency_code` VALUES(103, 'Ukraine – Hryvnia', 'UAH', '₴', '₴', '8372', '20b4');");
            $this->execute("INSERT INTO `currency_code` VALUES(104, 'United Kingdom – Pound', 'GBP', '£', '£', '163', 'a3');");
            $this->execute("INSERT INTO `currency_code` VALUES(105, 'United States – Dollar', 'USD', '$', '$', '36', '24');");
            $this->execute("INSERT INTO `currency_code` VALUES(106, 'Uruguay – Peso', 'UYU', '\$U', '\$U', '36, 85', '24, 55');");
            $this->execute("INSERT INTO `currency_code` VALUES(107, 'Uzbekistan – Som', 'UZS', 'лв', 'лв', '1083, 1074', '43b, 432');");
            $this->execute("INSERT INTO `currency_code` VALUES(108, 'Venezuela – Bolivar', 'VEF', 'Bs', 'Bs', '66, 115', '42, 73');");
            $this->execute("INSERT INTO `currency_code` VALUES(109, 'Viet Nam – Dong', 'VND', '₫', '₫', '8363', '20ab');");
            $this->execute("INSERT INTO `currency_code` VALUES(110, 'Yemen – Rial', 'YER', '﷼', '﷼', '65020', 'fdfc');");
            $this->execute("INSERT INTO `currency_code` VALUES(111, 'Zimbabwe – Dollar', 'ZWD', 'Z$', 'Z$', '90, 36', '5a, 24');");
        endif;
    }

    public function down()
    {
        if ($this->hasTable('currency_code')) :
            $this->dropTable('currency_code');
        endif;
    }
}
