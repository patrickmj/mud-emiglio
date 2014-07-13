<?php 
queue_js_file('mobile');

?>

<?php echo head(array('bodyid'=>'home')); ?>

<?php if ($homepageText = get_theme_option('Homepage Text')): ?>
    <!-- Homepage Text -->
    <div id="intro">
    <?php echo $homepageText; ?>
    </div>
<?php endif; ?>

<div id="primary">
<p>There are over 35,000 museums and historical societies in the United States! 
Click on one below, <a href="<?php echo url('items/browse'); ?>">browse around</a>,
or use the search form to find the next one you will visit.
    
    <div class='carousel'>
        <?php echo $this->shortcodes('[carousel showtitles=true order=random num=20]')?>
    </div>
    
    <div>
        <?php echo $this->shortcodes('[geolocation order=random]')?>
    </div>
</div>
<div id="secondary">
    <form action="mud/search" method="post">
        <input type="submit" class="big button" style="width: 100%; font-size: 24px;" value="Find me a museum!" />
        <br />
        <div id='zip' style='float:left'>
            <label for='zip'>Zip code</label>
            <input type="text" id="zip" name="zip" size="6" />
        </div>
        <div class='locate' style='float: left; font-weight: bold; cursor: pointer;'>
            <p id='locate'>Locate me</p>
            <p id='located' style='display: none'>Found you!</p>
        </div>
        <div id='radius' style='float:right; font-weight: bold;'>
            <label for='geolocation-radius'>How close?</label>
            <br />
            <select name="geolocation-radius">
                <option value="5">5 Miles</option>
                <option value="20">20 Miles</option>
                <option value="50">50 Miles</option>
                <option value="100">100 Miles</option>
            </select>
        </div>
        <div style='clear: both'>
            <label for='type'>Museum type</label>
            <select name="type" style="width: 100%">
                <option value="">Any</option>
                <option value="BOT">Arboretums, Botanitcal Gardens, And Nature Centers</option>
                <option value="ART">Art Museums</option>
                <option value="CMU">Children's Museums</option>
                <option value="GMU">Uncategorized of General Museums</option>
                <option value="HSC">Historical Societies, Historic Preservation</option>
                <option value="HST">History Museums</option>
                <option value="NAT">Natural History and Natural Science Museums</option>
                <option value="SCI">Science and Technology Museums and Planetariums</option>
                <option value="ZAW">Zoos, Aquariums, and Wildlife Conservation</option>
            </select>
        </div>
        <input type='hidden' id='lat' name='geolocation-latitude' />
        <input type='hidden' id='lng' name='geolocation-longitude' />
        <input type='hidden' id='mobile-located' name='mobile-located' value='0' />
    </form>
</div>

<?php echo foot(); ?>
