Status for the different users in the system
0-means not activated
1-means activated
2-pending payment
3-suspended

'<div style="display:flex;align-items:center;justify-content:space-between;">
    <form action="./show.php?id="' . $row['roleId'] . '"" method="get">
    <button type="submit" name="show"  value="' . $row['roleId'] . '"
    class="btn btn-info btn-sm editbtn" >Show</button>

    </form>

    <form action="./edit.php?id="' . $row['roleId'] . '"" method="get">
    <button type="submit" name="update"  value="' . $row['roleId'] . '"
    class="btn btn-info btn-sm editbtn" >Edit</button>

    </form>
    <form method="POST" action="./delete.php">
      <input type="hidden" name="id" value="' . $row['roleId'] . '"/>
      <button 
    class="btn btn-danger btn-sm deleteBtn" >Delete</button>

    </form>
    </div>'