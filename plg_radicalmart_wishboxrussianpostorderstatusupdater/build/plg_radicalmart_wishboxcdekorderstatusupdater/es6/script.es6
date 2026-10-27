"use strict";

class RadicalMartWishboxRussianPostOrderStatusUpdater {
	constructor() {
	}

	initialization() {
			document.getElementById('toolbar-wishboxrussianpost-update-statuses1')
				.querySelector('button')
				.addEventListener(
					'click',
					() => {
						const adminForm = document.getElementById('adminForm');
						const targetForm = document.getElementById('wishboxradicalmartrussianpost-orders');

						// Получаем выбранные checkbox'ы
						const checkboxes= adminForm.querySelectorAll('input[name="cid[]"]:checked');
						const cid = Array.from(checkboxes).map(cb => cb.value);

						// Очищаем предыдущие cid в целевой форме
						targetForm.querySelectorAll('input[name="cid[]"]').forEach(el => el.remove());

						// Добавляем новые cid в целевую форму
						cid.forEach(id => {
							const input = document.createElement('input');
							input.type = 'hidden';
							input.name = 'cid[]';
							input.value = id;
							targetForm.appendChild(input);
						});

						targetForm.submit();
					});
	}
}

export default RadicalMartWishboxRussianPostOrderStatusUpdater;

window.RadicalMartWishboxRussianPostOrderStatusUpdaterClass = null;
window.RadicalMartWishboxRussianPostOrderStatusUpdater = () => {
	if (window.RadicalMartWishboxRussianPostOrderStatusUpdaterClass === null) {
		window.RadicalMartWishboxRussianPostOrderStatusUpdaterClass = new RadicalMartWishboxRussianPostOrderStatusUpdater();
	}
	return window.RadicalMartWishboxRussianPostOrderStatusUpdaterClass;
}

document.addEventListener('DOMContentLoaded', () => {
	window.RadicalMartWishboxRussianPostOrderStatusUpdater().initialization();
});