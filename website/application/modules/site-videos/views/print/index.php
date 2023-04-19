<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: 713uk13m
 * Date: 5/10/18
 * Time: 15:14
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-gb" lang="en-gb">
<head>
<meta charset="UTF-8" />
<title><?php echo $data['site_title']; ?></title>
<?php $this->load->view('libraries/template_print'); ?>
</head>
<body>
<?php
if (isset($sub))
{
    if (isset($data))
    {
        $this->load->view($sub, $data);
    }
    else
    {
        $this->load->view($sub);
    }
}
?>
</body>
</html>