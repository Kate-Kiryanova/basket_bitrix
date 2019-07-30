<? // component bitrix:catalog.item file template.php ?>

<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main;

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogProductsViewedComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 * @var string $templateFolder
 */

$this->setFrameMode(true);
?>

<?
if (isset($arResult['ITEM']))
{
	$item = $arResult['ITEM'];
	$areaId = $arResult['AREA_ID'];
	$obName = 'ob' . preg_replace("/[^a-zA-Z0-9_]/", "x", $areaId);

	$price = $item['ITEM_PRICES'][$item['ITEM_PRICE_SELECTED']];
	?>

	<div class="category-grid__item" id="<?=$areaId?>" data-entity="item">
		<div class="slider-brick">
			<a href="<?=$item['DETAIL_PAGE_URL'];?>">
				<div class="slider-brick__img-wrap">

					<? if (!empty($item['PREVIEW_PICTURE']['SRC'])) : ?>
						<img
							src="<?=$item['PREVIEW_PICTURE']['SRC'];?>"
							alt=""
							srcset="<?= (!empty($item['DETAIL_PICTURE']['SRC'])) ? $item['DETAIL_PICTURE']['SRC'] : $item['PREVIEW_PICTURE']['SRC']; ?>"
						/>
					<? endif; ?>

					<div class="brick-like">
						<svg class="icon i i-like">
							<use xlink:href="#i-like"></use>
						</svg>
					</div>

				</div>

				<div class="slider-brick__text-wrap">
					<div class="text">
						<?=$item['NAME'];?>
					</div>

					<div class="caption">

						<? if (!empty($item['PROPERTIES']['CML2_ARTICLE']['VALUE'])) : ?>
							<div class="caption__code">
								Артикул: <?=$item['PROPERTIES']['CML2_ARTICLE']['VALUE'];?>
							</div>
						<? endif; ?>

						<? if (
							$item['CAN_BUY'] &&
							(int)$item['CATALOG_QUANTITY'] > 0
						) : ?>
							<div class="caption__status active">В наличии</div>
						<? else : ?>
							<div class="caption__status"><?=$arParams['MESS_NOT_AVAILABLE'];?></div>
						<? endif; ?>

						<div class="caption__qty">
							<?=$item['CATALOG_QUANTITY'].' '.$item['ITEM_MEASURE']['TITLE']?>
						</div>

					</div>

				</div>

			</a>

			<div class="bottom-plate">

				<? if ($item['CAN_BUY']) : ?>
					<a
						class="btn btn--blue js-add-basket"
						data-id="<?=$item['ID'];?>"
						data-action="ADD2BASKET"
						data-quantity="1"
						href="javascript:void(0);"
					>
						<?=$arParams['MESS_BTN_ADD_TO_BASKET'];?>
					</a>
				<? endif; ?>

				<div class="bottom-plate__price">
					<?=$price['PRINT_RATIO_BASE_PRICE'];?>
				</div>

			</div>

		</div>
	</div>

	<?
}
?>

