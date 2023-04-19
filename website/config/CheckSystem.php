<?php
// Hiển thị mã lỗi hay không
ini_set('display_errors', 0);

/**
 * Class CheckSystem
 *
 * @author    713uk13m <dev@nguyenanhung.com>
 * @copyright 713uk13m <dev@nguyenanhung.com>
 */
class CheckSystem
{
    /**
     * Function phpVersion
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2019-02-25 21:41
     *
     */
    public function phpVersion()
    {
        $minVersion = '5.6';
        $operator   = '>=';

        $message = 'Phiên bản PHP hiện tại là: ' . PHP_VERSION . ' - phiên bản khuyến nghị ' . $operator . ' ' . $minVersion;

        if (version_compare(PHP_VERSION, $minVersion, $operator)) {
            $status = 'HỢP LỆ';
        } else {
            $status = 'KO HỢP LỆ';
        }
        $result = $message . ' => ' . $status . PHP_EOL;

        echo $result;
    }

    /**
     * Function phpTelnet
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2019-02-25 22:05
     *
     * @param string $hostname
     * @param string $port
     */
    public function phpTelnet($hostname = '', $port = '')
    {
        $socket  = fsockopen($hostname, $port);
        $message = 'Kết nối đến server ' . $hostname . ':' . $port . '';
        if ($socket) {
            $status = 'THÀNH CÔNG';
        } else {
            $status = 'THẤT BẠI';
        }
        $result = $message . ' => ' . $status . PHP_EOL;
        echo $result;
    }

    /**
     * Function checkExtension
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2019-02-25 21:46
     *
     * @param string $extension
     */
    public function checkExtension($extension = '')
    {
        $message = 'Tiện ích yêu cầu -> ' . $extension;
        if (extension_loaded($extension)) {
            $status = 'ĐƯỢC CÀI ĐẶT';
        } else {
            $status = 'CHƯA ĐƯỢC CÀI ĐẶT';
        }
        $result = $message . ' => ' . $status . PHP_EOL;

        echo $result;
    }

    /**
     * Function checkWriteFile
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2019-02-25 21:52
     *
     * @param string $filename
     */
    public function checkWriteFile($filename = '')
    {
        $message = 'File ' . $filename;
        if (is_writable($filename)) {
            $status = 'ĐƯỢC CẤP QUYỀN GHI';
        } else {
            $status = 'ĐƯỢC CẤP QUYỀN GHI';
        }
        $result = $message . ' => ' . $status . PHP_EOL;
        echo $result;
    }

    /**
     * Function checkReadFile
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2019-02-25 21:53
     *
     * @param string $filename
     */
    public function checkReadFile($filename = '')
    {
        $message = 'File ' . $filename;
        if (is_readable($filename)) {
            $status = 'ĐƯỢC CẤP QUYỀN ĐỌC';
        } else {
            $status = 'ĐƯỢC CẤP QUYỀN ĐỌC';
        }
        $result = $message . ' => ' . $status . PHP_EOL;
        echo $result;
    }

    /**
     * Function checkExecutableFile
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2019-02-25 21:53
     *
     * @param string $filename
     */
    public function checkExecutableFile($filename = '')
    {
        $message = 'File ' . $filename . '';
        if (is_executable($filename)) {
            $status = 'ĐƯỢC CẤP QUYỀN THỰC THI';
        } else {
            $status = 'ĐƯỢC CẤP QUYỀN THỰC THI';
        }
        $result = $message . ' => ' . $status . PHP_EOL;
        echo $result;
    }

    /**
     * Function checkConnectDatabase
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2019-02-25 21:58
     *
     * @param string $host
     * @param string $port
     * @param string $db_name
     * @param string $username
     * @param string $password
     */
    public function checkConnectDatabase($host = '', $port = '', $db_name = '', $username = '', $password = '')
    {
        try {
            $dsn_string = "mysql:host=$host;port=$port;dbname=$db_name";
            $conn       = new PDO("mysql:host=$host;port=$port;dbname=$db_name", $username, $password);
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Connected successfully to Database : " . $dsn_string . " with username: " . $username . " and password: " . $password . PHP_EOL;
        }
        catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage() . PHP_EOL;
            echo $e->getTraceAsString() . PHP_EOL;
        }
    }
}
