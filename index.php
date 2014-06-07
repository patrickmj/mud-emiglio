<?php echo head(array('bodyid'=>'home')); ?>

<?php if ($homepageText = get_theme_option('Homepage Text')): ?>
    <!-- Homepage Text -->
    <div id="intro">
    <?php echo $homepageText; ?>
    </div>
<?php endif; ?>

<div id="primary">
<?php echo $this->shortcodes('[carousel]')?>
</div>
<div id="secondary">
    <form action="mud/search" method="post">
        <input type="submit" class="big button" style="width: 100%" value="Find me a museum!" />
        <br />
        <div id='zip' style='float:left'>
            <label for='zip'>Zip code</label>
            <input type="text" name="zip" size="6" />
        </div>
        <div id='radius' style='float:right'>
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
            </select>
        </div>
        
    </form>
</div>

<?php echo foot(); ?>
