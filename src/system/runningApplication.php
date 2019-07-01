<?php

$return_var = exec('pidof $app_name');

  if ($return_var > 0) {

    echo "UP";

  }

  if ($return_var == 0) {

    echo "DOWN";

  }

?>

<?php

$return_var = exec('pidof $app_name');

  if ($return_var > 0) {

    echo "<b><font color='blue'>Up</font></b>";

  }

  if ($return_var == 0) {

    echo "<b><font color='red'>DOWN</font></b>";

  }

?>

<?php

$return_var = exec('pidof $app_name');

  if ($return_var > 0) {

    echo "<b><font color='blue'>Up</font></b>, with pid: ";
    echo $return_var;

  }

  if ($return_var == 0) {

    echo "<b><font color='red'>DOWN</font></b>";

  }

?>