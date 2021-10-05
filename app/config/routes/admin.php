<?php
$route['admin/logout'] = 'admin/auth/logout';

$route['admin'] = 'admin/dashboard';
$route['admin/dashboard'] = 'admin/dashboard';

$route['admin/profile'] = 'admin/profile';

$route['admin/setting/store'] = 'admin/setting/store';
$route['admin/setting/bank'] = 'admin/setting/bank';
$route['admin/setting/courier'] = 'admin/setting/courier';
$route['admin/setting/maintenance'] = 'admin/setting/maintenance';
$route['admin/setting/logo'] = 'admin/setting/logo';
$route['admin/setting/notification'] = 'admin/setting/notification';
$route['admin/setting/consumer'] = 'admin/setting/consumer';

// page setting
$route['admin/setting/homepage'] = 'admin/setting/homepage';
$route['admin/setting/about'] = 'admin/setting/about';
$route['admin/setting/faq'] = 'admin/setting/faq';
$route['admin/setting/privacy-policy'] = 'admin/setting/privacyPolicy';
$route['admin/setting/terms-and-conditions'] = 'admin/setting/termsAndConditions';
$route['admin/setting/contact'] = 'admin/setting/contact';
$route['admin/setting/payment-method'] = 'admin/setting/paymentMethod';

$route['admin/category'] = 'admin/category';

$route['admin/product'] = 'admin/product';
$route['admin/product/form'] = 'admin/product/create';

$route['admin/invoice'] = 'admin/invoice/history';
$route['admin/invoice'] = 'admin/invoice/waiting';

$route['admin/complaint'] = 'admin/complaint';
$route['admin/complaint/detail'] = 'admin/complaint/detail';

$route['admin/order/waiting'] = 'admin/order/waiting';
$route['admin/order/process'] = 'admin/order/process';
$route['admin/order/send'] = 'admin/order/send';
$route['admin/order/complete'] = 'admin/order/complete';
$route['admin/order/detail'] = 'admin/order/detail';

$route['admin/message'] = 'admin/message';
$route['admin/message/detail'] = 'admin/message/detail';

$route['admin/introduction'] = 'admin/introduction';