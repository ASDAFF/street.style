<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if (StrLen($arResult["ERROR_MESSAGE"]) <= 0):?>	
	<form method="post" action="<?=POST_FORM_ACTION_URI?>" name="basket_form">
		<?
		if($arResult["ShowReady"]=="Y")
			include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/basket_items.php");

		if($arResult["ShowDelay"]=="Y")
			include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/basket_items_delay.php");

		if($arResult["ShowNotAvail"]=="Y")			
			include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/basket_items_notavail.php");
			
		if ($arResult["ShowSubscribe"] == "Y")
			include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/basket_items_subscribe.php");
		?>
	</form>
<?else:
	ShowError($arResult["ERROR_MESSAGE"]);
endif?>