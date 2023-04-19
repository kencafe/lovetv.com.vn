var express = require('express');
var path = require('path');
var cookieParser = require('cookie-parser');
var logger = require('morgan');
const { base64encode } = require('nodejs-base64');
var md5 = require('md5');
var requestXml = require('request');
var xml2js = require('xml2js');
var parser = new xml2js.Parser({explicitArray: false, trim: true});

var app = express();

app.use(logger('dev'));
app.use(express.json());
app.use(express.urlencoded({ extended: false }));
app.use(cookieParser());
app.use(express.static(path.join(__dirname, 'public')));

app.use('/api/v1/push', (request,response,next) => {
    let url = 'http://10.144.18.112/services/SMS_GW_MT_PROXY?wsdl'
    let msisdn = request.query.msisdn;
    let mo = request.query.mo ? request.query.mo : '';
    let mt = request.query.mt;
    let sms_usernamecp = "tintaichinh360";
    let authenticate = "h+khS9QKv@";
    let acount_send_sms = 'smsgw@2016';
    let sms_cp_code = 'THUDO';
    let sms_cp_charge = 'THUDO-INDEX360';
    let default_package = 'PUSH_TINTAICHINH360';
    let mt_base  = base64encode(mt);
    let brandname = 'THUDO';
    let transaction_id = (new Date()).getTime() ; 
    let sms_authenticate = md5(md5(transaction_id +""+ sms_usernamecp) + "" + md5(acount_send_sms + "" +  msisdn) + "" + authenticate);
    let serviceid = 'INDEX360';
    let sms_shortcode= '9088';
    let price = 0;
    let note = "";
    let content_xml = `<MODULE>SMSGW</MODULE><MESSAGE_TYPE>REQUEST</MESSAGE_TYPE><COMMAND><transaction_id>${transaction_id}</transaction_id>`
        + `<mo_id>0</mo_id><destination_address>${msisdn}</destination_address><source_address>${sms_shortcode}</source_address>` 
        + `<brandname>${brandname}</brandname><content_type>TEXT</content_type><encode_content>1</encode_content><user_name>${sms_usernamecp}</user_name>` 
        + `<authenticate>${sms_authenticate}</authenticate><info>${mt_base}</info><command_code>${mo}</command_code>` 
        + `<cp_code>${sms_cp_code}</cp_code><cp_charge>${sms_cp_charge}</cp_charge><service_code>${serviceid}</service_code>`
        + `<package_code>${default_package}</package_code><package_price>${price}</package_price></COMMAND>`;
    let data_xml = '<?xml version="1.0" encoding="utf-8"?><ACCESSGW xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">' + content_xml + '</ACCESSGW>';
    
    var options = {
        method: 'POST',
        keepAlive: false,
        url: url,
        headers: {
          'Content-type': 'text/xml',
        },
        timeout: 60000,
        body: data_xml
    };

   requestXml(options, function (error, res, body) {
       if(!error){
        parser.parseString(body,(err,result)=>{
            let error_id_request = result['ACCESSGW']['COMMAND']['error_id'];
            let error_desc_request = result['ACCESSGW']['COMMAND']['error_desc'];
            let responseResult = {};
            if (error_id_request == 0) {
                // Gửi SMS Thành công
                 responseResult = {
                    'ec'  : 0,
                    "result": result,
                    'msg': 'Success.',
                    'details': 'Gui tin nhan thanh cong.',
                    'data': {
                       'msisdn':  msisdn,
                        'mo': mo,
                        'mt': mt,
                        'note': note
                    }
                };
                response.json(responseResult).end();
            } else {
                // Gửi SMS thất bại
                 responseResult = {
                    'ec': 1,
                    "result": result,
                    'msg' : 'Failed - '+ error_desc_request,
                    'details': 'Gui tin nhan khong thanh cong.',
                    'data': {
                        'msisdn': msisdn,
                        'mo': mo,
                        'mt': mt,
                        'note': note
                    }
                };
                //if(error_id_request == 104){
                    setTimeout(function(){
                        response.json(responseResult).end();
                        }, 200);
                //}else{
                //    response.json(responseResult).end();
                //}
            }
        })
       }else{
           response.send(error).end();
       }
    }); 
    
});


module.exports = app;
