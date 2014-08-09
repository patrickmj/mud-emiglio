<?php
/**
 * Omeka
 * 
 * @copyright Copyright 2007-2012 Roy Rosenzweig Center for History and New Media
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 */

/**
 * Show the currently-active filters for a search/browse.
 * 
 * @package Omeka\View\Helper
 */
class Emiglio_View_Helper_ItemSearchFilters extends Zend_View_Helper_Abstract
{
    /**
     * Get a list of the currently-active filters for item browse/search.
     *
     * @param array $params Optional array of key-value pairs to use instead of
     *  reading the current params from the request.
     * @return string HTML output
     */
    public function itemSearchFilters(array $params = null)
    {
        if ($params === null) {
            $request = Zend_Controller_Front::getInstance()->getRequest(); 
            $requestArray = $request->getParams();
        } else {
            $requestArray = $params;
        }
        
        $db = get_db();
        $displayArray = array();
        foreach ($requestArray as $key => $value) {
            $filter = $key;
            if($value != null) {
                $displayValue = null;
                switch ($key) {
                    case 'type':
                        $filter = 'Item Type';
                        $itemType = $db->getTable('ItemType')->find($value);
                        if ($itemType) {
                            $displayValue = $itemType->name;
                        }
                        break;
                    
                    case 'collection':
                        $collection = $db->getTable('Collection')->find($value);
                        if ($collection) {
                            $displayValue = strip_formatting(
                                metadata(
                                    $collection,
                                    array('Dublin Core', 'Title'),
                                    array('no_escape' => true)
                                )
                            );
                        }
                        break;

                    case 'user':
                        $user = $db->getTable('User')->find($value);
                        if ($user) {
                            $displayValue = $user->name;
                        }
                        break;

                    case 'public':
                    case 'featured':
                        $displayValue = ($value == 1 ? __('Yes') : $displayValue = __('No'));
                        break;
                        
                    case 'search':
                    case 'tags':
                    case 'range':
                        $displayValue = $value;
                        break;
                }
                if ($displayValue) {
                    $displayArray[$filter] = $displayValue;
                }
            }
        }

        $displayArray = apply_filters('item_search_filters', $displayArray, array('request_array' => $requestArray));
        
        // Advanced needs a separate array from $displayValue because it's
        // possible for "Specific Fields" to have multiple values due to 
        // the ability to add fields.
        if(array_key_exists('advanced', $requestArray)) {
            $advancedArray = array();
            foreach ($requestArray['advanced'] as $i => $row) {
                if (!$row['element_id'] || !$row['type']) {
                    continue;
                }
                $elementID = $row['element_id'];
                $elementDb = $db->getTable('Element')->find($elementID);
                $element = __($elementDb->name);
                $type = __($row['type']);
                switch ($element) {
                    case 'LOCALE4':
                        $element = 'Locale Type';
                        if (isset($row['terms'])) {
                            $row['terms'] = $this->filterLocale4($row['terms']);
                        }
                        break;
                        
                    case 'INCOMECD':
                        $element = 'Income Range';
                        if (isset($row['terms'])) {
                            $row['terms'] = $this->filterIncomeCd($row['terms']);
                        }
                        break;
                    case 'DISCIPL':
                        $element = 'Discipline';
                        if (isset($row['terms'])) {
                            $row['terms'] = $this->filterDiscipline($row['terms']);
                        }
                        break;
                    default:
                        $element = ucwords(strtolower($element));
                }
                $advancedValue = $element . ' ' . $type;
                if (isset($row['terms'])) {
                    $advancedValue .= ' "' . $row['terms'] . '"';
                }
                $advancedArray[$i] = $advancedValue;
            }
        }

        $html = '';
        if (!empty($displayArray) || !empty($advancedArray)) {
            $html .= '<div id="item-filters">';
            $html .= '<ul>';
            foreach($displayArray as $name => $query) {
                $html .= '<li class="' . $name . '">' . html_escape(ucfirst($name)) . ': ' . html_escape($query) . '</li>';
            }
            if(!empty($advancedArray)) {
                foreach($advancedArray as $j => $advanced) {
                    $html .= '<li class="advanced">' . html_escape($advanced) . '</li>';
                }
            }
            $html .= '</ul>';
            $html .= '</div>';
        }
        return $html;
    }
    
    /**
     * Copy-pasted (mostly) from MudPlugin.php
     * @param unknown_type $value
     */
    protected function filterLocale4($value)
    {
        switch($value) {
            case '1':
                return 'City';
                break;
            case '2':
                return 'Suburb';
                break;
            case '3':
                return 'Town';
                break;
            case '4':
                return 'Rural';
                break;
            default:
                return 'Unknown';
                break;
        }
    }
    
    protected function filterIncomeCd($value)
    {
        switch($value) {
            case '0':
                return '$0';
                break;
            case '1':
                return '$1 to $9,000';
                break;
            case '2':
                return '$10,000 to $24,999';
                break;
            case '3':
                return '$25,000 to $99,999';
                break;
            case '4':
                return '$100,000 to $499,999';
                break;
            case '5':
                return '$500,000 to $999,999';
                break;
            case '6':
                return '$1,000,000 to $4,999,999';
                break;
            case '7':
                return '$5,000,000 to $9,999,999';
                break;
            case '8':
                return '$10,000,000 to $49,999,999';
                break;
            case '9';
                return '$50,000,000 to greater';
                break;
            default:
                return 'Unknown';
                break;
        }
    }
    
    public function filterDiscipline($value)
    {
        switch($value) {
            case 'ART':
                return "Art Museums";
                break;
            case 'BOT':
                return "Arboretums, Botanitcal Gardens, And Nature Centers";
                break;
            case 'CMU':
                return "Children's Museums";
                break;
            case 'GMU':
                return "Uncategorized or General Museums";
                break;
            case 'HSC':
                return "Historical Societies, Historic Preservation";
                break;
            case 'HST':
                return "History Museums";
                break;
            case 'NAT':
                return "Natural History and Natural Science Museums";
                break;
            case 'SCI':
                return "Science and Technology Museums and Planetariums";
                break;
            case 'ZAW':
                return "Zoos, Aquariums, and Wildlife Conservation";
                break;
        }
    }
    
}
