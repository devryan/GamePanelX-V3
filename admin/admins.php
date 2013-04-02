<?php
require('checkallowed.php'); // Check logged-in
?>

<div class="page_title">
    <div class="page_title_icon"><img src="../images/icons/medium/accounts.png" border="0" /></div>
    <div class="page_title_text"><?php echo $lang['admins']; ?></div>
</div>


<div class="box">
<div class="box_title" id="box_servers_title"><?php echo $lang['admins']; ?></div>
<div class="box_content" id="box_servers_content">

<table border="0" cellpadding="0" cellspacing="0" align="center" width="700" class="box_table" style="text-align:left;">
  <tr>
    <td width="120"><b><?php echo $lang['username']; ?></b></td>
    <td width="150"><b><?php echo $lang['name']; ?></b></td>
    <td width="140"><b><?php echo $lang['email_address']; ?></b></td>
    <td width="120">&nbsp;</td>
  </tr>
<?php
// List users
$result_usr = @mysql_query("SELECT 
                              id,
                              first_name,
                              last_name,
                              username,
                              email_address 
                            FROM admins 
                            WHERE 
                              deleted = '0'
                            ORDER BY 
                              id DESC") or die('Failed to query for users: '.mysql_error());

while($row_usr  = mysql_fetch_array($result_usr))
{
    $usr_id         = $row_usr['id'];
    $usr_fullname   = $row_usr['first_name'] . ' ' . $row_usr['last_name'];
    $usr_usrname    = $row_usr['username'];
    $usr_email      = $row_usr['email_address'];
    
    echo '<tr id="usr_' . $usr_id. '" style="cursor:pointer;" onClick="javascript:mainpage(\'viewadmin\',' . $usr_id . ');">
            <td>' . $usr_usrname . '</td>
            <td>' . $usr_fullname . '</td>
            <td>' . $usr_email . '</td>
            <td class="links">'.$lang['manage'].'</td>
          </tr>';
}

?>
</table>

</div>
</div>
