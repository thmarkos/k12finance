<?php

// no direct access
defined('_JEXEC') or die;

$mid = $module->id;
$doc =& JFactory::getDocument();

$base = JURI::base();
$mod_path = $base."modules/mod_plusslider/";

//parameters
$width = $params->get('width',940); 
$height = $params->get('height',400); 
$sliderType = $params->get('sliderType','slider'); 
$sliderEasing = $params->get('sliderEasing','easeOutQuad'); 
$displayTime = $params->get('displayTime',4000); 
$speed = $params->get('speed',500); 
$defaultSlide = $params->get('defaultSlide',0); 
$autoPlay = $params->get('autoPlay','true'); 
$keyboardNavigation = $params->get('keyboardNavigation','true'); 
$pauseOnHover = $params->get('pauseOnHover','true'); 
$createArrows = $params->get('createArrows','true'); 
$createPagination = $params->get('createPagination','true'); 		

$active = array(
	$params->get('active1'), $params->get('active2'), $params->get('active3'), $params->get('active4'), $params->get('active5'),
	$params->get('active6'), $params->get('active7'), $params->get('active8'), $params->get('active9'), $params->get('active10'));


$title = array(
	$params->get('title1'), $params->get('title2'), $params->get('title3'), $params->get('title4'), $params->get('title5'),
	$params->get('title6'), $params->get('title7'), $params->get('title8'), $params->get('title9'), $params->get('title10'));

$title_small = array(
	$params->get('title_small1'), $params->get('title_small2'), $params->get('title_small3'), $params->get('title_small4'), $params->get('title_small5'),
	$params->get('title_small6'), $params->get('title_small7'), $params->get('title_small8'), $params->get('title_small9'), $params->get('title_small10'));
	

$image = array(
	$params->get('image1'), $params->get('image2'), $params->get('image3'), $params->get('image4'), $params->get('image5'),
	$params->get('image6'), $params->get('image7'), $params->get('image8'), $params->get('image9'), $params->get('image10'));
	
$url = array(
	$params->get('url1'), $params->get('url2'), $params->get('url3'), $params->get('url4'), $params->get('url5'),
	$params->get('url6'), $params->get('url7'), $params->get('url8'), $params->get('url9'), $params->get('url10'));

$target = array(
	$params->get('target1'), $params->get('target2'), $params->get('target3'), $params->get('target4'), $params->get('target5'),
	$params->get('target6'), $params->get('target7'), $params->get('target8'), $params->get('target9'), $params->get('target10'));

$type = array(
	$params->get('type1'), $params->get('type2'), $params->get('type3'), $params->get('type4'), $params->get('type5'),
	$params->get('type6'), $params->get('type7'), $params->get('type8'), $params->get('type9'), $params->get('type10'));

$video = array(
	$params->get('video1'), $params->get('video2'), $params->get('video3'), $params->get('video4'), $params->get('video5'),
	$params->get('video6'), $params->get('video7'), $params->get('video8'), $params->get('video9'), $params->get('video10'));

$flvfile = array(
	$params->get('flvfile1'), $params->get('flvfile2'), $params->get('flvfile3'), $params->get('flvfile4'), $params->get('flvfile5'),
	$params->get('flvfile6'), $params->get('flvfile7'), $params->get('flvfile8'), $params->get('flvfile9'), $params->get('flvfile10'));

$flv_image = array(
	$params->get('flv_image1'), $params->get('flv_image2'), $params->get('flv_image3'), $params->get('flv_image4'), $params->get('flv_image5'),
	$params->get('flv_image6'), $params->get('flv_image7'), $params->get('flv_image8'), $params->get('flv_image9'), $params->get('flv_image10'));

