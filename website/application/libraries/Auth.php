<?php
/**
 * Created by PhpStorm.
 * User: 713uk13m <dev@nguyenanhung.com>
 * Date: 9/28/18
 * Time: 14:07
 */
defined('BASEPATH') OR exit('No direct script access allowed');

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;
use nguyenanhung\ThuDoMultimediaVasServices\Roles;

/**
 * Interface MyAuthInterfaces
 */
interface MyAuthInterfaces
{
    /**
     * Function getVersion
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 9/28/18 15:56
     *
     * @return mixed
     */
    public function getVersion();

    /**
     * Function checkPostRoles
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 9/28/18 15:56
     *
     * @param string $categorySlug
     *
     * @return mixed
     */
    public function checkPostRoles($categorySlug = '');

    /**
     * Function checkPostHotRoles - Kiểm tra quyền đọc tin bài HOT
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2018-12-11 14:51
     *
     * @return bool
     */
    public function checkPostHotRoles();

    /**
     * Function updateViewPostAndCheckRoles
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2018-12-20 15:51
     *
     * @param string $categoryId
     *
     * @return mixed
     */
    public function updateViewPostAndCheckRoles($categoryId = '');

    /**
     * Function checkRegisterRoles
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2018-12-18 11:22
     *
     * @param string $packageId
     * @param string $telco
     * @param bool   $isPackage
     *
     * @return bool
     */
    public function checkRegisterRoles($packageId = '', $telco = '', $isPackage = FALSE);
}

/**
 * Class Auth
 *
 * @property object CI
 */
class Auth implements MyAuthInterfaces
{
    const VERSION                                  = '0.1.0';
    const DEBUG                                    = TRUE; // true nếu lưu log, false nếu không lưu log
    const DEBUG_PACKAGE                            = TRUE;
    const SESSION_ID_CURRENT_USER_MSISDN           = 'CURRENT_USER_MSISDN';
    const SESSION_ID_CURRENT_USER_PACKAGE_ID       = 'CURRENT_USER_PACKAGE_ID';
    const SESSION_ID_CURRENT_USER_GET_INFO         = 'CURRENT_USER_GET_INFO';
    const SESSION_ID_CURRENT_USER_IS_MULTI_PACKAGE = 'CURRENT_USER_IS_MULTI_PACKAGE';
    const SESSION_ID_CURRENT_USER_LIST_PACKAGE_ID  = 'CURRENT_USER_LIST_PACKAGE_ID';
    protected $CI;
    protected $mono;
    protected $logger_path;
    protected $logger_file;
    protected $vendor_debug;
    private   $_roles;

    /**
     * Auth constructor.
     */
    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->library('msisdn');
        $this->CI->load->library('session');
        $this->vendor_debug = [
            'debugStatus'         => self::DEBUG_PACKAGE,
            'debugLoggerPath'     => APPPATH . 'logs-data/vendor/',
            'debugLoggerFilename' => 'Log-' . date('Y-m-d') . '.log'
        ];
        $this->_roles       = new Roles($this->vendor_debug);
        $this->CI->config->load('config_vas_telcos');
        // Monolog
        $this->logger_path = APPPATH . 'logs-data/Libraries-Authencation/';
        $this->logger_file = 'Log-' . date('Y-m-d') . '.log';
        $this->mono        = [
            'dateFormat'         => "Y-m-d H:i:s u",
            'outputFormat'       => "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n",
            'monoBubble'         => TRUE,
            'monoFilePermission' => 0777
        ];
        self::__save_log(__FUNCTION__, '|~~~~~~~~~~~~~~~~~~~~~~~~~> Start Check Roles <~~~~~~~~~~~~~~~~~~~~~~~~~|');
    }

    /**
     * Auth destructor.
     */
    public function __destruct()
    {
        self::__save_log(__FUNCTION__, '|~~~~~~~~~~~~~~~~~~~~~~~~~> End Check Roles <~~~~~~~~~~~~~~~~~~~~~~~~~|');
    }

    /**
     * Function getVersion
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 9/28/18 15:56
     *
     * @return string
     */
    public function getVersion()
    {
        return self::VERSION;
    }

    /**
     * Function checkPostRoles
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 11/16/18 13:57
     *
     * @param string $categorySlug
     *
     * @return bool|mixed
     * @throws \Exception
     */
    public function checkPostRoles($categorySlug = '')
    {
        $categoryConfig = config_item('category_config');
        if ($categoryConfig['check_roles'] !== TRUE) {
            self::__save_log(__FUNCTION__, 'Không bật chế độ phân quyền đọc -> ai cũng có thể đọc');

            return TRUE;
        }
        $currentSessionUsername           = $this->CI->msisdn->getSessionData(self::SESSION_ID_CURRENT_USER_MSISDN);
        $currentSessionPackage            = $this->CI->msisdn->getSessionData(self::SESSION_ID_CURRENT_USER_PACKAGE_ID);
        $currentSessionListPackage        = $this->CI->msisdn->getSessionData(self::SESSION_ID_CURRENT_USER_LIST_PACKAGE_ID);
        $currentSessionUserInfo           = $this->CI->msisdn->getSessionData(self::SESSION_ID_CURRENT_USER_GET_INFO);
        $currentSessionUserIsMultiPackage = $this->CI->msisdn->getSessionData(self::SESSION_ID_CURRENT_USER_IS_MULTI_PACKAGE);
        self::__save_log(__FUNCTION__, 'SESSION_ID_CURRENT_USER_MSISDN: ' . json_encode($currentSessionUsername));
        self::__save_log(__FUNCTION__, 'SESSION_ID_CURRENT_USER_PACKAGE_ID: ' . json_encode($currentSessionPackage));
        self::__save_log(__FUNCTION__, 'SESSION_ID_CURRENT_USER_LIST_PACKAGE_ID: ' . json_encode($currentSessionListPackage));
        self::__save_log(__FUNCTION__, 'SESSION_ID_CURRENT_USER_GET_INFO: ' . json_encode($currentSessionUserInfo));
        self::__save_log(__FUNCTION__, 'SESSION_ID_CURRENT_USER_IS_MULTI_PACKAGE: ' . json_encode($currentSessionUserIsMultiPackage));
        // Lấy thông tin gói cước
        if (isset($currentSessionUserIsMultiPackage) && $currentSessionUserIsMultiPackage === TRUE) {
            self::__save_log(__FUNCTION__, 'Trường hợp User được phép đăng ký sử dụng nhiều gói cước');
            // User đăng ký nhiều gói cước
            if (empty($currentSessionListPackage)) {
                if (!empty($currentSessionPackage)) {
                    self::__save_log(__FUNCTION__, 'Tìm thấy PackageID');
                    $userPackage = $currentSessionPackage;
                } else {
                    self::__save_log(__FUNCTION__, 'Tìm PackageID trong User Info');
                    if (isset($currentSessionUserInfo[0]->packageId)) {
                        $userPackage = $currentSessionUserInfo[0]->packageId;
                    } elseif (isset($currentSessionUserInfo[0]['packageId'])) {
                        $userPackage = $currentSessionUserInfo[0]['packageId'];
                    } elseif (isset($currentSessionUserInfo->packageId)) {
                        $userPackage = $currentSessionUserInfo->packageId;
                    } else {
                        $userPackage = NULL;
                    }
                }
            } else {
                self::__save_log(__FUNCTION__, 'Lấy list User Package');
                $userPackage = explode(',', $currentSessionListPackage);
            }
        } else {
            self::__save_log(__FUNCTION__, 'Trường hợp User chỉ được phép đăng ký 1 gói cước');
            // User đăng ký 1 gói cước
            if (!empty($currentSessionPackage)) {
                self::__save_log(__FUNCTION__, 'Tìm thấy PackageID');
                $userPackage = $currentSessionPackage;
            } else {
                self::__save_log(__FUNCTION__, 'Tìm PackageID trong User Info');
                if (isset($currentSessionUserInfo[0]->packageId)) {
                    $userPackage = $currentSessionUserInfo[0]->packageId;
                } elseif (isset($currentSessionUserInfo[0]['packageId'])) {
                    $userPackage = $currentSessionUserInfo[0]['packageId'];
                } elseif (isset($currentSessionUserInfo->packageId)) {
                    $userPackage = $currentSessionUserInfo->packageId;
                } else {
                    $userPackage = NULL;
                }
            }
        }
        if (!isset($userPackage) || empty($userPackage)) {
            self::__save_log(__FUNCTION__, 'Không tìm thấy giá trị packageID thích hợp');

            return FALSE;
        }
        $setCategoryConfig = $categoryConfig['list_category'];
        self::__save_log(__FUNCTION__, 'input Category Slug: ' . json_encode($categorySlug));
        self::__save_log(__FUNCTION__, 'Data Category Config: ' . json_encode($setCategoryConfig));
        self::__save_log(__FUNCTION__, 'Current Session Package: ' . json_encode($userPackage));
        // Open Object
        $this->_roles->setCategoryConfig($setCategoryConfig);
        $this->_roles->setListPackage($userPackage);
        $this->_roles->setCategoryId($categorySlug);
        // Check Roles
        $checkContentRoles = $this->_roles->checkContentRoles();
        self::__save_log(__FUNCTION__, 'Result Check Roles: ' . $checkContentRoles);
        if ($checkContentRoles === TRUE) {
            self::__save_log(__FUNCTION__, 'Kết quả => Check phân quyền thành công -> người dùng có quyền đọc toàn bộ nội dung bài viết');

            return TRUE;
        }
        self::__save_log(__FUNCTION__, 'Kết quả => Không check được quyền');

        return FALSE;
    }

    /**
     * Function checkPostHotRoles - Kiểm tra quyền đọc tin bài HOT
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2018-12-11 14:51
     *
     * @return bool
     */
    public function checkPostHotRoles()
    {
        $categoryConfig = config_item('category_config');
        if (isset($categoryConfig['check_roles_hot'])) {
            if ($categoryConfig['check_roles_hot'] !== TRUE) {
                self::__save_log(__FUNCTION__, 'Không bật chế độ phân quyền đọc -> ai cũng có thể đọc');

                return TRUE;
            }
            $currentSessionUsername           = $this->CI->msisdn->getSessionData(self::SESSION_ID_CURRENT_USER_MSISDN);
            $currentSessionPackage            = $this->CI->msisdn->getSessionData(self::SESSION_ID_CURRENT_USER_PACKAGE_ID);
            $currentSessionListPackage        = $this->CI->msisdn->getSessionData(self::SESSION_ID_CURRENT_USER_LIST_PACKAGE_ID);
            $currentSessionUserInfo           = $this->CI->msisdn->getSessionData(self::SESSION_ID_CURRENT_USER_GET_INFO);
            $currentSessionUserIsMultiPackage = $this->CI->msisdn->getSessionData(self::SESSION_ID_CURRENT_USER_IS_MULTI_PACKAGE);
            self::__save_log(__FUNCTION__, 'SESSION_ID_CURRENT_USER_MSISDN: ' . json_encode($currentSessionUsername));
            self::__save_log(__FUNCTION__, 'SESSION_ID_CURRENT_USER_PACKAGE_ID: ' . json_encode($currentSessionPackage));
            self::__save_log(__FUNCTION__, 'SESSION_ID_CURRENT_USER_LIST_PACKAGE_ID: ' . json_encode($currentSessionListPackage));
            self::__save_log(__FUNCTION__, 'SESSION_ID_CURRENT_USER_GET_INFO: ' . json_encode($currentSessionUserInfo));
            self::__save_log(__FUNCTION__, 'SESSION_ID_CURRENT_USER_IS_MULTI_PACKAGE: ' . json_encode($currentSessionUserIsMultiPackage));
            // Lấy thông tin gói cước
            if (isset($currentSessionUserIsMultiPackage) && $currentSessionUserIsMultiPackage === TRUE) {
                self::__save_log(__FUNCTION__, 'Trường hợp User được phép đăng ký sử dụng nhiều gói cước');
                // User đăng ký nhiều gói cước
                if (empty($currentSessionListPackage)) {
                    if (!empty($currentSessionPackage)) {
                        self::__save_log(__FUNCTION__, 'Tìm thấy PackageID');
                        $userPackage = $currentSessionPackage;
                    } else {
                        self::__save_log(__FUNCTION__, 'Tìm PackageID trong User Info');
                        if (isset($currentSessionUserInfo[0]->packageId)) {
                            $userPackage = $currentSessionUserInfo[0]->packageId;
                        } elseif (isset($currentSessionUserInfo[0]['packageId'])) {
                            $userPackage = $currentSessionUserInfo[0]['packageId'];
                        } elseif (isset($currentSessionUserInfo->packageId)) {
                            $userPackage = $currentSessionUserInfo->packageId;
                        } else {
                            $userPackage = NULL;
                        }
                    }
                } else {
                    self::__save_log(__FUNCTION__, 'Lấy list User Package 1');
                    $userPackage = explode(',', $currentSessionListPackage);
                    self::__save_log(__FUNCTION__, 'Lấy list User Package 2 -> ' . json_encode($userPackage));
                }
            } else {
                self::__save_log(__FUNCTION__, 'Trường hợp User chỉ được phép đăng ký 1 gói cước');
                // User đăng ký 1 gói cước
                if (!empty($currentSessionPackage)) {
                    self::__save_log(__FUNCTION__, 'Tìm thấy PackageID');
                    $userPackage = $currentSessionPackage;
                } else {
                    self::__save_log(__FUNCTION__, 'Tìm PackageID trong User Info');
                    if (isset($currentSessionUserInfo[0]->packageId)) {
                        $userPackage = $currentSessionUserInfo[0]->packageId;
                    } elseif (isset($currentSessionUserInfo[0]['packageId'])) {
                        $userPackage = $currentSessionUserInfo[0]['packageId'];
                    } elseif (isset($currentSessionUserInfo->packageId)) {
                        $userPackage = $currentSessionUserInfo->packageId;
                    } else {
                        $userPackage = NULL;
                    }
                }
            }
            if (!isset($userPackage) || empty($userPackage)) {
                self::__save_log(__FUNCTION__, 'Không tìm thấy giá trị packageID thích hợp');

                return FALSE;
            }
            if (isset($categoryConfig['check_roles_hot_list_package'])) {
                $listPackageAllowed = $categoryConfig['check_roles_hot_list_package'];
                log_message('error', 'List Package Allowed =>  ' . json_encode($listPackageAllowed));
                log_message('error', 'User Package =>  ' . json_encode($userPackage));
                if (is_array($userPackage)) {
                    foreach ($userPackage as $packageId) {
                        if (in_array($packageId, $listPackageAllowed)) {
                            return TRUE;
                        }
                    }
                } else {
                    if (in_array($userPackage, $listPackageAllowed)) {
                        return TRUE;
                    }
                }
            }
        }

        return FALSE;
    }

    /**
     * Function updateViewPostAndCheckRoles
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2018-12-20 15:51
     *
     * @param string $categoryId
     *
     * @return mixed
     */
    public function updateViewPostAndCheckRoles($categoryId = '')
    {
        $result = $this->CI->msisdn->checkUserViewedCategoryAndStop($categoryId);

        return $result;
    }

    /**
     * Function checkRegisterRoles
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2018-12-18 16:21
     *
     * @param string $packageId
     * @param string $telco
     * @param bool   $isPackage
     *
     * @return bool
     */
    public function checkRegisterRoles($packageId = '', $telco = '', $isPackage = FALSE)
    {
        $packageSettings           = config_item('package_settings');
        $currentSessionListPackage = $this->CI->msisdn->getListUserPackageId();
        if (is_array($currentSessionListPackage)) {
            if (in_array($packageId, $currentSessionListPackage)) {
                return TRUE;
            }
            if ($isPackage !== TRUE) {
                foreach ($currentSessionListPackage as $params) {
                    $packageGroup = $packageSettings[$telco][$params]['category_group'];
                    if (in_array($packageId, $packageGroup)) {
                        return TRUE;
                    }
                }
            }
        }
        if ($isPackage !== TRUE) {
            $packageGroup = $packageSettings[$telco][$packageId]['category_group'];
            if (in_array($currentSessionListPackage, $packageGroup)) {
                return TRUE;
            }
        }
        if ($packageId == $currentSessionListPackage) {
            return TRUE;
        }

        return FALSE;
    }

    /**
     * Function __save_log
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 2018-12-18 16:14
     *
     * @param string $name
     * @param string $msg
     * @param array  $context
     */
    private function __save_log($name = 'msisdn', $msg = '', $context = [])
    {
        try {
            if (self::DEBUG === TRUE) {
                $formatter = new LineFormatter($this->mono['outputFormat'], $this->mono['dateFormat']);
                $stream    = new StreamHandler($this->logger_path . $this->logger_file, Logger::INFO, $this->mono['monoBubble'], $this->mono['monoFilePermission']);
                $stream->setFormatter($formatter);
                $logger = new Logger($name);
                $logger->pushHandler($stream);
                if (is_array($context)) {
                    $logger->info($msg, $context);
                } else {
                    $logger->info($msg . json_encode($context));
                }

            }

        }
        catch (Exception $e) {
            $message = 'Error Code: ' . $e->getCode() . ' - File: ' . $e->getFile() . ' - Line: ' . $e->getLine() . ' - Message: ' . $e->getMessage();
            log_message('error', $message);
        }
    }
}
