<?php
/**
 * Project vn-telco-detect.
 * Created by PhpStorm.
 * User: 713uk13m <dev@nguyenanhung.com>
 * Date: 9/19/18
 * Time: 16:27
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Viettel_mps_data
 *
 * @author    713uk13m <dev@nguyenanhung.com>
 * @copyright 713uk13m <dev@nguyenanhung.com>
 */
class Viettel_mps_data
{
    // Khai báo Key Viettel Public do Viettel cung cấp
    public $viettelPublicKey = <<<EOD
-----BEGIN PUBLIC KEY-----
MIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEAyctRb9afL2u9LFMkqFXr
QZonxAMm3XkadN5PG5+SMrQgYu1GGPVj3paICpxL8FWoh9KbQ582vhX7WH8rEUgq
K5frtxwColOQ4xenckBdWUZKMHmb+g67Lnd8+gLkkz9wXwUfMxQ8QkxyTrHKeweu
9w2ZNv7E1MebIC1GRRGEdi3FKoRkxazmm+y3+WuSeCfOm48zgRkKNicQgVvHEKca
BJ1MEt5WbudPIPmXFHusmXec2CDswU3HWPSItp3yGLV4+EqjPp3UIxyva7CHYmZ+
EVl0yyrTunXymZgMv+aCpeZNsULKs3BUO87HfFamkVrjNRYciTtfo8pH1J185Bcl
1kbU+NpArw/fkDnsVyj2EAaENH06txfzpp7Z+ws7mPr1ueUmnjkYhhDEVmGMx84U
zguZvV1KEAZ83C3QAC3lwdpF/N+WFUpj9OlT6MjJNrB4rY8YWZDYOLGFDKaZieAX
dQR5TxRyOi/WiGpWJx7c3GNgeSSJ1zHRIV/8DJqffO8sMWBqyueAeZK0HIyc3D49
CUglR5j3/8V5T2CDppWGpfOU48m9QKx/bSoZZC5AEzS6k1NIGNMp4mrZUpUzo1Oe
fSJoFQXvfnnw3V5OV+IPbrwvGa3g74NKv1lBq5VUtqKI26t1TW9S7qFKVeQf+lUV
TEN2jBeIyMdDNh0CJ7E10AsCAwEAAQ==
-----END PUBLIC KEY-----
EOD;
    // Khai báo Key Viettel Private do Viettel cung cấp
    public $viettelPrivateKey = <<<EOD
-----BEGIN RSA PRIVATE KEY-----
Viettel-Private-Key-Example
-----END RSA PRIVATE KEY-----
EOD;
    // Khai báo Key CP Public do Viettel cung cấp
    public $cpPublicKey = <<<EOD
-----BEGIN PUBLIC KEY-----
MIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEAkdPtZ3i5+mjaVEr5k9vm
/oRjJCE/8wGQ4o6dJsy9udCP1hml7ootfcBCRbiCnXWuyEy4YtE2Vf5XPx3sguYd
rMmbHVFbHQQhIUZLBIMR9JiVGj2QRaZx953uQlacYA7vmFjRKyF/LTdqJx8/kas7
yu/7GqB6HSDOkU7FaXkhgECxAXK8PrJ70aLjeBo01jL16Nmn0EHFvohHTfEjoBzE
W8jCyXGE8Xbd1xdWTWwPS7flx0AV/y44xKZb7H4syv3xkRV810xeZXNK9dKJ11dp
foq8Jv6q7hmE64JIKtpbnJZHbUIDnYuiQGGrHIWv7f7lx9mzzoosZAqdBn2R0UCp
6elQ2w+j2tcPpnYcIAxXwJKJluS2TjrJSubLL1Zk1krw8QJavoMLrugzGRj+qmLw
FY+NeNNN8fOkO5T+F++uX9qLWBnP2/ZqBsTTQ2v28jE9Ep4MpSs2NKejCeA4VSzR
VGa62X5zL5kC7ttkPCNM6Zr9k4eHYpBB5R3P4GHhixpQ3Hu4YnNcsdlWuM/qzXZd
5RXPXO1VKauZWnEUAKmYxijDI+Sqg0bnyUj3KcJm55sI3h7f54cCzzuagDVGKqnI
8j4ZjUjHcmIgee6GxdlazsMGpQg2SyImSN6qutYdQSpxeQYOSN1qF0vfiO0RGnW/
s9B8ZKQcK8fKyH+dvfx35k0CAwEAAQ==
-----END PUBLIC KEY-----
EOD;
    // Khai báo Key CP Private do Viettel cung cấp
    public    $cpPrivateKey = <<<EOD
-----BEGIN RSA PRIVATE KEY-----
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
-----END RSA PRIVATE KEY-----
EOD;
    protected $CI;

    /**
     * Viettel_mps_key constructor.
     */
    public function __construct()
    {
        $this->CI =& get_instance();
    }

    /**
     * Function getViettelPublicKey
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 9/19/18 16:30
     *
     * @return string
     */
    public function getViettelPublicKey()
    {
        return $this->viettelPublicKey;
    }

    /**
     * Function getViettelPrivateKey
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 9/19/18 16:30
     *
     * @return string
     */
    public function getViettelPrivateKey()
    {
        return $this->viettelPrivateKey;
    }

    /**
     * Function getCpPublicKey
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 9/19/18 16:30
     *
     * @return string
     */
    public function getCpPublicKey()
    {
        return $this->cpPublicKey;
    }

    /**
     * Function getCpPrivateKey
     *
     * @author: 713uk13m <dev@nguyenanhung.com>
     * @time  : 9/19/18 16:30
     *
     * @return string
     */
    public function getCpPrivateKey()
    {
        return $this->cpPrivateKey;
    }
}
