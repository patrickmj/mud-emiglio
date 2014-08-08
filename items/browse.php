<?php
queue_js_file('mobile');
queue_js_url("http://maps.google.com/maps/api/js?sensor=false");
queue_js_file('map');


$css = "
            #map_browse {
                height: 436px;
            }
            .balloon {width:400px !important; font-size:1.2em;}
            .balloon .title {font-weight:bold;margin-bottom:1.5em;}
            .balloon .title, .balloon .description {float:left; width: 220px;margin-bottom:1.5em;}
            .balloon img {float:right;display:block;}
            .balloon .view-item {display:block; float:left; clear:left; font-weight:bold; text-decoration:none;}
            #map-links a {
                display:block;
            }
            #search_block {
                clear: both;
            }";
queue_css_string($css);


$pageTitle = __('Browse Museums');
echo head(array('title'=>$pageTitle, 'bodyclass' => 'items browse'));
?>

<?php 
//set up range of ids for geolocation
$range = array();
?>
<div id="primary" class="browse">

    <h1><?php echo $pageTitle;?> <?php echo __('(%s total)', $total_results); ?></h1>

    <?php echo item_search_filters(); ?>

    <ul class="items-nav navigation" id="secondary-nav">
        <?php echo public_nav_items(); ?>
    </ul>

    <?php echo pagination_links(); ?>

    <?php foreach (loop('items') as $item): ?>
        <?php $range[] = $item->id; ?>
        <div class="item hentry">
            <div class="item-meta">

            <h2><?php echo link_to_item(metadata($item, array('Dublin Core', 'Title'), array('class'=>'permalink'))); ?></h2>

            <?php if (metadata($item, 'has thumbnail')): ?>
                <div class="item-img">
                <?php echo link_to_item(item_image('square_thumbnail')); ?>
                </div>
            <?php endif; ?>

            <?php if ($text = metadata($item, array('Item Type Metadata', 'Text'), array('snippet'=>250))): ?>
                <div class="item-description">
                <p><?php echo $text; ?></p>
                </div>
            <?php elseif ($description = metadata($item, array('Dublin Core', 'Description'), array('snippet'=>250))): ?>
                <div class="item-description">
                <?php echo $description; ?>
                </div>
            <?php endif; ?>

            <?php if (metadata($item, 'has tags')): ?>
                <div class="tags"><p><strong><?php echo __('Tags'); ?>: </strong>
                <?php echo tag_string('items'); ?></p>
                </div>
            <?php endif; ?>

            <?php echo fire_plugin_hook('public_items_browse_each', array('view' => $this, 'item' =>$item)); ?>

            </div><!-- end class="item-meta" -->
        </div><!-- end class="item hentry" -->
    <?php endforeach; ?>
    
    <?php echo fire_plugin_hook('public_items_browse', array('items'=>$items, 'view' => $this)); ?>

    <?php echo pagination_links(); ?>
</div>
<div id="secondary">

    <form action="<?php echo url('mud/search'); ?>" method="post">
        <input type="submit" class="big button" style="width: 100%" value="Find me a museum!" />
        <br />
        <div id='zip' style='float:left'>
            <label for='zip'>Zip code</label>
            <input type="text" id="zip" name="zip" size="6" />
        </div>
        <div class='locate' style='float: left; font-weight: bold; cursor: pointer;'>
            <p id='locate'>Locate me</p>
            <p id='located' style='display: none'>Found you!</p>
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



<?php 
$range = implode(',', $range);
$params = array(
        'controller'     => 'map',
        'action'         => 'browse',
        'module'         => 'geolocation',
        'output'         => 'kml',
        'only_map_items' => true,
        'range'          => $range
        );

?>
    <?php if (! empty($range)): ?>
    <div>
    <?php echo $this->googleMap('map_browse', array( 'params' => $params)); ?>
    
    </div>    
    <?php endif; ?>
</div><!-- end primary -->

<?php echo foot(); ?>
