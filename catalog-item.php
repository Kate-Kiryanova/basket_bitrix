<? // component bitrix:catalog.element file template.php ?>

<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogSectionComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 * @var string $templateFolder
 */

$this->setFrameMode(true);

$templateLibrary = array('popup', 'fx');
$currencyList = '';

if (!empty($arResult['CURRENCIES']))
{
	$templateLibrary[] = 'currency';
	$currencyList = CUtil::PhpToJSObject($arResult['CURRENCIES'], false, true, true);
}

$templateData = array(
	'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
	'TEMPLATE_LIBRARY' => $templateLibrary,
	'CURRENCIES' => $currencyList,
	'ITEM' => array(
		'ID' => $arResult['ID'],
		'IBLOCK_ID' => $arResult['IBLOCK_ID'],
		'OFFERS_SELECTED' => $arResult['OFFERS_SELECTED'],
		'JS_OFFERS' => $arResult['JS_OFFERS']
	)
);
unset($currencyList, $templateLibrary);

$mainId = $this->GetEditAreaId($arResult['ID']);
$itemIds = array(
	'ID' => $mainId,
	'DISCOUNT_PERCENT_ID' => $mainId.'_dsc_pict',
	'STICKER_ID' => $mainId.'_sticker',
	'BIG_SLIDER_ID' => $mainId.'_big_slider',
	'BIG_IMG_CONT_ID' => $mainId.'_bigimg_cont',
	'SLIDER_CONT_ID' => $mainId.'_slider_cont',
	'OLD_PRICE_ID' => $mainId.'_old_price',
	'PRICE_ID' => $mainId.'_price',
	'DISCOUNT_PRICE_ID' => $mainId.'_price_discount',
	'PRICE_TOTAL' => $mainId.'_price_total',
	'SLIDER_CONT_OF_ID' => $mainId.'_slider_cont_',
	'QUANTITY_ID' => $mainId.'_quantity',
	'QUANTITY_DOWN_ID' => $mainId.'_quant_down',
	'QUANTITY_UP_ID' => $mainId.'_quant_up',
	'QUANTITY_MEASURE' => $mainId.'_quant_measure',
	'QUANTITY_LIMIT' => $mainId.'_quant_limit',
	'BUY_LINK' => $mainId.'_buy_link',
	'ADD_BASKET_LINK' => $mainId.'_add_basket_link',
	'BASKET_ACTIONS_ID' => $mainId.'_basket_actions',
	'NOT_AVAILABLE_MESS' => $mainId.'_not_avail',
	'COMPARE_LINK' => $mainId.'_compare_link',
	'TREE_ID' => $mainId.'_skudiv',
	'DISPLAY_PROP_DIV' => $mainId.'_sku_prop',
	'DISPLAY_MAIN_PROP_DIV' => $mainId.'_main_sku_prop',
	'OFFER_GROUP' => $mainId.'_set_group_',
	'BASKET_PROP_DIV' => $mainId.'_basket_prop',
	'SUBSCRIBE_LINK' => $mainId.'_subscribe',
	'TABS_ID' => $mainId.'_tabs',
	'TAB_CONTAINERS_ID' => $mainId.'_tab_containers',
	'SMALL_CARD_PANEL_ID' => $mainId.'_small_card_panel',
	'TABS_PANEL_ID' => $mainId.'_tabs_panel'
);
$obName = $templateData['JS_OBJ'] = 'ob'.preg_replace('/[^a-zA-Z0-9_]/', 'x', $mainId);

?>

	<div class="panel" id="<?=$itemIds['ID']?>">
		<div class="container">

			<div class="descr-row__btn-panel">

				<div class="product-item-amount">
					<div class="product-item-amount-field-container" data-id="<?=$arResult['ID'];?>" id="<?=$itemIds['BASKET_ACTIONS_ID']?>">

											<span
												class="product-item-amount-field-btn-minus js-quantity-change js-item-minus js-minus"
												data-role="minus"
											>
												<svg class="icon i i-minus">
													<use xlink:href="#i-minus"></use>
												</svg>
											</span>

						<input
							class="product-item-amount-field js-quantity-field"
							name="quantity"
							type="num"
							placeholder="1"
							data-role="input-number"
							value="1"
							onkeypress="return false"
							onkeyup="return false"
						/>

						<span
							class="product-item-amount-field-btn-plus js-quantity-change js-item-plus js-plus"
							data-role="pluse"
						>
												<svg class="icon i i-plus">
													<use xlink:href="#i-plus"></use>
												</svg>
											</span>

					</div>
				</div>

				<a
					class="btn btn--blue  <?=((float)$actualItem['CATALOG_QUANTITY'] > 0 && $actualItem['CATALOG_QUANTITY_TRACE'] === 'Y' && $arResult['~CATALOG_CAN_BUY_ZERO'] === 'N') ? '' : 'disabled';?> js-item-add-basket"
					id="<?=$itemIds['ADD_BASKET_LINK']?>"
					href="javascript:void(0);"
					data-id="<?=$arResult['ID'];?>"
					data-action="BUY"
				>
					<?=$arParams['MESS_BTN_ADD_TO_BASKET']?>
				</a>

			</div>

		</div>
	</div>

<?
$jsParams = array(
	'CONFIG' => array(
		'USE_CATALOG' => $arResult['CATALOG'],
		'SHOW_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
		'SHOW_PRICE' => !empty($arResult['ITEM_PRICES']),
		'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'] === 'Y',
		'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'] === 'Y',
		'USE_PRICE_COUNT' => $arParams['USE_PRICE_COUNT'],
		'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE'],
		'MAIN_PICTURE_MODE' => $arParams['DETAIL_PICTURE_MODE'],
		'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
		'SHOW_CLOSE_POPUP' => $arParams['SHOW_CLOSE_POPUP'] === 'Y',
		'SHOW_MAX_QUANTITY' => $arParams['SHOW_MAX_QUANTITY'],
		'RELATIVE_QUANTITY_FACTOR' => $arParams['RELATIVE_QUANTITY_FACTOR'],
		'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
		'USE_STICKERS' => true,
		'USE_SUBSCRIBE' => $showSubscribe,
		'SHOW_SLIDER' => $arParams['SHOW_SLIDER'],
		'SLIDER_INTERVAL' => $arParams['SLIDER_INTERVAL'],
		'ALT' => $alt,
		'TITLE' => $title,
		'MAGNIFIER_ZOOM_PERCENT' => 200,
		'USE_ENHANCED_ECOMMERCE' => $arParams['USE_ENHANCED_ECOMMERCE'],
		'DATA_LAYER_NAME' => $arParams['DATA_LAYER_NAME'],
		'BRAND_PROPERTY' => !empty($arResult['DISPLAY_PROPERTIES'][$arParams['BRAND_PROPERTY']])
			? $arResult['DISPLAY_PROPERTIES'][$arParams['BRAND_PROPERTY']]['DISPLAY_VALUE']
			: null
	),
	'VISUAL' => $itemIds,
	'PRODUCT_TYPE' => $arResult['PRODUCT']['TYPE'],
	'PRODUCT' => array(
		'ID' => $arResult['ID'],
		'ACTIVE' => $arResult['ACTIVE'],
		'PICT' => reset($arResult['MORE_PHOTO']),
		'NAME' => $arResult['~NAME'],
		'SUBSCRIPTION' => true,
		'ITEM_PRICE_MODE' => $arResult['ITEM_PRICE_MODE'],
		'ITEM_PRICES' => $arResult['ITEM_PRICES'],
		'ITEM_PRICE_SELECTED' => $arResult['ITEM_PRICE_SELECTED'],
		'ITEM_QUANTITY_RANGES' => $arResult['ITEM_QUANTITY_RANGES'],
		'ITEM_QUANTITY_RANGE_SELECTED' => $arResult['ITEM_QUANTITY_RANGE_SELECTED'],
		'ITEM_MEASURE_RATIOS' => $arResult['ITEM_MEASURE_RATIOS'],
		'ITEM_MEASURE_RATIO_SELECTED' => $arResult['ITEM_MEASURE_RATIO_SELECTED'],
		'SLIDER_COUNT' => $arResult['MORE_PHOTO_COUNT'],
		'SLIDER' => $arResult['MORE_PHOTO'],
		'CAN_BUY' => $arResult['CAN_BUY'],
		'CHECK_QUANTITY' => $arResult['CHECK_QUANTITY'],
		'QUANTITY_FLOAT' => is_float($arResult['ITEM_MEASURE_RATIOS'][$arResult['ITEM_MEASURE_RATIO_SELECTED']]['RATIO']),
		'MAX_QUANTITY' => $arResult['CATALOG_QUANTITY'],
		'STEP_QUANTITY' => $arResult['ITEM_MEASURE_RATIOS'][$arResult['ITEM_MEASURE_RATIO_SELECTED']]['RATIO'],
		'CATEGORY' => $arResult['CATEGORY_PATH']
	),
	'BASKET' => array(
		'ADD_PROPS' => $arParams['ADD_PROPERTIES_TO_BASKET'] === 'Y',
		'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
		'PROPS' => $arParams['PRODUCT_PROPS_VARIABLE'],
		'EMPTY_PROPS' => $emptyProductProperties,
		'BASKET_URL' => $arParams['BASKET_URL'],
		'ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
		'BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE'].$arResult['ID']
	)
);
unset($emptyProductProperties);
?>
	<script>
		BX.message({
			ECONOMY_INFO_MESSAGE: '<?=GetMessageJS('CT_BCE_CATALOG_ECONOMY_INFO2')?>',
			TITLE_ERROR: '<?=GetMessageJS('CT_BCE_CATALOG_TITLE_ERROR')?>',
			TITLE_BASKET_PROPS: '<?=GetMessageJS('CT_BCE_CATALOG_TITLE_BASKET_PROPS')?>',
			BASKET_UNKNOWN_ERROR: '<?=GetMessageJS('CT_BCE_CATALOG_BASKET_UNKNOWN_ERROR')?>',
			BTN_SEND_PROPS: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_SEND_PROPS')?>',
			BTN_MESSAGE_BASKET_REDIRECT: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_BASKET_REDIRECT')?>',
			BTN_MESSAGE_CLOSE: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_CLOSE')?>',
			BTN_MESSAGE_CLOSE_POPUP: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_CLOSE_POPUP')?>',
			TITLE_SUCCESSFUL: '<?=GetMessageJS('CT_BCE_CATALOG_ADD_TO_BASKET_OK')?>',
			COMPARE_MESSAGE_OK: '<?=GetMessageJS('CT_BCE_CATALOG_MESS_COMPARE_OK')?>',
			COMPARE_UNKNOWN_ERROR: '<?=GetMessageJS('CT_BCE_CATALOG_MESS_COMPARE_UNKNOWN_ERROR')?>',
			COMPARE_TITLE: '<?=GetMessageJS('CT_BCE_CATALOG_MESS_COMPARE_TITLE')?>',
			BTN_MESSAGE_COMPARE_REDIRECT: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_COMPARE_REDIRECT')?>',
			PRODUCT_GIFT_LABEL: '<?=GetMessageJS('CT_BCE_CATALOG_PRODUCT_GIFT_LABEL')?>',
			PRICE_TOTAL_PREFIX: '<?=GetMessageJS('CT_BCE_CATALOG_MESS_PRICE_TOTAL_PREFIX')?>',
			RELATIVE_QUANTITY_MANY: '<?=CUtil::JSEscape($arParams['MESS_RELATIVE_QUANTITY_MANY'])?>',
			RELATIVE_QUANTITY_FEW: '<?=CUtil::JSEscape($arParams['MESS_RELATIVE_QUANTITY_FEW'])?>',
			SITE_ID: '<?=CUtil::JSEscape($component->getSiteId())?>'
		});

		var <?=$obName?> = new JCCatalogElement(<?=CUtil::PhpToJSObject($jsParams, false, true)?>);
	</script>

<?
unset($actualItem, $itemIds, $jsParams);

