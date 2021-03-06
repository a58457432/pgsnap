<?php

/*
 * Copyright (c) 2008-2016 Guillaume Lelarge <guillaume@lelarge.info>
 *
 * Permission to use, copy, modify, and distribute this software for any
 * purpose with or without fee is hereby granted, provided that the above
 * copyright notice and this permission notice appear in all copies.
 *
 * THE SOFTWARE IS PROVIDED "AS IS" AND THE AUTHOR DISCLAIMS ALL WARRANTIES
 * WITH REGARD TO THIS SOFTWARE INCLUDING ALL IMPLIED WARRANTIES OF
 * MERCHANTABILITY AND FITNESS. IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR
 * ANY SPECIAL, DIRECT, INDIRECT, OR CONSEQUENTIAL DAMAGES OR ANY DAMAGES
 * WHATSOEVER RESULTING FROM LOSS OF USE, DATA OR PROFITS, WHETHER IN AN
 * ACTION OF CONTRACT, NEGLIGENCE OR OTHER TORTIOUS ACTION, ARISING OUT OF
 * OR IN CONNECTION WITH THE USE OR PERFORMANCE OF THIS SOFTWARE.
 */

$buffer = $navigate_stats.'
<div id="pgContentWrap">

<h1>Stats On Functions</h1>
';

$query = "SELECT
  schemaname,
  funcname,
  calls,
  total_time,
  self_time
FROM pg_stat_user_functions
ORDER BY schemaname, funcname";

$rows = pg_query($connection, $query);
if (!$rows) {
  echo "An error occured.\n";
  exit;
}

$buffer .= '<div class="tblBasic">

<table id="myTable" border="0" cellpadding="0" cellspacing="0" class="tblBasicGrey">
<thead>
<tr>
  <th class="colFirst">Schema name</th>
  <th class="colMid">Function name</th>
  <th class="colMid">Calls</th>
  <th class="colMid">Total Time</th>
  <th class="colLast">Self Time</th>
</tr>
</thead>
<tbody>
';

while ($row = pg_fetch_array($rows)) {
$buffer .= tr($row['schemaname'])."
  <td title=\"".$comments['schemas'][$row['schemaname']]."\">".$row['schemaname']."</td>
  <td title=\"".$comments['functions'][$row['schemaname']][$row['funcname']]."\">".$row['funcname']."</td>
  <td>".$row['calls']."</td>
  <td>".$row['total_time']."</td>
  <td>".$row['self_time']."</td>";
}
$buffer .= "
</tr>";

$buffer .= '</tbody>
</table>
</div>
';

$buffer .= '<button id="showthesource">Show SQL commands!</button>
<div id="source">
<p>'.$query.'</p>
</div>';

$filename = $outputdir.'/stat_functions.html';
include 'lib/fileoperations.php';

?>
