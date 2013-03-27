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
 * Minecraft protocol
 * http://wiki.vg/Protocol#Server_List_Ping_.280xFE.29
 *
 * @author      Alfie "Azelphur" Day    <support@azelphur.com>
 * @version     $Revision: 0.1 $
*/

class GameQ_Protocol_minecraft extends GameQ_Protocol
{
    public function status()
    {
	$result = $this->p->read($this->p->getLength());
	$result = str_replace("\x00", "", $result); 
	$result = str_replace("\x1A", "", $result); 
	$result = str_replace("\xFF", "", $result);

	$srvinfo = explode("\xA7",$result); 

        $this->r->add('hostname',       $srvinfo[0]);
        $this->r->add('num_players',    $srvinfo[1]);
        $this->r->add('max_players',    $srvinfo[2]);
    }
}
?>
