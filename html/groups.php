<?php

require_once 'include/global.php';

// Get a list of possible groups
$stmt = $pdo->prepare("select * from
	`groups` where
	`type` = 'group'");
$stmt->execute();

require 'include/header.php';
?>
<table id="password-list">
        <thead>
                <tr><th class="input" colspan="6"><input type="search" placeholder="Search for a Group" autofocus></th></tr>
                <tr class="menu"><th></th><th class="inner-first">Name</th><th>Username</th><th>Password</th><th class="inner-last">Description</th><th></th></tr>
        </thead>
        <tfoot>
                <tr><td></td><td colspan="4"></td><td></td></tr>
                <tr class="spacer"><td></td></tr>
        </tfoot>
        <tbody>
<?php

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

}
?>
	</tbody>
</table>

<?php

require 'include/footer.php';

?>
