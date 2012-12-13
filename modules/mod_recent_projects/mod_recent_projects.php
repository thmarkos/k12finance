<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

require_once dirname(__FILE__).'/helper.php';

$maintitle = $params->get('maintitle', '');
$maindescription = $params->get('maindescription', '');
$mainlink = $params->get('mainlink', '');
$btntext = $params->get('btntext', '');

$title1 = $params->get('title1', '');
$description1 = $params->get('description1', '');
$image1 = $params->get('image1', '');
$linktype1 = $params->get('linktype1', '');
$link1 = $params->get('link1', '');
$externallink1 = $params->get('externallink1', '');
$articlelink1 = $params->get('articlelink1', '');

$title2 = $params->get('title2', '');
$description2 = $params->get('description2', '');
$image2 = $params->get('image2', '');
$linktype2 = $params->get('linktype2', '');
$link2 = $params->get('link2', '');
$externallink2 = $params->get('externallink2', '');
$articlelink2 = $params->get('articlelink2', '');

$title3 = $params->get('title3', '');
$description3 = $params->get('description3', '');
$image3 = $params->get('image3', '');
$linktype3 = $params->get('linktype3', '');
$link3 = $params->get('link3', '');
$externallink3 = $params->get('externallink3', '');
$articlelink3 = $params->get('articlelink3', '');


$doc =& JFactory::getDocument();
$timthumb = JURI::base().'templates/'.JFactory::getApplication()->getTemplate().'/lib/timthumb.php';

$css = '';
$doc->addStyleDeclaration($css);


?>
<div class=" recent_projects">
    <ul class="items">
        <li>
            <h3 class="title-square"><?php echo $maintitle; ?></h3>
            <p><?php echo $maindescription; ?></p>
            <a href="<?php echo make_link('internal', $mainlink, 0, 0); ?>" class="readon"><?php echo $btntext; ?></a>
        </li>
    
        <li>
        	<?php if($linktype1 != 'none') { ?>
            <a href="<?php echo make_link($linktype1, $link1, $externallink1, $articlelink1); ?>">
            <?php } ?>
                <img src="<?php echo $timthumb; ?>?src=<?php echo JURI::base().$image1; ?>&amp;w=220&amp;h=163&amp;q=90" alt="" />
                
                <div class="details">
                    <h4><?php echo $title1; ?></h4>
                    <p><?php echo $description1; ?></p>
                </div>
            <?php if($linktype1 != 'none') { ?>
            </a>
            <?php } ?>
        </li>
        <li>
        <?php if($linktype2 != 'none') { ?>
	        <a href="<?php echo make_link($linktype2, $link2, $externallink2, $articlelink2); ?>">
        <?php } ?>
        	<img src="<?php echo $timthumb; ?>?src=<?php echo JURI::base().$image2; ?>&amp;w=220&amp;h=163&amp;q=90" alt="" />
        
            <div class="details">
                <h4><?php echo $title2; ?></h4>
                <p><?php echo $description2; ?></p>
            </div>
            
            <?php if($linktype2 != 'none') { ?>
            </a>
            <?php } ?>
        </li>
        <li class="last">
        <?php if($linktype3 != 'none') { ?>
        	<a href="<?php echo make_link($linktype3, $link3, $externallink3, $articlelink3); ?>">
        <?php } ?>
	        <img src="<?php echo $timthumb; ?>?src=<?php echo JURI::base().$image3; ?>&amp;w=220&amp;h=163&amp;q=90" alt="" />
            <div class="details">
                <h4><?php echo $title3; ?></h4>
                <p><?php echo $description3; ?></p>
            </div>
            
            <?php if($linktype3 != 'none') { ?>
            </a>
            <?php } ?>
        </li>
    </ul>
</div>
<div class="clear"></div>   
