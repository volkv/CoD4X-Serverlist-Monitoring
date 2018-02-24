<?php
function sort_by_score($a, $b) {
	return $b['score'] > $a['score'] ? 1 : -1;
}

function uncolorize($string) {
	$string = str_replace('^1', '', $string);
	$string = str_replace('^2', '', $string);
	$string = str_replace('^3', '', $string);
	$string = str_replace('^4', '', $string);
	$string = str_replace('^5', '', $string);
	$string = str_replace('^6', '', $string);
	$string = str_replace('^7', '', $string);
	$string = str_replace('^8', '', $string);
	$string = str_replace('^9', '', $string);
	$string = str_replace('^0', '', $string);
	return $string;
}

function colorize($string) {
	$string .= "^";

	$find = array(
		'/\^0(.*?)\^/is',
		'/\^1(.*?)\^/is',
		'/\^2(.*?)\^/is',
		'/\^3(.*?)\^/is',
		'/\^4(.*?)\^/is',
		'/\^5(.*?)\^/is',
		'/\^6(.*?)\^/is',
		'/\^7(.*?)\^/is',
		'/\^8(.*?)\^/is',
		'/\^9(.*?)\^/is',
	);

	$replace = array(
		'<span style="color:#777777;">$1</span>^',
		'<span style="color:#F65A5A;">$1</span>^',
		'<span style="color:#00F100;">$1</span>^',
		'<span style="color:#EFEE04;">$1</span>^',
		'<span style="color:#0F04E8;">$1</span>^',
		'<span style="color:#04E8E7;">$1</span>^',
		'<span style="color:#F75AF6;">$1</span>^',
		'<span style="color:#FFFFFF;">$1</span>^',
		'<span style="color:#7E7E7E;">$1</span>^',
		'<span style="color:#6E3C3C;">$1</span>^',
	);

	$string = preg_replace($find, $replace, $string);
	return substr($string, 0, strlen($string) - 1);
}

function get_prestige_icon($rank) {
	if ($rank < 4)
		return "1-3";
	if ($rank < 7)
		return "4-6";
	if ($rank < 10)
		return "7-9";
	if ($rank < 13)
		return "10-12";
	if ($rank < 16)
		return "13-15";
	if ($rank < 19)
		return "16-18";
	if ($rank < 22)
		return "19-21";
	if ($rank < 24)
		return "22-24";
	if ($rank < 26)
		return "25-27";
	if ($rank < 31)
		return "28-30";
	if ($rank < 34)
		return "31-33";
	if ($rank < 37)
		return "34-36";
	if ($rank < 40)
		return "37-39";
	if ($rank < 43)
		return "40-42";
	if ($rank < 46)
		return "43-45";
	if ($rank < 49)
		return "46-48";
	if ($rank < 52)
		return "49-51";
	if ($rank < 55)
		return "52-54";
	else
		return "55";
}

function get_rank_text($rank) {
	if ($rank == 1)
		return "Private First Class";
	if ($rank == 2)
		return "Private First Class I";
	if ($rank == 3)
		return "Private First Class II";
	if ($rank == 4)
		return "Lance Corporal";
	if ($rank == 5)
		return "Lance Corporal I";
	if ($rank == 6)
		return "Lance Corporal II";
	if ($rank == 7)
		return "Corporal";
	if ($rank == 8)
		return "Corporal I";
	if ($rank == 9)
		return "Corporal II";
	if ($rank == 10)
		return "Sergeant";
	if ($rank == 11)
		return "Sergeant I";
	if ($rank == 12)
		return "Sergeant II";
	if ($rank == 13)
		return "Staff Sergeant";
	if ($rank == 14)
		return "Staff Sergeant I";
	if ($rank == 15)
		return "Staff Sergeant II";
	if ($rank == 16)
		return "Gunnery Sergeant";
	if ($rank == 17)
		return "Gunnery Sergeant I";
	if ($rank == 18)
		return "Gunnery Sergeant II";
	if ($rank == 19)
		return "Master Sergeant";
	if ($rank == 20)
		return "Master Sergeant I";
	if ($rank == 21)
		return "Master Sergeant II";
	if ($rank == 22)
		return "Master Gunnery Sergeant";
	if ($rank == 23)
		return "Master Gunnery Sergeant I";
	if ($rank == 24)
		return "Master Gunnery Sergeant II";
	if ($rank == 25)
		return "Second Lieutenant";
	if ($rank == 26)
		return "Second Lieutenant I";
	if ($rank == 27)
		return "Second Lieutenant II";
	if ($rank == 28)
		return "First Lieutenant";
	if ($rank == 29)
		return "First Lieutenant I";
	if ($rank == 30)
		return "First Lieutenant II";
	if ($rank == 31)
		return "Captain";
	if ($rank == 32)
		return "Captain I";
	if ($rank == 33)
		return "Captain II";
	if ($rank == 34)
		return "Major";
	if ($rank == 35)
		return "Major I";
	if ($rank == 36)
		return "Major II";
	if ($rank == 37)
		return "Lieutenant Colonel";
	if ($rank == 38)
		return "Lieutenant Colonel I";
	if ($rank == 39)
		return "Lieutenant Colonel II";
	if ($rank == 40)
		return "Colonel";
	if ($rank == 41)
		return "Colonel I";
	if ($rank == 42)
		return "Colonel II";
	if ($rank == 43)
		return "Brigadier General";
	if ($rank == 44)
		return "Brigadier General I";
	if ($rank == 45)
		return "Brigadier General II";
	if ($rank == 46)
		return "Major General";
	if ($rank == 47)
		return "Major General I";
	if ($rank == 48)
		return "Major General II";
	if ($rank == 49)
		return "Lieutenant General";
	if ($rank == 50)
		return "Lieutenant General I";
	if ($rank == 51)
		return "Lieutenant General II";
	if ($rank == 52)
		return "General";
	if ($rank == 53)
		return "General I";
	if ($rank == 54)
		return "General II";
	if ($rank == 55)
		return "Commander";
	else
		return "Unknown";
}