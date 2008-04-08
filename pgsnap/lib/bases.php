<?php

/*
 * Copyright (c) 2008 Guillaume Lelarge <guillaume@lelarge.info>
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

$buffer = "<h1>Databases list</h1>";


$query = "SELECT datname,
  rolname AS dba,
  pg_catalog.pg_encoding_to_char(encoding) AS encoding,
  datistemplate,
  datallowconn,
  datconnlimit,
  datlastsysoid,
  datfrozenxid,
  spcname as tablespace,
  pg_size_pretty(pg_database_size(datname)) AS size,
  datconfig,
  datacl
FROM pg_database, pg_roles, pg_tablespace
WHERE datdba = pg_roles.oid
  AND dattablespace = pg_tablespace.oid
ORDER BY datname";

$rows = pg_query($connection, $query);
if (!$rows) {
  echo "An error occured.\n";
  exit;
}

$buffer .= "<table>
<thead>
<tr>
  <td>DB Name</td>
  <td>DB Owner</td>
  <td>Encoding</td>
  <td>Template?</td>
  <td>Allow connections?</td>
  <td>Connection limits</td>
  <td>Last system OID</td>
  <td>Frozen XID</td>
  <td>Tablespace name</td>
  <td>Size</td>
  <td>Configuration</td>
  <td><acronym X=\"Access Control List\">ACL</acronym></td>
</tr>
</thead>
<tbody>\n";

while ($row = pg_fetch_array($rows)) {
$buffer .= "<tr>
  <td>".$row['datname']."</td>
  <td>".$row['dba']."</td>
  <td>".$row['encoding']."</td>
  <td>".$image[$row['datistemplate']]."</td>
  <td>".$image[$row['datallowconn']]."</td>
  <td>".$row['datconnlimit']."</td>
  <td>".$row['datlastsysoid']."</td>
  <td>".$row['datfrozenxid']."</td>
  <td>".$row['tablespace']."</td>
  <td>".$row['size']."</td>
  <td>".$row['datconfig']."</td>
  <td><acronym X=\"Access Control List\">".$row['datacl']."</acronym></td>
</tr>";
}
$buffer .= "</tbody>
</table>";

$buffer .= '<button id="showthesource">Show SQL commands!</button>
<div id="source">
<p>'.$query.'</p>
</div>';

$filename = $outputdir.'/bases.html';
include 'lib/fileoperations.php';

?>
