<?php
/*  API Call for country List */

$url = "https://restcountries.eu/rest/v2/all";
$ch = curl_init();
curl_setopt ($ch, CURLOPT_URL, $url);
curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 5);
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
$contents = curl_exec($ch);

$json = json_decode($contents, true); // decode the JSON into an associative array

$cArray = [];
foreach ($json as $key => $value) {
    $cArray[$value['name']] =$value['alpha2Code'];
}
?>

<div class="pj_box">
    <div class="cloader">Loading Data..</div>
    <p class="meta-options pj_field">
        <label for="pj_author">Country</label>
        <input type="hidden" name="post_title" value="Brazil" id="title" spellcheck="true" autocomplete="off">
        <select name="pj_country" id="pj_country" required="required">
            <?php
                echo '<option value="">Select Country</option>';
                $cVal = esc_attr( get_post_meta( get_the_ID(), 'pj_country', true ) );
                foreach ($cArray as $key => $value) {
                    if($cVal == $value){ $check = 'selected'; } else {  $check = ' '; }
                    echo '<option value='.$value.' '.$check.' name='.$key.'>'.$key.'</option>';
                }
            ?>
        </select>
    </p>

    <p class="meta-options pj_field">
        <label for="pj_author">TopLevel Domain</label>
        <input id="pj_topLevelDomain"  type="text" name="pj_topLevelDomain" value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'pj_topLevelDomain', true ) ); ?>">
    </p>

    <p class="meta-options pj_field">
        <label for="pj_author">Alpha2 Code</label>
        <input id="pj_alpha2Code"  type="text" name="pj_alpha2Code" value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'pj_alpha2Code', true ) ); ?>">
    </p>

    <p class="meta-options pj_field">
        <label for="pj_author">Alpha3 Code</label>
        <input id="pj_alpha3Code"  type="text" name="pj_alpha3Code" value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'pj_alpha3Code', true ) ); ?>">
    </p>

    <p class="meta-options pj_field">
        <label for="pj_author">Calling Codes</label>
        <input id="pj_callingCodes"  type="text" name="pj_callingCodes" value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'pj_callingCodes', true ) ); ?>">
    </p>

    <p class="meta-options pj_field">
        <label for="pj_author">Timezones</label>
        <input id="pj_timezones"  type="text" name="pj_timezones" value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'pj_timezones', true ) ); ?>">
    </p>

    <p class="meta-options pj_field">
        <label for="pj_author">Currencies</label>
        <input id="pj_currencies"  type="text" name="pj_currencies" value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'pj_currencies', true ) ); ?>">
    </p>

    <p class="meta-options pj_field">
        <label for="pj_author">Country Flag</label>
        <input id="pj_countryflag"  type="text" name="pj_countryflag" value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'pj_countryflag', true ) ); ?>" readonly="readonly">
        <img  id="pj_countryflagImg" src ="<?php echo esc_attr( get_post_meta( get_the_ID(), 'pj_countryflag', true ) ); ?>" height="50">
    </p>

    <p class="meta-options pj_field">
        <label for="pj_author">Publishing Time</label>
        <input id="pj_publishingtime"  type="text" name="pj_publishingtime" value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'pj_publishingtime', true ) ); ?>">
    </p>
</div>