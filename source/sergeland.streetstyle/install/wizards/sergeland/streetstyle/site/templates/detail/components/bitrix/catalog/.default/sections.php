<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if( is_array($_REQUEST[$arParams["FILTER_NAME"]]) ):

global ${$arParams["FILTER_NAME"]};
foreach(${$arParams["FILTER_NAME"]} as &$VALUE) 
	if(empty($VALUE)) $VALUE = false;
	
$SECTION_TITLE = "";
$arResult["VARIABLES"]["SECTION_ID"] = intval($_REQUEST[$arParams["FILTER_NAME"]]["SECTION_ID"]) > 0 ? intval($_REQUEST[$arParams["FILTER_NAME"]]["SECTION_ID"]) : "";

$HEAD = $_REQUEST[$arParams["FILTER_NAME"]]["~FIELD_NAME"];
unset($_REQUEST[$arParams["FILTER_NAME"]]["~FIELD_NAME"]);

if(!isset($arParams["CACHE_TIME"]))
	$arParams["CACHE_TIME"] = 36000000;

if($arResult["VARIABLES"]["SECTION_ID"] > 0)
{
	$obCache = new CPHPCache;
	if ($obCache->StartDataCache($arParams["CACHE_TIME"], $arResult["VARIABLES"]["SECTION_ID"], SITE_ID."/bitrix/catalog/"))
	{
			$arFilterSection = array(
										"IBLOCK_ID"=>$arParams["IBLOCK_ID"],
										"IBLOCK_ACTIVE"=>"Y",
										"ACTIVE"=>"Y",
										"GLOBAL_ACTIVE"=>"Y",
										"ID"=>$arResult["VARIABLES"]["SECTION_ID"],
									);

			$rsSection = CIBlockSection::GetList(array(), $arFilterSection);
			$arSection = $rsSection->GetNext();
			
			if($arSection)
				$SECTION_TITLE = GetMessage("SERGELAND_STREETSTYLE_SECTION").$arSection["NAME"];	
			else
			{
				unset($_REQUEST[$arParams["FILTER_NAME"]]["SECTION_ID"]);
				$obCache->AbortDataCache();
			}		   
			$obCache->EndDataCache(array("SECTION_TITLE"=>$SECTION_TITLE));
	}
	else
	{ 
			$cache = $obCache->GetVars(); 
			$SECTION_TITLE = $cache["SECTION_TITLE"];
	} 	
}
else unset($_REQUEST[$arParams["FILTER_NAME"]]["SECTION_ID"]);

if( array_key_exists("!PROPERTY_NEWPRODUCT", $_REQUEST[$arParams["FILTER_NAME"]]) )		
	$SECTION_TITLE = GetMessage("SERGELAND_STREETSTYLE_NEWPRODUCT");
if( array_key_exists("!PROPERTY_ACTION", $_REQUEST[$arParams["FILTER_NAME"]]) )		
	$SECTION_TITLE = GetMessage("SERGELAND_STREETSTYLE_ACTION");
if( array_key_exists("!PROPERTY_SPECIALOFFER", $_REQUEST[$arParams["FILTER_NAME"]]) )		
	$SECTION_TITLE = GetMessage("SERGELAND_STREETSTYLE_SPECIALOFFER");


foreach($_REQUEST[$arParams["FILTER_NAME"]] as $key=>$NAME)
{
   $i++;   
   if($key == "SECTION_ID" && strlen($SECTION_TITLE) > 0)
		$SECTION_TITLE = " - ".$SECTION_TITLE;	
   if($key != "SECTION_ID")   
		$HEAD .= (strlen($HEAD) > 0) ? (strlen($NAME) > 0) ? " - ".$NAME : $NAME : $NAME;	
   if(count($_REQUEST[$arParams["FILTER_NAME"]]) == $i)
		$HEAD .= $SECTION_TITLE;
}
$APPLICATION->SetTitle(GetMessage("SERGELAND_STREETSTYLE_FILTER").$HEAD);
?>
<div class="catalog">
	<h4><span><?=GetMessage("SERGELAND_STREETSTYLE_FILTER")?><?=$HEAD?></span></h4>
</div>
<?
$sort_field = $arParams["ELEMENT_SORT_FIELD"];
$sort_order = $arParams["ELEMENT_SORT_ORDER"];

$obCache = new CPHPCache;
if ($obCache->StartDataCache($arParams["CACHE_TIME"], "CATALOG_PRICE", SITE_ID."/bitrix/catalog/"))
{
		CModule::IncludeModule("catalog");
		if(count($arParams["PRICE_CODE"]) > 1)
			 $dbPriceType = CCatalogGroup::GetList(array("SORT" => "ASC"), array("BASE" => "Y"));
		else $dbPriceType = CCatalogGroup::GetList(array("SORT" => "ASC"), array("NAME" => $arParams["PRICE_CODE"][0]));
		
		$arPriceType = $dbPriceType->Fetch();
		
		$CATALOG_PRICE = "CATALOG_PRICE_".$arPriceType["ID"];		
		$obCache->EndDataCache(array("CATALOG_PRICE"=>$CATALOG_PRICE));
}
else
{ 
		$cache = $obCache->GetVars(); 
		$CATALOG_PRICE = $cache["CATALOG_PRICE"];
}

$priseURL = "desc&price";
$nameURL  = "asc&name";

if(array_key_exists("desc", $_REQUEST))
{
	$sort_order = "desc";	
	if(array_key_exists("price", $_REQUEST))
	{
		$sort_field = $CATALOG_PRICE;
		$priseURL   = "asc&price";
	}		
	if(array_key_exists("name", $_REQUEST))
	{
		$sort_field = "NAME";
		$nameURL    = "asc&name";
	}		
}

if(array_key_exists("asc", $_REQUEST))
{
	$sort_order = "asc";	
	if(array_key_exists("price", $_REQUEST))
	{
		$sort_field = $CATALOG_PRICE;
		$priseURL   = "desc&price";
	}		
	if(array_key_exists("name", $_REQUEST))
	{
		$sort_field = "NAME";
		$nameURL    = "desc&name";	
	}	
}

$APPLICATION->IncludeComponent(
	"bitrix:catalog.section",
	"",
	Array(
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
        "OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
		"SHOW_ALL_WO_SECTION" => "Y",		
		"ELEMENT_SORT_FIELD" => $sort_field,
		"ELEMENT_SORT_ORDER" => $sort_order,
		"ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
		"ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
		"PROPERTY_CODE" => $arParams["LIST_PROPERTY_CODE"],
		"META_KEYWORDS" => $arParams["LIST_META_KEYWORDS"],
		"META_DESCRIPTION" => $arParams["LIST_META_DESCRIPTION"],
		"BROWSER_TITLE" => $arParams["LIST_BROWSER_TITLE"],
		"INCLUDE_SUBSECTIONS" => "Y",
		"BASKET_URL" => $arParams["BASKET_URL"],
		"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
		"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
		"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
		"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
		"PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
		"FILTER_NAME" => $arParams["FILTER_NAME"],
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_FILTER" => $arParams["CACHE_FILTER"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		"SET_TITLE" => $arParams["SET_TITLE"],
		"SET_STATUS_404" => $arParams["SET_STATUS_404"],
		"DISPLAY_COMPARE" => $arParams["USE_COMPARE"],
		"PAGE_ELEMENT_COUNT" => $arParams["PAGE_ELEMENT_COUNT"],
		"LINE_ELEMENT_COUNT" => $arParams["LINE_ELEMENT_COUNT"],
		"PRICE_CODE" => $arParams["PRICE_CODE"],
		"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
		"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
		"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
		"PRICE_VAT_SHOW_VALUE" => $arParams["PRICE_VAT_SHOW_VALUE"],
		"USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
		"QUANTITY_FLOAT" => $arParams["QUANTITY_FLOAT"],
		"PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],
		"DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
		"DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],
		"PAGER_TITLE" => $arParams["PAGER_TITLE"],
		"PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
		"PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
		"PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
		"PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
		"PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
		"SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
		"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
		"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
		"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
		'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
		'CURRENCY_ID' => $arParams['CURRENCY_ID'],
		'HIDE_NOT_AVAILABLE' => $arParams["HIDE_NOT_AVAILABLE"],
		//Template parameters
		"LINK_IBLOCK_ID" => $arParams["LINK_IBLOCK_ID"],
		"LINK_PROPERTY_SID" => $arParams["LINK_PROPERTY_SID"],
	),
	$component
);?>
<div class="catalog-element-sort"><?=GetMessage("SERGELAND_STREETSTYLE_SORT")?> <a href="<?=$APPLICATION->GetCurPageParam($priseURL, array("asc", "desc", "name", "price"));?>"><?=GetMessage("SERGELAND_STREETSTYLE_SORT_PRICE")?></a> | <a href="<?=$APPLICATION->GetCurPageParam($nameURL, array("asc", "desc", "name", "price"));?>"><?=GetMessage("SERGELAND_STREETSTYLE_SORT_NAME")?></a></div>
<?else:?>
<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.section.list",
	"main",
	Array(
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		"COUNT_ELEMENTS" => $arParams["SECTION_COUNT_ELEMENTS"],
		"TOP_DEPTH" => $arParams["SECTION_TOP_DEPTH"],
		"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"]
	),
	$component
);
?>
<?endif?>