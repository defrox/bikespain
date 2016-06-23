<?php
/**
 * Created by PhpStorm.
 * User: defrox
 * Date: 26/05/16
 * Time: 23:00
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('csv2mysql.php');
use defrox\CSV2mySQL as CSV2mySQL;
$result = new CSV2mySQL();

if ($_POST) {
    $infile = array_key_exists('infile', $_POST) ? $_POST["infile"] : '';
    $outfile = array_key_exists('outfile', $_POST) ? $_POST["outfile"] : '';
    $type = array_key_exists('type', $_POST) ? $_POST["type"] : '';
    $entity = array_key_exists('entity', $_POST) ? $_POST["entity"] : '';
    $taxonomy = array_key_exists('taxonomy', $_POST) ? $_POST["taxonomy"] : '';
    $showout = array_key_exists('showout', $_POST) ? $_POST["showout"] : '';
    $dbhost = array_key_exists('dbhost', $_POST) ? $_POST["dbhost"] : '';
    $dbport = array_key_exists('dbport', $_POST) ? $_POST["dbport"] : '';
    $dbuser = array_key_exists('dbuser', $_POST) ? $_POST["dbuser"] : '';
    $dbpass = array_key_exists('dbpass', $_POST) ? $_POST["dbpass"] : '';
    $dbname = array_key_exists('dbname', $_POST) ? $_POST["dbname"] : '';
    $dbexec = array_key_exists('dbexec', $_POST) ? $_POST["dbexec"] : '';
} else {
    $infile = $outfile = $type = $entity = $showout = $dbhost = $dbuser = $dbpass = $dbname = $dbexec = $dbport = $taxonomy = '';
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<form method="post">
    <table>
        <tbody>
        <tr>
            <td>
                <label for="infile">Origin Filename</label>
            </td>
            <td>
                <input type="text" name="infile" id="infile" value="<?php echo $infile; ?>"/>
            </td>
        </tr>
        <tr>
            <td>
                <label for="outfile">Output Filename</label>
            </td>
            <td>
                <input type="text" name="outfile" id="outfile" value="<?php echo $outfile; ?>"/>
            </td>
        </tr>
        <tr>
            <td>
                <label for="entity">Entity starting number</label>
            </td>
            <td>
                <input type="number" name="entity" id="entity" value="<?php echo $entity; ?>"/>
            </td>
        </tr>
        <tr>
            <td>
                <label for="dbhost">DB Host</label>
            </td>
            <td>
                <input type="text" name="dbhost" id="dbhost" value="<?php echo $dbhost; ?>"/>
            </td>
        </tr>
        <tr>
            <td>
                <label for="dbport">DB Port</label>
            </td>
            <td>
                <input type="number" name="dbport" id="dbport" value="<?php echo $dbport; ?>"/>
            </td>
        </tr>
        <tr>
            <td>
                <label for="dbuser">DB User</label>
            </td>
            <td>
                <input type="text" name="dbuser" id="dbuser" value="<?php echo $dbuser; ?>"/>
            </td>
        </tr>
        <tr>
            <td>
                <label for="dbpass">DB Password</label>
            </td>
            <td>
                <input type="password" name="dbpass" id="dbpass" value="<?php echo $dbpass; ?>"/>
            </td>
        </tr>
        <tr>
            <td>
                <label for="dbname">DB Name</label>
            </td>
            <td>
                <input type="text" name="dbname" id="dbname" value="<?php echo $dbname; ?>"/>
            </td>
        </tr>
        <tr>
            <td>
                <label for="type">Profile Type</label>
            </td>
            <td>
                <select name="type" id="type">
                    <option value="proveedor" <?php echo $type == 'proveedor' ? 'selected' : ''; ?>>Proveedor</option>
                    <option value="cliente" <?php echo $type == 'cliente' ? 'selected' : ''; ?>>Cliente</option>
                    <option value="contacto" <?php echo $type == 'contacto' ? 'selected' : ''; ?>>Contacto</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>
                <label for="taxonomy">Taxonomy Machine Name</label>
            </td>
            <td>
                <input type="text" name="taxonomy" id="taxonomy" value="<?php echo $taxonomy; ?>"/>
            </td>
        </tr>
        <tr>
            <td>
                <input type="checkbox" value="true" name="dbexec"
                       id="dbexec" <?php echo $dbexec == 'true' ? 'checked' : ''; ?>/>
                <label for="dbexec">Execute SQL query?</label>
            </td>
            <td>
                <input type="checkbox" value="true" name="showout"
                       id="showout" <?php echo $showout == 'true' ? 'checked' : ''; ?> />
                <label for="showout">Show SQL output?</label>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="submit" value="Submit">
            </td>
        </tr>
        </tbody>
    </table>
</form>
<?php
if ($_POST) {
    $output_exec = '';
    $infile = array_key_exists('infile', $_POST) && $_POST["infile"] != '' ? $_POST["infile"] : "./Provedores2016.csv";
    $outfile = array_key_exists('outfile', $_POST) && $_POST["outfile"] != '' ? $_POST["outfile"] : "output.sql";
    $entity = array_key_exists('entity', $_POST) && $_POST["entity"] != '' ? $_POST["entity"] : 0;
    $result->setConnection($dbhost, $dbuser, $dbpass, $dbname, $dbport);
    if ($taxonomy != '') $result->setTaxonomy($taxonomy);
    $output = $result->process($infile, $outfile, $_POST['type'], $entity);
    if ($dbexec == 'true') $output_exec = $result->dbExec($result->getSql());
    if ($output != '') echo $output . "<br/>";
    if ($output_exec != '') echo $output_exec . "<br/>";
    if (array_key_exists('showout', $_POST) && $_POST["showout"]) echo '<pre>' .  $result->getSql() . '</pre>';
}
?>
</body>
</html>