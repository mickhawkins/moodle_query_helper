<?php
/**
 * A tool to paste in Moodle formatted SQL and output an SQL editor friendly version with placeholders that can be filled.
 *
 * @copyright 2018 Michael Hawkins
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

if (isset($_POST['sql'])) {
    $sql = $_POST['sql'];
    $sql = str_replace('}', '', preg_replace('/\{/', $_POST['prefix'], $sql));

    // Find all query placeholders.
    $placeholders = [];
    preg_match_all('/[\s\=](\:[a-z0-9]+)/i', $sql, $placeholders);
    $placeholders = $placeholders[1];

    // Replace question mark placeholders with string placeholders.
    $placeholderindex = 1;
    while(($questionmarkpos = strpos($sql, '?')) !== false) {
        $placeholdername = ':placeholder' . $placeholderindex;

        // Make sure we don't reuse existing placeholder names.
        if (!empty($placeholders)) {
            while(array_search($placeholdername, $placeholders) !== false) {
                $placeholderindex++;
                $placeholdername = ':placeholder' . $placeholderindex;
            }

            $sql = substr($sql, 0, $questionmarkpos) . ':placeholder' . $placeholderindex . substr($sql, ($questionmarkpos + 1));
        }

        $placeholders[] = $placeholdername;
        $placeholderindex++;
    }

    $placeholders = array_combine($placeholders, $placeholders);

    // Sub in the placeholder values if they have been provided.
    if (isset($_POST['placeholdervalues'])) {
        foreach ($_POST['placeholdervalues'] as $placeholder => $value) {
            if (!empty($value) && isset($placeholders[$placeholder])) {
                $sql = str_replace($placeholder, "'{$value}'", $sql);
                unset($placeholders[$placeholder]);
            }
        }
    }

    if (substr($sql, -1) != ';') {
        $sql .= ';';
    }
}

?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Moodle SQL testing helper tool</title>

        <!--Bootstrap-->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
    </head>

    <body>
        <div class="container" style="padding: 20px; background-color:#CCEBFF">
            <h2>Moodle SQL Testing Helper</h2>
            <p>Convert SQL queries formatted for Moodle db wrappers into runnable queries, with placeholder substitution.</p>

            <form id="sqlhelperform" name="sqlhelperform" method='POST' action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <div class="form-group">
                    <label for="prefix">Prefix:</label>
                    <input type="text" class="form-control" id="prefix" name="prefix" value="mdl_">
                </div>
                <div class="form-group">
                <label for='sql'>SQL:</label>
                <textarea class="form-control" rows="15" id="sql" name="sql" placeholder="SQL to convert"><?php echo $sql; ?></textarea>
                </div>

                <?php
                    if (!empty($placeholders)) {
                        echo "<h4>Placeholders to fill</h4>";
                        // Go through each placeholder and create an input for it.
                        foreach($placeholders as $placeholder) {
                            echo "  <div class='form-group'>
                                        <label for='placeholdervalues[{$placeholder}]'>{$placeholder}</label>
                                        <input type='text' class='form-control' id='placeholdervalues[{$placeholder}]' name='placeholdervalues[{$placeholder}]'>
                                        <br>
                                    <div>";
                        }
                    }
                ?>
                <br>
                <input type="submit" class="btn btn-info" id="submit" name="submit" value="Convert!">
            </form>
        </div>
    </body>
</html>
