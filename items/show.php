<?php 
$paths = $this->getAssetPaths();
//print_r($paths, true);
$themePath = $paths[0][0];
$this->addHelperPath($themePath . '/helpers', 'Emiglio_View_Helper_');

?>
<?php 
$title = metadata($item, array('Dublin Core', 'Title'));
?>
<?php echo head(array('title' => $title, 'bodyclass' => 'items show')); ?>

<h1><?php echo $title; ?></h1>

<div id="primary">

    <?php
        $files = $item->Files; 
        if (! empty($files)): ?>
    <div class='mud image'>
    <?php echo file_markup($files[0], array('imageSize' => 'fullsize')); ?>
    </div>
    <?php endif; ?>
    <div class='element'>
    <?php echo metadata($item, array('Dublin Core', 'Description')); ?>
    </div>

    <div class='element'>
    <?php $wikipediaUrl = metadata($item, array('MUD Elements', 'Wikipedia Url')); ?>
    <?php if(empty($wikipediaUrl)): ?>
    <p><a class='big button' href='http://en.wikipedia.org/wiki/Wikipedia:Starting_an_article'>Create a wikipedia page for <?php echo $title; ?></a>
    </p>
    <p>
    It looks like there is no Wikipedia entry for <?php echo $title; ?>. If there really is one, please <a href="<?php echo url('contact'); ?>">let us know</a>! If not, please foster greater exchange of our shared cultural heritage by creating a Wikipedia page for it.
    </p>
    <?php else:?>
    <a href="<?php echo $wikipediaUrl; ?>">Wikipedia Page for <?php echo $title; ?></a>
    <?php endif; ?>
    </div>
    <div class='element'>
    <?php $dbpediaUri = metadata($item, array('MUD Elements', 'DBpedia Uri')); ?>
    <?php if (!empty($dbpediaUri)):?>
    <p>Linked Open Data URI: <?php echo url_to_link($dbpediaUri); ?>.</p>
    <p>Learn more about <a href='http://en.wikipedia.org/wiki/Linked_data'>Linked Open Data</a>.
    </p>
    <?php endif;?>
    </div>
    <div>

    <p>
    If something is wrong or missing on this page of the internet, please add a correction <?php echo CorrectionsPlugin::correctionLink($item, 'here');?>. 
    </p>
    
    <p>
    You can also <a href="<?php echo url('contact'); ?>">let me know</a> about thoughts and ideas about this site.
    </p>    
    </div>
    <?php echo get_specific_plugin_hook_output('Geolocation', 'public_items_show', array('view' => $this, 'item' => $item) ); ?>

</div><!-- end primary -->

<div id="secondary">

    <div class="mud title">
    <?php $link = metadata($item, array('MUD Elements', 'WEBURL')); ?>
    <?php if (empty($link)): ?>
    <?php echo metadata($item, array('Dublin Core', 'Title'));?>
    <?php else: ?>
    <a href="<?php echo $link  ?>"><?php echo metadata($item, array('Dublin Core', 'Title'));?></a>
    <?php endif; ?>
    </div>
    <div>
    <?php $socialMedia = new SocialMediaElementSetPlugin; 
    echo $socialMedia->socialMediaLinksList($item);
    ?>
    </div>
    <div class="address">
        <?php echo metadata($item, array('MUD Elements', 'ADDRESS')); ?>
        <br />
        <?php echo metadata($item, array('MUD Elements', 'CITY')); ?>,
        <?php echo metadata($item, array('MUD Elements', 'STATE')); ?>
        <?php echo metadata($item, array('MUD Elements', 'ZIP')); ?>
    </div>
    <div class="phone">
        <?php echo metadata($item, array('MUD Elements', 'PHONE')); ?>
    </div>
    <div>
    <p>Something wrong or missing? Please submit a <?php echo CorrectionsPlugin::correctionLink($item, 'correction');?>
    </div>
    <?php echo get_specific_plugin_hook_output('FacetByMetadata', 'public_items_show', array('view' => $this, 'item' => $item) ); ?>
    
    
</div><!-- end secondary -->

<ul class="item-pagination navigation">
    <li id="previous-item" class="previous"><?php echo link_to_previous_item_show(); ?></li>
    <li id="next-item" class="next"><?php echo link_to_next_item_show(); ?></li>
</ul>

<?php echo foot();
