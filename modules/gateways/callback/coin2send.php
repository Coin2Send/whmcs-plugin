<?php
require_once __DIR__ . '/../../../init.php';
require_once __DIR__ . '/../../../includes/gatewayfunctions.php';
require_once __DIR__ . '/../../../includes/invoicefunctions.php';

$gatewayModuleName = 'coin2send';
$gatewayParams = getGatewayVariables($gatewayModuleName);

if (!$gatewayParams['type']) {
    logTransaction($gatewayParams['name'], $_POST, 'Not activated');
    die('[ERROR] In modules/gateways/callback/coin2send.php: Coin2Send module not activated.');
}
$invoiceId = $_POST["custom_1"];
$transactionId = $_POST['transaction_id'];
$sci_password = $gatewayParams['SCIPassword'];
$paymentAmount = $_POST["amount"];
$paymentFee = $_POST["x_fee"];
$hash = md5($_POST['transaction_id'].':'.$gatewayParams['SCIUsername'].':'.$_POST['customer'].':'.$paymentAmount.':'.$_POST['currency'].':'.$sci_password);

$success = true;
$transactionStatus = $success ? 'Success' : 'Failure';

if($hash != $_POST['hash']){
	$transactionStatus = 'Hash Verification Failure';
	$success = false;
}
$invoiceId = checkCbInvoiceID($invoiceId, $gatewayParams['name']);
checkCbTransID($transactionId);

logTransaction($gatewayParams['name'], $_POST, $transactionStatus);

if($success){
    addInvoicePayment(
        $invoiceId,
        $transactionId,
        $paymentAmount,
        0,
        $gatewayModuleName
    );
}
