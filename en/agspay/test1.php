<form action='test.php' method='GET' name='frm'>
<?php

$_POST['hello'] = ' 한글';

foreach ($_POST as $a => $b) {
echo "<input type='hidden' name='".$a."' value='".$b."'>";
}
?>
</form>
<script language="JavaScript">
document.frm.submit();
</script>