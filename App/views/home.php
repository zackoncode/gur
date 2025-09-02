<?php
if (!isset($_SESSION['user'])) {
  echo "session perida";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $title ?></title>
</head>

<body>
  <h1>index pagina inical</h1>
</body>

</html>