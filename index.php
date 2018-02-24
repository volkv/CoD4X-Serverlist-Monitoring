<?php
/*
 * CoD4X Serverlist Monitoring based on serverstatus.xml
 * Made by UnCool (CoD4Narod.RU)
 */

define('DIR', __DIR__ . "/");

include DIR . 'config.php';
include DIR . 'inc/functions.php';

error_reporting(E_ALL);
ini_set('display_errors', '1');

$baseURL = str_replace($_SERVER["DOCUMENT_ROOT"], "", DIR);
?>

<div class="c4n-mon">
	<link rel="stylesheet" href="<?= $baseURL ?>css/style.css">

	<script>
		function showPlayers(server) {
			var players = document.getElementById('c4n-mon__playerslist_' + server);
			players.style.visibility = "visible";
			players.style.opacity = "1";
			window.onclick = function (event) {
				if (event.target == players) {
					players.style.visibility = "hidden";
					players.style.opacity = "0";
				}
			}
		}
	</script>

	<table class="c4n-mon__servers">
		<tbody>
		<?php

		$d['players_total'] = 0;
		$d['players_max'] = 0;

		foreach ($servers as $server_id => $server) {
			clearstatcache();
			$xml = simplexml_load_file($server . 'serverstatus.xml');
			$d['players'] = $xml->Clients[0]['Total'][0];
			$d['players_total'] += $d['players'];
			$d['ip'] = $ip[$server_id];
			$d['time'] = $xml['TimeStamp'];

			foreach ($xml->Game->Data as $data) {
				switch ($data['Name']) {
					case 'sv_hostname':
						$d['sv_hostname'] = (string)$data['Value'];
						$d['servers'][$server_id] = $d['sv_hostname'];
						break;
					case 'mapname':
						$d['mapname'] = (string)$data['Value'];
						break;
					case 'uptime':
						$d['uptime'] = (string)$data['Value'];
						break;
					case 'g_mapStartTime':
						$time = date_create((string)$data['Value']);
						$now = date_create();
						$d['map_time'] = $now->diff($time)->i . ':' . sprintf("%02d", $now->diff($time)->s);
						break;
					case 'sv_maxclients':
						$d['sv_maxclients'] = (int)$data['Value'];
						break;
					case 'sv_privateClients':
						$d['sv_privateClients'] = (int)$data['Value'];
						break;
				}
			}

			$d['players_max'] = $d['players_max'] + $d['sv_maxclients'] - $d['sv_privateClients'];

			foreach ($xml->Clients->Client as $client) {
				$cid = (int)$client['CID'];
				$team = (int)$client['Team'];

				switch ((string)$client['TeamName']) {
					case 'Connecting...':
						$team = 4;
						break;
					case 'Loading...':
						$team = 5;
						break;
					case 'Free':
						$team = 6;
						break;
				}
				if (array_key_exists((string)$client['TeamName'], $lang))
					$d['teams'][$server_id][$team] = $lang[(string)$client['TeamName']];
				else
					$d['teams'][$server_id][$team] = colorize((string)$client['TeamName']);

				$d['clients'][$server_id][$team][$cid]['name'] = (string)$client['ColorName'];
				$d['clients'][$server_id][$team][$cid]['score'] = (string)$client['Score'];
				$d['clients'][$server_id][$team][$cid]['kills'] = (string)$client['Kills'];
				$d['clients'][$server_id][$team][$cid]['deaths'] = (string)$client['Deaths'];
				$d['clients'][$server_id][$team][$cid]['assists'] = (string)$client['Assists'];
				$d['clients'][$server_id][$team][$cid]['ping'] = (string)$client['Ping'];
				$d['clients'][$server_id][$team][$cid]['rank'] = (string)$client['rank'];
			}

			$d['percent'] = (int)(($d['players'] / ($d['sv_maxclients'] - $d['sv_privateClients'])) * 100);

			if ($d['percent'] < 20) {
				$d['percent_color'] = '#3498DB';
			} elseif ($d['percent'] >= 20 AND $d['percent'] < 40) {
				$d['percent_color'] = '#16CC35';
			} elseif ($d['percent'] >= 40 AND $d['percent'] < 60) {
				$d['percent_color'] = '#B3CA1C';
			} elseif ($d['percent'] >= 60 AND $d['percent'] < 80) {
				$d['percent_color'] = '#F1C40F';
			} elseif ($d['percent'] >= 80 AND $d['percent'] < 100) {
				$d['percent_color'] = '#dcb20b';
			} else {
				$d['percent'] = 100;
				$d['percent_color'] = '#E74C3C';
			}
			?>

			<tr>
				<td>
					<span title="Online for <?= $d['uptime'] ?>" class="c4n-mon__badge<?= ((time() - $d['time']) < 20) ? ' c4n-mon__badge_positive' : '' ?>"></span>

				</td>

				<td class="c4n-mon__hostname">
					<?= colorize($d['sv_hostname']) ?>
				</td>
				<td title="<?= $lang['connect_to'] . " " . uncolorize($d['sv_hostname']) ?>" class="c4n-mon__ip">
					<a href="cod4://<?= $d['ip'] ?>"><?= $d['ip'] ?></a></td>
				<td class="c4n-mon__map <?= $d['mapname'] ?>"><?= $d['mapname'] ?></td>
				<td title="<?= $lang['round_time'] ?>"><?= $d['map_time'] ?></td>
				<td <?= 'title="' . $lang['oper_playerlist'] . " " . uncolorize($d['sv_hostname']) . '"' ?>>
					<div class="c4n-mon__players-graph <?= ($d['players'] < 1) ? ' no-players' : '' ?>">
						<div class="c4n-mon__players-progress" style="background-color:<?= $d['percent_color'] ?>;width:<?= $d['percent'] ?>%">
							<div <?= ($d['players'] > 0 ? 'onclick="showPlayers(' . $server_id . ')"' : '') ?> class="c4n-mon__players-count"><?= $d['players'] . '/' . ($d['sv_maxclients'] - $d['sv_privateClients']) ?></div>
						</div>
					</div>

				</td>
				<td class="c4n-mon__icons">
					<?php if (isset($linkSS[$server_id])) { ?>
						<i title="<?= $lang['link_ss'] ?>" class="c4n-mon__icon">
							<a href="<?= $linkSS[$server_id] ?>" target="_blank"><img src="<?= $baseURL ?>img/ss.svg"></a>
						</i>
					<?php } ?>
					<?php if (isset($linkSTAT[$server_id])) { ?>
						<i title="<?= $lang['link_stats'] ?>" class="c4n-mon__icon">
							<a href="<?= $linkSTAT[$server_id] ?>" target="_blank"><img src="<?= $baseURL ?>img/stats.svg"></a>
						</i>
					<?php } ?>
					<?php if (isset($linkCHAT[$server_id])) { ?>
						<i title="<?= $lang['link_chat'] ?>" class="c4n-mon__icon">
							<a href="<?= $linkCHAT[$server_id] ?>" target="_blank"><img src="<?= $baseURL ?>img/chat.svg"></a>
						</i>
					<?php } ?>

					<i title="<?= $lang['link_gt'] ?>" class="c4n-mon__icon">
						<a href="http://www.gametracker.com/server_info/<?= $d['ip'] ?>" target="_blank"><img src="<?= $baseURL ?>img/gt.svg"></a>
					</i>

					<?php if (isset($linkDONATE[$server_id])) { ?>
						<i title="<?= $lang['link_donate'] ?>" class="c4n-mon__icon">
							<a href="<?= $linkDONATE[$server_id] ?>" target="_blank"><img src="<?= $baseURL ?>img/donate.svg"></a>
						</i>
					<?php } ?>
				</td>
			</tr>

		<?php }

		$max = explode(";", file_get_contents(DIR . 'inc/max'));
		$players_record = (int)$max[0];
		$players_record_date = $max[1];

		if ($players_record < $d['players_total']) {
			file_put_contents(DIR . 'inc/max', $d['players_total'] . ";" . date('d.m.y H:i'));
			$players_record = $d['players_total'];
			$players_record_date = date('d.m.y H:i');
		}
		?>

		</tbody>

	</table>
	<div class="c4n-mon__total">
		<div class="c4n-mon__total-progress" style="width: <?= (int)($d['players_total'] / $d['players_max'] * 100) ?>%; background-color:#2ecc71;"></div>
		<div class="c4n-mon__total-label"><?= $d['players_total'] . '/' . $d['players_max'] ?></div>
		<span class="c4n-mon__record">
				<?= $lang['record'] . ": " . $players_record . ' (' . $players_record_date . ')' ?>
		</span>
	</div>

	<?php foreach ($d['clients'] as $server_id => $server) { ?>

		<div id="c4n-mon__playerslist_<?= $server_id ?>" class="c4n-mon__playerslist">
			<div class="c4n-mon__playerscontent">

				<h2><?= colorize($d['servers'][$server_id]) ?></h2>

				<?php ksort($server);
				foreach ($server as $team => $players) { ?>

					<div class="c4n-mon__table_group">
						<table class="c4n-mon__players">
							<caption><?= $d['teams'][$server_id][$team] ?></caption>
							<thead>
							<tr>
								<th><?= $lang['player'] ?></th>
								<th><?= $lang['score'] ?></th>
								<th><?= $lang['kills'] ?></th>
								<th><?= $lang['deaths'] ?></th>
								<th><?= $lang['kd'] ?></th>
								<th><?= $lang['assists'] ?></th>
								<th><?= $lang['ping'] ?></th>
							</tr>
							</thead>
							<tbody>

							<?php usort($players, 'sort_by_score');
							foreach ($players as $player) {
								$rank_text = get_rank_text($player['rank']); ?>

								<tr>
									<td class="c4n-mon__playername">
				<span class="c4n-mon__rank-wrapper" title="<?= $rank_text ?>">
					<span class="c4n-mon__rank-img-wrapper"><img class="c4n-mon__rank" src="/stats/ranks/<?= get_prestige_icon($player['rank']) ?>.png"></span>
					<sub class="c4n-mon__rank_number"><?= $player['rank'] ?></sub>
				</span>
										<span>	<?= $player['name'] ?></a></span>

									</td>
									<td>
										<?= $player['score'] ?>
									</td>

									<td>
										<?= $player['kills'] ?>
									</td>
									<td>
										<?= $player['deaths'] ?>
									</td>
									<td>
										<?= ($player['deaths'] > 0) ? number_format($player['kills'] / $player['deaths'], 2) : 0; ?>
									</td>

									<td>
										<?= $player['assists'] ?>
									</td>
									<td>
										<?= $player['ping'] ?>
									</td>
								</tr>
							<?php } ?>
							</tbody>
						</table>
					</div>
				<?php } ?>
			</div>
		</div>
	<?php } ?>
</div>
