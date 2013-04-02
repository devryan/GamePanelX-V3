<?php
/**
 * This file is part of GameQ.
 *
 * GameQ is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * GameQ is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
*/

require_once GAMEQ_BASE . 'Protocol.php';

/**
 * Minequery protocol protocol
 * http://forums.bukkit.org/threads/dev-minequery-1-5-a-query-server-that-responds-with-server-info-1185.1358/
 *
 * @author      Alfie "Azelphur" Day    <support@azelphur.com>
 * @version     $Revision: 0.1 $
*/

class GameQ_Protocol_minequery extends GameQ_Protocol
{
    public function status()
    {
        $result = json_decode($this->p->readString("\n"), true);
        $this->r->add('server_port',    $result["serverPort"]);
        $this->r->add('num_players',    $result["playerCount"]);
        $this->r->add('max_players',    $result["maxPlayers"]);
	foreach ($result["playerList"] as $player)
	{
		$this->r->addPlayer('name',    $player);
	}
    }
}
?>
