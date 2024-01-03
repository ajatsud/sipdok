<?php

if (!defined("VER")) {
	exit("No direct script access allowed");
}

$flash = flash();

?>

</div>
<script src="/res/sweetalert.js"></script>
<?php if ($flash) : ?>
	<script>
		swal("<?= $flash["title"] ?>", "<?= $flash["message"] ?>", "<?= $flash["icon"] ?>");
	</script>
<?php endif; ?>
</body>

</html>