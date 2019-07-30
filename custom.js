function Basket($params) {
	var _this = this;
	_this.init($params);
}

Basket.prototype = {
	init: function ($params) {
		var _this = this;
		_this.data = $params;

		console.log(_this);

		_this.events();
	},
	events: function () {
		var _this = this;

		$(document).on('click', '.js-add-basket', function () {

			var data = {
				id: $(this).data('id'),
				quantity: $(this).data('quantity'),
				ajax_basket : 'Y'
			};

			var url = window.location.pathname + '?action=' + $(this).data('action') + '&id=' + data.id;

			_this.addToBasketAjax(data, url);

		});

		$(document).on('click', '.js-delete-item', function () {

			var _deleteId = 'DELETE_' + $(this).data('id');

			var data = {
				basketAction: 'recalculateAjax',
				via_ajax: 'Y',
				site_id: _this.data.siteId,
				template: _this.data.template,
				signedParamsString: _this.data.signedParamsString,
				sessid: $('#sessid').val(),
				fullRecalculation: 'Y',
				basket : {
					[_deleteId]: 'Y'
				}
			};

			_this.deleteItemFromBasketAjax($(this).data('id'), data);

		});

		$(document).on('click', '.js-basket-minus', function () {

			var _btn = $(this);

			changeQuantity(_btn);

			_this.changeItemQuantityInBasketAjax(
				_btn.parent().data('id'),
				_btn.parent().find('.js-quantity-field').val()
			);

		});

		$(document).on('click', '.js-basket-plus', function () {

			var _btn = $(this);

			changeQuantity(_btn);

			_this.changeItemQuantityInBasketAjax(
				_btn.parent().data('id'),
				_btn.parent().find('.js-quantity-field').val()
			);

		});

		$(document).on('click', '.js-item-add-basket', function () {

			var data = {
				id: $(this).data('id'),
				quantity: $(this).closest('.descr-row__btn-panel').find('.js-quantity-field').val(),
				ajax_basket : 'Y'
			};

			var url = window.location.pathname + '?action=' + $(this).data('action') + '&id=' + data.id;

			_this.addToBasketAjax(data, url);
		});

		$(document).on('click', '.js-item-plus', function () {
			var _btn = $(this);

			changeQuantity(_btn);
		});

		$(document).on('click', '.js-item-minus', function () {
			var _btn = $(this);

			changeQuantity(_btn);
		});

	},
	addToBasketAjax: function (data, url) {
		var _this = this;

		if (data) {
			$.ajax({
				url: url,
				type: 'post',
				dataType: 'json',
				data: data,
				success: function (result) {

					if (result.STATUS == 'OK') {
						/* показать модалку о том, что товар успешно добавлен в корзину */
						_this.updateBasketAjax();
					}

				},
				error: function (e, error) {
					console.log(e);
					console.log(error);
				}
			});
			return false;
		}

	},
	updateBasketAjax: function () {
		var _this = this;

		var data = {
			basketAction: 'recalculateAjax',
			via_ajax: 'Y',
			site_id: _this.data.siteId,
			template: _this.data.template,
			signedParamsString: _this.data.signedParamsString,
			sessid: $('#sessid').val(),
			fullRecalculation: 'Y'
		};

		if (data) {
			$.ajax({
				url: _this.data.ajaxUrl,
				type: 'post',
				dataType: 'json',
				data: data,
				success: function (result) {

					if (typeof result === "object") {

						if (result.BASKET_DATA.BASKET_ITEMS_COUNT > 0) {
							_this.editTotalPriceInBasket(result.BASKET_DATA.allSum_FORMATED);
							_this.editDiscountPriceInBasket(result.BASKET_DATA.DISCOUNT_PRICE_ALL_FORMATED);
						}

						_this.changeTotalCountItemsInBasket(result.BASKET_DATA.BASKET_ITEMS_COUNT);

						$.each(result['CHANGED_BASKET_ITEMS'], function( key, value ) {
							_this.editItemQuantityInBasket(
								result['CHANGED_BASKET_ITEMS'][key],
								result['BASKET_DATA']['GRID']['ROWS'][value]['QUANTITY']
							);
						});

					}

				},
				error: function (e, error) {
					console.log(e);
					console.log(error);
				}
			});
			return false;
		}

	},
	editTotalPriceInBasket: function (price) {

		if (
			price &&
			$('.total-cart-basket').find('.bottom-plate__price').length
		) {
			$('.total-cart-basket').find('.bottom-plate__price').text('').text(price);
		}

	},
	editDiscountPriceInBasket: function(discountPrice) {
		if (discountPrice) {
			/* написать изменение суммы скидки, как в _this.editTotalPriceInBasket */
		}
	},
	editItemQuantityInBasket: function (id, quantity) {
		var _this = this;

		if ($('.cart-basket-header').length > 0) {

			if ($('#basket-item-' + id).length > 0) {

				$('#basket-item-' + id).find('.js-quantity-field').val(quantity);
				$('#basket-item-' + id).find('.js-quantity-field').trigger('input');

			} else {

				_this.addNewItemToBasket(id, quantity);

			}

		} else {
			/* делаем перезагрузку страницы, тк не хочется полностью вёрстку пустой корзины менять на НЕ пустую :( */
			setTimeout(function () {
				location.reload();
			}, 700);
		}

	},
	deleteItemFromBasketAjax: function (id, data) {
		var _this = this;

		if (data) {
			$.ajax({
				url: _this.data.ajaxUrl,
				type: 'post',
				dataType: 'json',
				data: data,
				success: function (result) {

					if (typeof result === "object") {

						if (result.BASKET_DATA.BASKET_ITEMS_COUNT > 0) {
							_this.editTotalPriceInBasket(result.BASKET_DATA.allSum_FORMATED);
							_this.editDiscountPriceInBasket(result.BASKET_DATA.DISCOUNT_PRICE_ALL_FORMATED);
						}

						_this.deleteItemBlockFromBasket(id, result);

						_this.changeTotalCountItemsInBasket(result.BASKET_DATA.BASKET_ITEMS_COUNT);
					}

				},
				error: function (e, error) {
					console.log(e);
					console.log(error);
				}
			});
			return false;
		}

	},
	deleteItemBlockFromBasket: function(id, resultData) {

		if (resultData.BASKET_DATA.BASKET_ITEMS_COUNT > 0) {

			if ($('#basket-item-' + id).length) {
				$('#basket-item-' + id).remove();
			}

		} else {
			/* делаем перезагрузку страницы, тк не хочется полностью вёрстку Не пустой корзины менять на пустую :( */
			setTimeout(function () {
				location.reload();
			}, 700);
		}

	},
	addNewItemToBasket: function (data) {
		/* вставить вёрстку этого блока в корзину :( */
	},
	changeTotalCountItemsInBasket: function (totalCount) {

		if (totalCount > 0) {
			$('.js-btn-cart').find('.cart-qty').text('').text(totalCount);
		} else {
			$('.js-btn-cart').find('.cart-qty').text('').text('пуста');
		}

	},
	changeItemQuantityInBasketAjax: function (id, quantity) {
		var _this = this;

		var _quantityId = 'QUANTITY_' + id;
		var data = {
			basketAction: 'recalculateAjax',
			via_ajax: 'Y',
			site_id: _this.data.siteId,
			template: _this.data.template,
			signedParamsString: _this.data.signedParamsString,
			sessid: $('#sessid').val(),
			fullRecalculation: 'Y',
			basket : {
				[_quantityId]: quantity
			}
		};

		if (data) {
			$.ajax({
				url: _this.data.ajaxUrl,
				type: 'post',
				dataType: 'json',
				data: data,
				success: function (result) {

					if (typeof result === "object") {

						if (result.BASKET_DATA.BASKET_ITEMS_COUNT > 0) {
							_this.editTotalPriceInBasket(result.BASKET_DATA.allSum_FORMATED);
							_this.editDiscountPriceInBasket(result.BASKET_DATA.DISCOUNT_PRICE_ALL_FORMATED);
						}

					}
				},
				error: function (e, error) {
					console.log(e);
					console.log(error);
				}
			});
			return false;
		}

	}
};

function changeQuantity($btn) {

	var quantity = $btn.parent().find('.js-quantity-field').val();

	if ($btn.hasClass('js-minus')) {
		if (quantity > 1) {
			--quantity;
		} else if (quantity < 1) {
			quantity = 1;
		}
	} else if ($btn.hasClass('js-plus')) {
		quantity++;
	}

	$btn.parent().find('.js-quantity-field').val('').val(quantity);
	$btn.parent().find('.js-quantity-field').trigger('input');

}
