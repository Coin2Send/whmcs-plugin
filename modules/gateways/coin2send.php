<?php
if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}


function coin2send_config()
{
    return array(
        'FriendlyName' => array(
            'Type' => 'System',
            'Value' => 'Coin2Send'
        ),
        'SCIUsername' => array(
            'FriendlyName' => 'Username',
            'Description' => 'Coin2Send\'s username',
            'Type' => 'text',
        ),
        'SCIPassword' => array(
            'FriendlyName' => 'SCI Password',
            'Description' => 'The SCI password',
            'Type' => 'text',
        )
    );
}

function coin2send_link($params)
{
    if (false === isset($params) || true === empty($params)) {
        die('[ERROR] In modules/gateways/coin2send.php::coin2send_link() function: Missing or invalid $params data.');
    }

    if (substr($params['systemurl'], -1) != "/") {
      $returnlink = $params['systemurl'] . "/";
    } else {
      $returnlink = $params['systemurl'];
    }


	$form = '<form action="https://www.coin2send.com/sci" method="POST">';
	$form .= '<input type="hidden" name="account" value="'. $params['SCIUsername'] .'">';
	$form .= '<input type="hidden" name="store" value="'. $params['companyname'] .'">';
	$form .= '<input type="hidden" name="amount" value="'. number_format($params['amount'], 8, '.', '') .'">';
	$form .= '<input type="hidden" name="currency" value="'. $params['currency'] .'">';
	$form .= '<input type="hidden" name="memo" value="'.$params['description'].'">';
	$form .= '<input type="hidden" name="custom_1" value="'. $params['invoiceid'] .'">';
	$form .= '<input type="hidden" name="success_url" value="'.$returnlink . 'viewinvoice.php?id=' . $params['invoiceid'].'">';
	$form .= '<input type="hidden" name="fail_url" value="'.$returnlink . 'clientarea.php">';
	$form .= '<input type="hidden" name="status_url" value="'.$returnlink . 'modules/gateways/callback/coin2send.php">';
	$form .= '<input type="submit" value="' . $params['langpaynow'] . '" />';
	$form .= '</form>';
	return $form;
}
