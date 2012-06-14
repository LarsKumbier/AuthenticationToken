<?php

require('AuthenticationToken.php');

$user1 = array (
	'username'    => "meiermax",
	'firstname'   => "Max",
	'lastname'    => "Meier",
	'mailaddress' => "max.meier@fh-heidelberg.de");

$token = new AuthenticationToken();
$authToken1 = $token->GetTokenByArray($user1);

echo "Test for Authentication Token\n";
foreach ($user1 as $key => $value)
	echo "  ".str_pad($key, 18).' => '.$value."\n";	
echo "\n";

echo 'Default Options authToken is '.$authToken1."\n\n";

echo "Valid Algorithms on this system:\n";
$algos = wordwrap(implode(" | ", $token->GetValidAlgorithms()));
$algos = str_replace("\n", "\n  ", $algos);
echo "  ".$algos."\n\n";

echo "Testing changed value in user...          ";
$user2 = $user1;
$user2['username'] .= 'maiermax';
$authToken2 = $token->GetTokenByArray($user2);
echo ($authToken1 != $authToken2) ? "ok\n" : "FAIL\n";

echo "Testing changed order of keys...          ";
$user2 = array (
	'firstname'   => $user1['firstname'],
	'mailaddress' => $user1['mailaddress'],
	'lastname'    => $user1['lastname'],
	'username'    => $user1['username']);
$authToken2 = $token->GetTokenByArray($user2);
echo ($authToken1 == $authToken2) ? "ok\n" : "FAIL\n";

echo 'Testing changed secret...                 ';
$token->SetSecret('clidcalovopcecBiemchindObjickfac');
$authToken2 = $token->GetTokenByArray($user1);
echo ($authToken1 != $authToken2) ? "ok\n" : "FAIL\n";

echo 'Testing invalid algorithm exception...    ';
$exception = false;
try {
	$token->SetAlgorithm('non-existant');
} catch (Exception $e) {
	$exception = true;
}
echo ($exception) ? "ok\n" : "FAIL\n";

echo 'Testing too small secret exception...     ';
$exception = false;
try {
	$token->SetSecret('abcdefghijlmnop');
} catch (Exception $e) {
	$exception = true;
}
echo ($exception) ? "ok\n" : "FAIL\n";

echo 'Testing too big secret exception...       ';
$exception = false;
try {
	$token->SetSecret(str_pad('abcdefghijklmnop', 129, 'x'));
} catch (Exception $e) {
	$exception = true;
}
echo ($exception) ? "ok\n" : "FAIL\n";

echo "\n";
echo "done!";

exit();

?>
