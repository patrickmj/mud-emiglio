<?php echo head(array('title' => metadata($item, array('Dublin Core', 'Title')), 'bodyclass' => 'items show')); ?>

<h1><?php echo metadata($item, array('Dublin Core', 'Title')); ?></h1>

<div id="primary">

    <div class='mud image'>
    <?php echo file_markup($item->Files[0], array('imageSize' => 'fullsize')); ?>
    </div>
    <div class='element'>
    <?php echo metadata($item, array('Dublin Core', 'Description')); ?>
    </div>

    <div class='element'>
    <?php $wikipediaUrl = metadata($item, array('MUD Elements', 'Wikipedia Url')); ?>
    <?php if(empty($wikipediaUrl)): ?>
    <p>
    There is no Wikipedia entry for this museum. Please foster greater exchange of our shared cultural heritage by <a href='http://en.wikipedia.org/wiki/Wikipedia:Starting_an_article'>creating a wikipedia page</a> for it.
    </p>
    <?php else:?>
    <a href="<?php echo $wikipediaUrl; ?>">Wikipedia Page</a>
    <?php endif; ?>
    </div>
    <div class='element'>
    <?php $dbpediaUri = metadata($item, array('MUD Elements', 'DBpedia Uri')); ?>
    <?php if(!empty($dbpediaUri)):?>
    <p>Linked Open Data URI: <?php echo url_to_link($dbpediaUri); ?>.</p>
    <p>Learn more about <a href='http://en.wikipedia.org/wiki/Linked_data'>Linked Open Data</a>.
    </p>
    <?php endif;?>
    </div>
    

</div><!-- end primary -->

<div id="secondary">

    <div class="link">
    <a href="<?php echo metadata($item, array('MUD Elements', 'WEBURL')); ?>"><?php echo metadata($item, array('Dublin Core', 'Title'));?></a>
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
<?php fire_plugin_hook('public_items_show', array('view' => $this, 'item' => $item)); ?>
    
    
</div><!-- end secondary -->

<ul class="item-pagination navigation">
    <li id="previous-item" class="previous"><?php echo link_to_previous_item_show(); ?></li>
    <li id="next-item" class="next"><?php echo link_to_next_item_show(); ?></li>
</ul>

<?php echo foot();
