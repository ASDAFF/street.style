<?if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();if(!CModule::IncludeModule("iblock"))return;if(!CModule::IncludeModule("catalog"))return;@copy(WIZARD_ABSOLUTE_PATH."/site/services/iblock/xml/".LANGUAGE_ID."/catalog_tpl.xml",WIZARD_ABSOLUTE_PATH."/site/services/iblock/xml/".LANGUAGE_ID."/catalog.xml");CWizardUtil::ReplaceMacros(WIZARD_ABSOLUTE_PATH."/site/services/iblock/xml/".LANGUAGE_ID."/catalog.xml",Array("CATALOG_XML_ID"=>htmlspecialchars("catalog-streetstyle-".ToLower(WIZARD_SITE_ID)),));@copy(WIZARD_ABSOLUTE_PATH."/site/services/iblock/xml/".LANGUAGE_ID."/catalog_prices_tpl.xml",WIZARD_ABSOLUTE_PATH."/site/services/iblock/xml/".LANGUAGE_ID."/catalog_prices.xml");CWizardUtil::ReplaceMacros(WIZARD_ABSOLUTE_PATH."/site/services/iblock/xml/".LANGUAGE_ID."/catalog_prices.xml",Array("CATALOG_XML_ID"=>htmlspecialchars("catalog-streetstyle-".ToLower(WIZARD_SITE_ID)),));$iblockXMLFile=WIZARD_SERVICE_RELATIVE_PATH."/xml/".LANGUAGE_ID."/catalog.xml";$iblockXMLFilePrices=WIZARD_SERVICE_RELATIVE_PATH."/xml/".LANGUAGE_ID."/catalog_prices.xml";$iblockType="catalog";$iblockID=$wizard->GetVar("catalogProductID");$catalogCount=$wizard->GetVar("catalogProductCount");$useSKUPrice=$wizard->GetVar("useSKUPrice")=="Y"?true:false;$permissions=Array("1"=>"X","2"=>"R");$dbGroup=CGroup::GetList($by="",$order="",Array("STRING_ID"=>"sale_administrator"));if($arGroup=$dbGroup->Fetch())
$permissions[$arGroup["ID"]]='W';$dbGroup=CGroup::GetList($by="",$order="",Array("STRING_ID"=>"content_editor"));if($arGroup=$dbGroup->Fetch())
$permissions[$arGroup["ID"]]='W';$dbResultList=CCatalogGroup::GetList(Array(),Array("BASE"=>"Y"));if(!($dbResultList->Fetch()))
{$arFields=Array();$rsLanguage=CLanguage::GetList($by,$order,array());while($arLanguage=$rsLanguage->Fetch())
{WizardServices::IncludeServiceLang("catalog.php",$arLanguage["ID"]);$arFields["USER_LANG"][$arLanguage["ID"]]=GetMessage("WIZ_PRICE_NAME");}
$arFields["BASE"]="Y";$arFields["SORT"]=100;$arFields["NAME"]="BASE";$arFields["USER_GROUP"]=Array(1,2);$arFields["USER_GROUP_BUY"]=Array(1,2);CCatalogGroup::Add($arFields);}
$iblockID=WizardServices_SERGELAND::ImportIBlockFromXML($iblockXMLFile,$iblockType,$iblockID,WIZARD_SITE_ID,$permissions,WIZARD_INSTALL_DEMO_DATA);if($iblockID<1)
return;WizardServices_SERGELAND::ImportIBlockFromXML($iblockXMLFilePrices,false,$iblockID,WIZARD_SITE_ID,$permissions,WIZARD_INSTALL_DEMO_DATA);if(WIZARD_INSTALL_DEMO_DATA)
{$arConditions=array();$dbProductDiscounts=CCatalogDiscount::GetList();while($arProductDiscounts=$dbProductDiscounts->Fetch())
$arConditions[$arProductDiscounts["SITE_ID"]][]=$arProductDiscounts["CONDITIONS"];$defCurrency="EUR";$dbSite=CSite::GetByID(WIZARD_SITE_ID);if($arSite=$dbSite->Fetch())
$lang=$arSite["LANGUAGE_ID"];if($lang=="ru")$defCurrency="RUB";elseif($lang=="en")$defCurrency="USD";WizardServices::IncludeServiceLang("catalog.php",$lang);$dbSect=CIBlockSection::GetList(Array(),Array("IBLOCK_ID"=>$iblockID,"CODE"=>"women"));if($arSect=$dbSect->Fetch())
$sofasSectId=$arSect["ID"];$arF=Array("SITE_ID"=>WIZARD_SITE_ID,"ACTIVE"=>"Y","RENEWAL"=>"N","NAME"=>GetMessage("WIZ_DISCOUNT"),"SORT"=>100,"MAX_DISCOUNT"=>0,"VALUE_TYPE"=>"P","VALUE"=>10,"CURRENCY"=>$defCurrency,"CONDITIONS"=>Array("CLASS_ID"=>"CondGroup","DATA"=>Array("All"=>"AND","True"=>"True"),"CHILDREN"=>Array("0"=>Array("CLASS_ID"=>"CondIBSection","DATA"=>Array("logic"=>"Equal","value"=>+$sofasSectId)))));if(!in_array(serialize($arF["CONDITIONS"]),$arConditions[WIZARD_SITE_ID]))
CCatalogDiscount::Add($arF);$dbProperty=CIBlockProperty::GetList(Array(),Array("IBLOCK_ID"=>$iblockID,"CODE"=>"SPECIALOFFER"));if($arProperty=$dbProperty->GetNext())
$specialofferId=$arProperty["ID"];$dbProperty=CIBlockProperty::GetPropertyEnum($specialofferId);if($arProperty=$dbProperty->GetNext())
$specialofferEnumId=$arProperty["ID"];$arF=Array("SITE_ID"=>WIZARD_SITE_ID,"ACTIVE"=>"Y","RENEWAL"=>"N","NAME"=>GetMessage("WIZ_DISCOUNT2"),"SORT"=>200,"MAX_DISCOUNT"=>0,"VALUE_TYPE"=>"P","VALUE"=>20,"CURRENCY"=>$defCurrency,"CONDITIONS"=>Array("CLASS_ID"=>"CondGroup","DATA"=>Array("All"=>"AND","True"=>"True"),"CHILDREN"=>Array("0"=>Array("CLASS_ID"=>"CondIBProp:$iblockID:$specialofferId","DATA"=>Array("logic"=>"Equal","value"=>+$specialofferEnumId)))));if(!in_array(serialize($arF["CONDITIONS"]),$arConditions[WIZARD_SITE_ID]))
CCatalogDiscount::Add($arF);$dbProperty=CIBlockProperty::GetList(Array(),Array("IBLOCK_ID"=>$iblockID,"CODE"=>"ACTION"));if($arProperty=$dbProperty->GetNext())
$specialofferId=$arProperty["ID"];$dbProperty=CIBlockProperty::GetPropertyEnum($specialofferId);if($arProperty=$dbProperty->GetNext())
$specialofferEnumId=$arProperty["ID"];$arF=Array("SITE_ID"=>WIZARD_SITE_ID,"ACTIVE"=>"Y","RENEWAL"=>"N","NAME"=>GetMessage("WIZ_DISCOUNT3"),"SORT"=>300,"MAX_DISCOUNT"=>0,"VALUE_TYPE"=>"P","VALUE"=>15,"CURRENCY"=>$defCurrency,"CONDITIONS"=>Array("CLASS_ID"=>"CondGroup","DATA"=>Array("All"=>"AND","True"=>"True"),"CHILDREN"=>Array("0"=>Array("CLASS_ID"=>"CondIBProp:$iblockID:$specialofferId","DATA"=>Array("logic"=>"Equal","value"=>+$specialofferEnumId)))));if(!in_array(serialize($arF["CONDITIONS"]),$arConditions[WIZARD_SITE_ID]))
CCatalogDiscount::Add($arF);$res=CIBlockElement::GetList(Array(),Array("IBLOCK_ID"=>$iblockID,"XML_ID"=>18));if($ar_fields=$res->GetNext())
$elementId=$ar_fields["ID"];$arF=Array("SITE_ID"=>WIZARD_SITE_ID,"ACTIVE"=>"Y","RENEWAL"=>"N","NAME"=>GetMessage("WIZ_DISCOUNT4"),"SORT"=>400,"MAX_DISCOUNT"=>0,"VALUE_TYPE"=>"P","VALUE"=>40,"CURRENCY"=>$defCurrency,"CONDITIONS"=>Array("CLASS_ID"=>"CondGroup","DATA"=>Array("All"=>"AND","True"=>"True"),"CHILDREN"=>Array("0"=>Array("CLASS_ID"=>"CondIBElement","DATA"=>Array("logic"=>"Equal","value"=>+$elementId)))));if(!in_array(serialize($arF["CONDITIONS"]),$arConditions[WIZARD_SITE_ID]))
CCatalogDiscount::Add($arF);$res=CIBlockElement::GetList(Array(),Array("IBLOCK_ID"=>$iblockID,"XML_ID"=>79));if($ar_fields=$res->GetNext())
$elementId=$ar_fields["ID"];$arF=Array("SITE_ID"=>WIZARD_SITE_ID,"ACTIVE"=>"Y","RENEWAL"=>"N","NAME"=>GetMessage("WIZ_DISCOUNT5"),"SORT"=>500,"MAX_DISCOUNT"=>0,"VALUE_TYPE"=>"P","VALUE"=>35,"CURRENCY"=>$defCurrency,"CONDITIONS"=>Array("CLASS_ID"=>"CondGroup","DATA"=>Array("All"=>"AND","True"=>"True"),"CHILDREN"=>Array("0"=>Array("CLASS_ID"=>"CondIBElement","DATA"=>Array("logic"=>"Equal","value"=>+$elementId)))));if(!in_array(serialize($arF["CONDITIONS"]),$arConditions[WIZARD_SITE_ID]))
CCatalogDiscount::Add($arF);$res=CIBlockElement::GetList(Array(),Array("IBLOCK_ID"=>$iblockID,"XML_ID"=>110));if($ar_fields=$res->GetNext())
$elementId=$ar_fields["ID"];$arF=Array("SITE_ID"=>WIZARD_SITE_ID,"ACTIVE"=>"Y","RENEWAL"=>"N","NAME"=>GetMessage("WIZ_DISCOUNT6"),"SORT"=>600,"MAX_DISCOUNT"=>0,"VALUE_TYPE"=>"P","VALUE"=>70,"CURRENCY"=>$defCurrency,"CONDITIONS"=>Array("CLASS_ID"=>"CondGroup","DATA"=>Array("All"=>"AND","True"=>"True"),"CHILDREN"=>Array("0"=>Array("CLASS_ID"=>"CondIBElement","DATA"=>Array("logic"=>"Equal","value"=>+$elementId)))));if(!in_array(serialize($arF["CONDITIONS"]),$arConditions[WIZARD_SITE_ID]))
CCatalogDiscount::Add($arF);$dbSect=CIBlockSection::GetList(Array(),Array("IBLOCK_ID"=>$iblockID,"CODE"=>"teenage-collection"));if($arSect=$dbSect->Fetch())
$sofasSectId=$arSect["ID"];$arF=Array("SITE_ID"=>WIZARD_SITE_ID,"ACTIVE"=>"Y","RENEWAL"=>"N","NAME"=>GetMessage("WIZ_DISCOUNT7"),"SORT"=>100,"MAX_DISCOUNT"=>0,"VALUE_TYPE"=>"P","VALUE"=>50,"CURRENCY"=>$defCurrency,"CONDITIONS"=>Array("CLASS_ID"=>"CondGroup","DATA"=>Array("All"=>"AND","True"=>"True"),"CHILDREN"=>Array("0"=>Array("CLASS_ID"=>"CondIBSection","DATA"=>Array("logic"=>"Equal","value"=>+$sofasSectId)))));if(!in_array(serialize($arF["CONDITIONS"]),$arConditions[WIZARD_SITE_ID]))
if($discount_id=CCatalogDiscount::Add($arF))
{CCatalogDiscountCoupon::Add(array("DISCOUNT_ID"=>$discount_id,"ACTIVE"=>"Y","ONE_TIME"=>"N","COUPON"=>ToUpper(WIZARD_SITE_ID)."-SERGELAND50"));}}
$arPropsToDelete=array("CML2_BAR_CODE","CML2_ARTICLE","CML2_ATTRIBUTES","CML2_TRAITS","CML2_BASE_UNIT","CML2_TAXES","MORE_PHOTO","FILES","CML2_MANUFACTURER",);foreach($arPropsToDelete as $code)
{$dbProperty=CIBlockProperty::GetList(Array(),Array("IBLOCK_ID"=>$iblockID,"CODE"=>$code));if($arProperty=$dbProperty->GetNext())
CIBlockProperty::Delete($arProperty["ID"]);}
$iblock=new CIBlock;$arFields=Array("ACTIVE"=>"Y","FIELDS"=>array('IBLOCK_SECTION'=>array('IS_REQUIRED'=>'Y','DEFAULT_VALUE'=>'',),'ACTIVE'=>array('IS_REQUIRED'=>'Y','DEFAULT_VALUE'=>'Y',),'ACTIVE_FROM'=>array('IS_REQUIRED'=>'N','DEFAULT_VALUE'=>'',),'ACTIVE_TO'=>array('IS_REQUIRED'=>'N','DEFAULT_VALUE'=>'',),'SORT'=>array('IS_REQUIRED'=>'N','DEFAULT_VALUE'=>'',),'NAME'=>array('IS_REQUIRED'=>'Y','DEFAULT_VALUE'=>'',),'PREVIEW_PICTURE'=>array('IS_REQUIRED'=>'N','DEFAULT_VALUE'=>array('FROM_DETAIL'=>'Y','DELETE_WITH_DETAIL'=>'Y','UPDATE_WITH_DETAIL'=>'Y','SCALE'=>'Y','WIDTH'=>'300','HEIGHT'=>'200','IGNORE_ERRORS'=>'Y','METHOD'=>'resample','COMPRESSION'=>95,),),'PREVIEW_TEXT_TYPE'=>array('IS_REQUIRED'=>'Y','DEFAULT_VALUE'=>'html',),'PREVIEW_TEXT'=>array('IS_REQUIRED'=>'N','DEFAULT_VALUE'=>'',),'DETAIL_PICTURE'=>array('IS_REQUIRED'=>'N','DEFAULT_VALUE'=>array('SCALE'=>'Y','WIDTH'=>'1200','HEIGHT'=>'800','IGNORE_ERRORS'=>'Y','METHOD'=>'resample','COMPRESSION'=>95,),),'DETAIL_TEXT_TYPE'=>array('IS_REQUIRED'=>'Y','DEFAULT_VALUE'=>'html',),'DETAIL_TEXT'=>array('IS_REQUIRED'=>'N','DEFAULT_VALUE'=>'',),'XML_ID'=>array('IS_REQUIRED'=>'N','DEFAULT_VALUE'=>'',),'CODE'=>array('IS_REQUIRED'=>'N','DEFAULT_VALUE'=>array('UNIQUE'=>'Y','TRANSLITERATION'=>'Y','TRANS_LEN'=>100,'TRANS_CASE'=>'L','TRANS_SPACE'=>'-','TRANS_OTHER'=>'-','TRANS_EAT'=>'Y','USE_GOOGLE'=>'Y',),),'TAGS'=>array('IS_REQUIRED'=>'N','DEFAULT_VALUE'=>'',),'SECTION_NAME'=>array('IS_REQUIRED'=>'Y','DEFAULT_VALUE'=>'',),'SECTION_PICTURE'=>array('IS_REQUIRED'=>'N','DEFAULT_VALUE'=>array('FROM_DETAIL'=>'N','DELETE_WITH_DETAIL'=>'N','UPDATE_WITH_DETAIL'=>'N','SCALE'=>'Y','WIDTH'=>'200','HEIGHT'=>'300','IGNORE_ERRORS'=>'N','METHOD'=>'resample','COMPRESSION'=>95,),),'SECTION_DESCRIPTION_TYPE'=>array('IS_REQUIRED'=>'Y','DEFAULT_VALUE'=>'html',),'SECTION_DESCRIPTION'=>array('IS_REQUIRED'=>'N','DEFAULT_VALUE'=>'',),'SECTION_DETAIL_PICTURE'=>array('IS_REQUIRED'=>'N','DEFAULT_VALUE'=>array('SCALE'=>'Y','WIDTH'=>'400','HEIGHT'=>'600','IGNORE_ERRORS'=>'Y','METHOD'=>'resample','COMPRESSION'=>95,),),'SECTION_XML_ID'=>array('IS_REQUIRED'=>'N','DEFAULT_VALUE'=>'',),'SECTION_CODE'=>array('IS_REQUIRED'=>'Y','DEFAULT_VALUE'=>array('UNIQUE'=>'Y','TRANSLITERATION'=>'Y','TRANS_LEN'=>100,'TRANS_CASE'=>'L','TRANS_SPACE'=>'-','TRANS_OTHER'=>'-','TRANS_EAT'=>'Y','USE_GOOGLE'=>'Y',),),),);$iblock->Update($iblockID,$arFields);$arLanguages=Array();$arProperty=Array();$rsLanguage=CLanguage::GetList($by,$order,array());while($arLanguage=$rsLanguage->Fetch())
$arLanguages[]=$arLanguage["LID"];$arUserFields=array("UF_BROWSER_TITLE","UF_KEYWORDS","UF_META_DESCRIPTION");foreach($arUserFields as $userField)
{$arLabelNames=Array();foreach($arLanguages as $languageID)
{WizardServices::IncludeServiceLang("property_names.php",$languageID);$arLabelNames[$languageID]=GetMessage($userField);}
$arProperty["EDIT_FORM_LABEL"]=$arLabelNames;$arProperty["LIST_COLUMN_LABEL"]=$arLabelNames;$arProperty["LIST_FILTER_LABEL"]=$arLabelNames;$dbRes=CUserTypeEntity::GetList(Array(),Array("ENTITY_ID"=>'IBLOCK_'.$iblockID.'_SECTION',"FIELD_NAME"=>$userField));if($arRes=$dbRes->Fetch())
{$userType=new CUserTypeEntity();$userType->Update($arRes["ID"],$arProperty);}}
$arProperty=Array();$dbProperty=CIBlockProperty::GetList(Array(),Array("IBLOCK_ID"=>$iblockID));while($arProp=$dbProperty->Fetch())
$arProperty[$arProp["CODE"]]=$arProp["ID"];$dbSite=CSite::GetByID(WIZARD_SITE_ID);if($arSite=$dbSite->Fetch())
$lang=$arSite["LANGUAGE_ID"];if(strlen($lang)<=0)
$lang="ru";WizardServices::IncludeServiceLang("catalog.php",$lang);$tabs.='edit1--#--'.GetMessage("WZD_OPTION_CATALOG_1").'--,'.'--ACTIVE--#--'.GetMessage("WZD_OPTION_CATALOG_2").'--,'.'--PROPERTY_'.$arProperty["NEWPRODUCT"].'--#--'.GetMessage("WZD_OPTION_CATALOG_3").'--,'.'--PROPERTY_'.$arProperty["SALELEADER"].'--#--'.GetMessage("WZD_OPTION_CATALOG_4").'--,'.'--PROPERTY_'.$arProperty["ACTION"].'--#--'.GetMessage("WZD_OPTION_CATALOG_5").'--,'.'--PROPERTY_'.$arProperty["SPECIALOFFER"].'--#--'.GetMessage("WZD_OPTION_CATALOG_6").'--,'.'--SORT--#--'.GetMessage("WZD_OPTION_CATALOG_7").'--,'.'--NAME--#--'.GetMessage("WZD_OPTION_CATALOG_8").'--,'.'--PROPERTY_'.$arProperty["ARTNUMBER"].'--#--'.GetMessage("WZD_OPTION_CATALOG_9").'--,'.'--PROPERTY_'.$arProperty["COLOR"].'--#--'.GetMessage("WZD_OPTION_CATALOG_10").'--,'.'--PROPERTY_'.$arProperty["SIZE"].'--#--'.GetMessage("WZD_OPTION_CATALOG_11").'--,'.'--PROPERTY_'.$arProperty["COUNTRY"].'--#--'.GetMessage("WZD_OPTION_CATALOG_12").'--,'.'--PROPERTY_'.$arProperty["BREND"].'--#--'.GetMessage("WZD_OPTION_CATALOG_13").'--,'.'--PROPERTY_'.$arProperty["COLLECTION"].'--#--'.GetMessage("WZD_OPTION_CATALOG_14").'--,'.'--edit1_csection2--#----'.GetMessage("WZD_OPTION_CATALOG_39").'--,'.'--PROPERTY_'.$arProperty["COUNTDOWN_PRODUCT_DAY"].'--#--'.GetMessage("WZD_OPTION_CATALOG_40").'--,'.'--edit1_csection1--#----'.GetMessage("WZD_OPTION_CATALOG_41").'--,'.'--PROPERTY_'.$arProperty["COUNTDOWN_SALE_FROM"].'--#--'.GetMessage("WZD_OPTION_CATALOG_42").'--,'.'--PROPERTY_'.$arProperty["COUNTDOWN_SALE_TO"].'--#--'.GetMessage("WZD_OPTION_CATALOG_43").'--,'.'--edit1_csection3--#----'.GetMessage("WZD_OPTION_CATALOG_44").'--,'.'--DETAIL_PICTURE--#--'.GetMessage("WZD_OPTION_CATALOG_15").'--,'.'--PROPERTY_'.$arProperty["MORE_PHOTO"].'--#--'.GetMessage("WZD_OPTION_CATALOG_45").'--,'.'--CATALOG--#--'.GetMessage("WZD_OPTION_CATALOG_16").'--;'.'--cedit1--#--'.GetMessage("WZD_OPTION_CATALOG_17").'--,'.'--DETAIL_TEXT--#--'.GetMessage("WZD_OPTION_CATALOG_18").'--,'.'--PREVIEW_TEXT--#--'.GetMessage("WZD_OPTION_CATALOG_19").'--;'.'--cedit1--#--'.GetMessage("WZD_OPTION_CATALOG_37").'--,'.'--PROPERTY_'.$arProperty["RECOMMEND"].'--#--'.GetMessage("WZD_OPTION_CATALOG_38").'--;';if($useSKUPrice){$tabs.='--edit8--#--'.GetMessage("WZD_OPTION_CATALOG_20").'--,'.'--OFFERS--#--'.GetMessage("WZD_OPTION_CATALOG_21").'--;';}
$tabs.='--edit14--#--'.GetMessage("WZD_OPTION_CATALOG_46").'--,'.'--PROPERTY_'.$arProperty["TITLE"].'--#--'.GetMessage("WZD_OPTION_CATALOG_47").'--,'.'--PROPERTY_'.$arProperty["KEYWORDS"].'--#--'.GetMessage("WZD_OPTION_CATALOG_48").'--,'.'--PROPERTY_'.$arProperty["META_DESCRIPTION"].'--#--'.GetMessage("WZD_OPTION_CATALOG_49").'--,'.'--IPROPERTY_TEMPLATES_ELEMENT_META_TITLE--#--'.GetMessage("WZD_OPTION_CATALOG_50").'--,'.'--IPROPERTY_TEMPLATES_ELEMENT_META_KEYWORDS--#--'.GetMessage("WZD_OPTION_CATALOG_51").'--,'.'--IPROPERTY_TEMPLATES_ELEMENT_META_DESCRIPTION--#--'.GetMessage("WZD_OPTION_CATALOG_52").'--,'.'--IPROPERTY_TEMPLATES_ELEMENT_PAGE_TITLE--#--'.GetMessage("WZD_OPTION_CATALOG_53").'--,'.'--IPROPERTY_TEMPLATES_ELEMENTS_PREVIEW_PICTURE--#----'.GetMessage("WZD_OPTION_CATALOG_54").'--,'.'--IPROPERTY_TEMPLATES_ELEMENT_PREVIEW_PICTURE_FILE_ALT--#--'.GetMessage("WZD_OPTION_CATALOG_55").'--,'.'--IPROPERTY_TEMPLATES_ELEMENT_PREVIEW_PICTURE_FILE_TITLE--#--'.GetMessage("WZD_OPTION_CATALOG_56").'--,'.'--IPROPERTY_TEMPLATES_ELEMENT_PREVIEW_PICTURE_FILE_NAME--#--'.GetMessage("WZD_OPTION_CATALOG_57").'--,'.'--IPROPERTY_TEMPLATES_ELEMENTS_DETAIL_PICTURE--#----'.GetMessage("WZD_OPTION_CATALOG_58").'--,'.'--IPROPERTY_TEMPLATES_ELEMENT_DETAIL_PICTURE_FILE_ALT--#--'.GetMessage("WZD_OPTION_CATALOG_59").'--,'.'--IPROPERTY_TEMPLATES_ELEMENT_DETAIL_PICTURE_FILE_TITLE--#--'.GetMessage("WZD_OPTION_CATALOG_60").'--,'.'--IPROPERTY_TEMPLATES_ELEMENT_DETAIL_PICTURE_FILE_NAME--#--'.GetMessage("WZD_OPTION_CATALOG_61").'--,'.'--SEO_ADDITIONAL--#----'.GetMessage("WZD_OPTION_CATALOG_62").'--,'.'--TAGS--#--'.GetMessage("WZD_OPTION_CATALOG_63").'--;'.'--cedit2--#--'.GetMessage("WZD_OPTION_CATALOG_22").'--,'.'--XML_ID--#--'.GetMessage("WZD_OPTION_CATALOG_23").'--,'.'--SECTIONS--#--'.GetMessage("WZD_OPTION_CATALOG_24").'--;'.'--';CUserOptions::SetOption("form","form_element_".$iblockID,array('tabs'=>$tabs));CUserOptions::SetOption("form","form_section_".$iblockID,array('tabs'=>'edit1--#--'.GetMessage("WZD_OPTION_CATALOG_25").'--,'.'--ACTIVE--#--'.GetMessage("WZD_OPTION_CATALOG_26").'--,'.'--SORT--#--'.GetMessage("WZD_OPTION_CATALOG_27").'--,'.'--IBLOCK_SECTION_ID--#--'.GetMessage("WZD_OPTION_CATALOG_28").'--,'.'--NAME--#--'.GetMessage("WZD_OPTION_CATALOG_29").'--,'.'--CODE--#--'.GetMessage("WZD_OPTION_CATALOG_30").'--,'.'--UF_BROWSER_TITLE--#--'.GetMessage("WZD_OPTION_CATALOG_31").'--,'.'--UF_TITLE_H1--#----'.GetMessage("WZD_OPTION_CATALOG_32").'--,'.'--UF_KEYWORDS--#--'.GetMessage("WZD_OPTION_CATALOG_33").'--,'.'--UF_META_DESCRIPTION--#--'.GetMessage("WZD_OPTION_CATALOG_34").'--,'.'--PICTURE--#--'.GetMessage("WZD_OPTION_CATALOG_35").'--,'.'--DESCRIPTION--#--'.GetMessage("WZD_OPTION_CATALOG_36").'--;'.'--',));$res=CIBlock::GetByID($iblockID);$ar_res=$res->GetNext();$iblockType=$ar_res["IBLOCK_TYPE_ID"];$dbPriceType=CCatalogGroup::GetList(array(),array("BASE"=>"Y"));if($arPriceType=$dbPriceType->Fetch())
$priceTypeName=$arPriceType["NAME"];else $priceTypeName="BASE";CUserOptions::SetOption("list","tbl_iblock_list_".md5($iblockType.".".$iblockID),array('columns'=>'DETAIL_PICTURE,'.'PROPERTY_'.$arProperty["ARTNUMBER"].','.'NAME,'.'CATALOG_GROUP_'.$arPriceType["ID"].','.'PROPERTY_'.$arProperty["SPECIALOFFER"].','.'PROPERTY_'.$arProperty["NEWPRODUCT"].','.'PROPERTY_'.$arProperty["SALELEADER"],'by'=>'timestamp_x','order'=>'desc','page_size'=>'20',));CUserOptions::SetOption("list","tbl_product_admin_".md5($iblockType.".".$iblockID),array('columns'=>'DETAIL_PICTURE,'.'NAME,'.'CATALOG_GROUP_'.$arPriceType["ID"].','.'ACTIVE,'.'SORT,'.'CATALOG_QUANTITY,'.'ID,'.'TIMESTAMP_X','by'=>'timestamp_x','order'=>'desc','page_size'=>'20',));$_SESSION["CATALOG_PRODUCT_ID"]=$iblockID;COption::SetOptionInt("streetstyle","catalogProductID",$iblockID,false,WIZARD_SITE_ID);COption::SetOptionInt("streetstyle","catalogProductCount",$catalogCount,false,WIZARD_SITE_ID);CWizardUtil::ReplaceMacros($_SERVER["DOCUMENT_ROOT"].BX_PERSONAL_ROOT."/templates/".WIZARD_SITE_ID."_detail_streetstyle_sl/header.php",array("IBLOCK_TYPE"=>$iblockType,"IBLOCK_ID"=>$iblockID,"PRICE_CODE"=>$priceTypeName,));WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."/catalog/",array("SITE_DIR"=>WIZARD_SITE_DIR,"IBLOCK_TYPE"=>$iblockType,"IBLOCK_ID"=>$iblockID,"PRICE_CODE"=>$priceTypeName,"PAGE_ELEMENT_COUNT"=>$catalogCount,));WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."/actions/",array("SITE_DIR"=>WIZARD_SITE_DIR,"IBLOCK_TYPE"=>$iblockType,"IBLOCK_ID"=>$iblockID,"PRICE_CODE"=>$priceTypeName,"PAGE_ELEMENT_COUNT"=>$catalogCount,));WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."/new/",array("SITE_DIR"=>WIZARD_SITE_DIR,"IBLOCK_TYPE"=>$iblockType,"IBLOCK_ID"=>$iblockID,"PRICE_CODE"=>$priceTypeName,"PAGE_ELEMENT_COUNT"=>$catalogCount,));WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."/sale/",array("SITE_DIR"=>WIZARD_SITE_DIR,"IBLOCK_TYPE"=>$iblockType,"IBLOCK_ID"=>$iblockID,"PRICE_CODE"=>$priceTypeName,"PAGE_ELEMENT_COUNT"=>$catalogCount,));?>