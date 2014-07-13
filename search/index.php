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


$pageTitle = __('Search Omeka ') . __('(%s total)', $total_results);
echo head(array('title' => $pageTitle, 'bodyclass' => 'search'));
$searchRecordTypes = get_search_record_types();
?>
<h1><?php echo $pageTitle; ?></h1>
<?php echo search_filters(); ?>
<?php if ($total_results): ?>
<?php echo pagination_links(); ?>
<?php $range = array(); ?>
<div id='primary'>
<table id="search-results">
    <thead>
        <tr>
            <th><?php echo __('Title');?></th>
        </tr>
    </thead>
    <tbody>
        <?php $filter = new Zend_Filter_Word_CamelCaseToDash; ?>
        <?php foreach (loop('search_texts') as $searchText): ?>
        
        <?php $record = get_record_by_id($searchText['record_type'], $searchText['record_id']); ?>
        <?php $recordType = $searchText['record_type']; ?>
        <?php set_current_record($recordType, $record); ?>
        <?php $range[] = $record->id; ?>
        <tr class="<?php echo strtolower($filter->filter($recordType)); ?>">
            <td>
                <?php if ($recordImage = record_image($recordType, 'square_thumbnail')): ?>
                    <?php echo link_to($record, 'show', $recordImage, array('class' => 'image')); ?>
                <?php endif; ?>
                <a href="<?php echo record_url($record, 'show'); ?>"><?php echo $searchText['title'] ? $searchText['title'] : '[Unknown]'; ?></a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>

<div id='secondary'>
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
    
    <div>
    <?php echo $this->googleMap('map_browse', array( 'params' => $params)); ?>
    
    </div>    
    <?php endif; ?>
</div>
<?php echo pagination_links(); ?>
<?php else: ?>
<div id="no-results">
    <p><?php echo __('Your query returned no results.');?></p>
</div>
<?php endif; ?>
<?php echo foot(); ?>
