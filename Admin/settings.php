<?php
    $opt = get_option('ratingcaptain_data');
    $options = json_decode($opt);
    if(isset($_POST['submit_rating'])){
        if(isset($_POST['ratingcaptain_api'])) $options->api_key = $_POST['ratingcaptain_api'];
        if(isset($_POST['ratingcaptain_send_products'])) $options->send_products = true;
        else $options->send_products = false;
        update_option('ratingcaptain_data', json_encode($options));
    }
?>

<?php /*if(class_exists('WooCommerce')){ */?>
<form action="" method="POST">
    <h1 class="mt-2">RatingCaptain settings</h1>
    <table class="form-table">
        <tbody>
        <tr>
            <th scope="row">
                <label for="ratingcaptain_api">Wpisz kod API do twojej strony</label>
            </th>
            <td>
                <input type="text" name="ratingcaptain_api" class="regular-text" value="<?php echo $options->api_key ?>">
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="ratingcaptain_api">Wysyłać mail z oceną produktów?</label>
            </th>
            <td>
                <input type="checkbox" name="ratingcaptain_send_products" class="regular-text" <?php if(isset($options->send_products) && $options->send_products){?> checked <?php }?> >
            </td>
        </tr>
        </tbody>
    </table>

    <p class="submit">
        <input type="submit" name="submit_rating" id="submit" class="button button-primary" value="Zapisz zmiany">
    </p>
</form>
<?php /*} else{*/?><!--
<p>Nie znaleziono aktywnego sklepu WooCommerce. Przykro nam ta wtyczka nie będzie działać poprawnie.</p>
--><?php /*}*/?>
