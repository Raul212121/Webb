document.addEventListener('DOMContentLoaded', function () {
    var loginModalBackdrop = document.getElementById('login-modal-backdrop');
    var registerModalBackdrop = document.getElementById('register-modal-backdrop');

    var openLoginButton = document.getElementById('open-login-modal');
    var openRegisterButton = document.getElementById('open-register-modal');

    var closeLoginButton = document.getElementById('close-login-modal');
    var closeRegisterButton = document.getElementById('close-register-modal');

    var switchToLoginButton = document.getElementById('switch-to-login-modal');
	var switchToRegisterButton = document.getElementById('switch-to-register-modal');

	var loginForm = document.getElementById('login-form');
	var loginUsernameInput = document.getElementById('login-username');
	var loginPasswordInput = document.getElementById('login-password');

	var verifyModalBackdrop = document.getElementById('verify-modal-backdrop');
	var closeVerifyButton = document.getElementById('close-verify-modal');

	var fleamarketModalBackdrop = document.getElementById('fleamarket-modal-backdrop');
	var openFleamarketButton = document.getElementById('open-fleamarket-modal');
	var closeFleamarketButton = document.getElementById('close-fleamarket-modal');

	var fleamarketTabButtons = document.querySelectorAll('[data-fleamarket-tab]');
	var fleamarketDepotTab = document.getElementById('fleamarket-tab-depot');
	var fleamarketOffersTab = document.getElementById('fleamarket-tab-offers');
	var fleamarketMarketTab = document.getElementById('fleamarket-tab-market');

	var fleamarketSellPriceInput = document.getElementById('fleamarket-sell-price');
	var fleamarketSellButton = document.getElementById('fleamarket-sell-button');

	var fleamarketContentContainer = document.getElementById('fleamarket-content-container');
	var refreshFleamarketButton = document.getElementById('refresh-fleamarket-market');

	var fleamarketNoticeOverlay = document.getElementById('fleamarket-notice-overlay');
	var closeFleamarketNoticeButton = document.getElementById('close-fleamarket-notice');

	var fleamarketFilterShowMoreButton = document.getElementById('fleamarket-filter-show-more-button');

	if (fleamarketFilterShowMoreButton) {
		fleamarketFilterShowMoreButton.addEventListener('click', function () {
			var hiddenBonusItems = document.querySelectorAll('.fleamarket-filter-bonus-item-hidden');

			if (!hiddenBonusItems.length) {
				fleamarketFilterShowMoreButton.style.display = 'none';
				return;
			}

			hiddenBonusItems.forEach(function (item) {
				item.classList.remove('fleamarket-filter-bonus-item-hidden');
			});

			fleamarketFilterShowMoreButton.style.display = 'none';
		});
	}

	if (closeFleamarketNoticeButton && fleamarketNoticeOverlay) {
		function clearFleamarketNoticeFromUrl() {
			var currentUrl = new URL(window.location.href);

			if (currentUrl.searchParams.has('fleamarket_notice')) {
				currentUrl.searchParams.delete('fleamarket_notice');
				window.history.replaceState({}, document.title, currentUrl.pathname + currentUrl.search);
			}
		}

		clearFleamarketNoticeFromUrl();

		closeFleamarketNoticeButton.addEventListener('click', function (event) {
			event.preventDefault();
			fleamarketNoticeOverlay.style.display = 'none';
			clearFleamarketNoticeFromUrl();
		});
	}

	if (refreshFleamarketButton) {
		refreshFleamarketButton.addEventListener('click', function (event) {
			event.preventDefault();
			reloadFleamarketContent(window.location.pathname + window.location.search);
		});
	}

	function reloadFleamarketContent(url) {
		if (!fleamarketContentContainer) {
			return;
		}

		var requestUrl = url || window.location.pathname + window.location.search;

		if (requestUrl.indexOf('?') === -1) {
			requestUrl += '?fleamarket_partial=1';
		} else {
			requestUrl += '&fleamarket_partial=1';
		}

		fetch(requestUrl, {
			headers: {
				'X-Requested-With': 'XMLHttpRequest'
			}
		})
			.then(function (response) {
				if (!response.ok) {
					throw new Error('Failed to reload FleaMarket.');
				}

				return response.text();
			})
			.then(function (html) {
				fleamarketContentContainer.innerHTML = html;
				bindFleamarketTabEvents();
				bindFleamarketCountdowns();
				bindFleamarketFilterEvents();
				bindFleamarketSearchFilter();
				bindFleamarketMarketApplyFilter();
				bindFleamarketMarketResetFilter();
			})
			.catch(function () {
				window.location.href = url || window.location.href;
			});
	}

	function setActiveFleamarketTab(tabName) {
		if (!tabName) {
			return;
		}

		var currentUrl = new URL(window.location.href);
		currentUrl.searchParams.set('fleamarket_open', '1');
		currentUrl.searchParams.set('fleamarket_tab', tabName);

		reloadFleamarketContent(currentUrl.pathname + currentUrl.search);
	}

	function bindFleamarketTabEvents() {
		var currentTabButtons = document.querySelectorAll('[data-fleamarket-tab]');

		if (!currentTabButtons.length) {
			return;
		}

		currentTabButtons.forEach(function (button) {
			button.addEventListener('click', function () {
				var tabName = button.getAttribute('data-fleamarket-tab');

				if (!tabName) {
					return;
				}

				setActiveFleamarketTab(tabName);
			});
		});
	}

	if (loginForm && loginUsernameInput && loginPasswordInput) {
		[loginUsernameInput, loginPasswordInput].forEach(function (input) {
			input.addEventListener('keydown', function (event) {
				if (event.key === 'Enter') {
					event.preventDefault();

					if (typeof loginForm.requestSubmit === 'function') {
						loginForm.requestSubmit();
					} else {
						loginForm.submit();
					}
				}
			});
		});
	}

    function lockBody() {
        document.body.style.overflow = 'hidden';
    }

    function unlockBody() {
        document.body.style.overflow = '';
    }

    function closeAllModals() {
		var wasFleamarketOpen = false;

		if (fleamarketModalBackdrop && !fleamarketModalBackdrop.hidden) {
			wasFleamarketOpen = true;
		}

		if (loginModalBackdrop) {
			loginModalBackdrop.hidden = true;
		}

		if (registerModalBackdrop) {
			registerModalBackdrop.hidden = true;
		}

		if (verifyModalBackdrop) {
			verifyModalBackdrop.hidden = true;
		}

		if (fleamarketModalBackdrop) {
			fleamarketModalBackdrop.classList.remove('is-open');

			setTimeout(function () {
				if (!fleamarketModalBackdrop.classList.contains('is-open')) {
					fleamarketModalBackdrop.hidden = true;
				}
			}, 180);
		}

		unlockBody();

		if (wasFleamarketOpen) {
			window.location.href = '/';
		}
	}

    function openLoginModal() {
        closeAllModals();

        if (loginModalBackdrop) {
            loginModalBackdrop.hidden = false;
            lockBody();
        }
    }

    function openRegisterModal() {
        closeAllModals();

        if (registerModalBackdrop) {
            registerModalBackdrop.hidden = false;
            lockBody();
        }
    }

	function openFleamarketModal() {
		closeAllModals();

		if (fleamarketModalBackdrop) {
			fleamarketModalBackdrop.hidden = false;

			requestAnimationFrame(function () {
				fleamarketModalBackdrop.classList.add('is-open');
			});

			lockBody();
		}

		var currentUrl = new URL(window.location.href);
		currentUrl.searchParams.set('fleamarket_open', '1');

		if (!currentUrl.searchParams.get('fleamarket_tab')) {
			currentUrl.searchParams.set('fleamarket_tab', 'depot');
		}

		reloadFleamarketContent(currentUrl.pathname + currentUrl.search);
	}

    if (openLoginButton) {
        openLoginButton.addEventListener('click', function (event) {
            event.preventDefault();
            openLoginModal();
        });
    }

    if (openRegisterButton) {
        openRegisterButton.addEventListener('click', function (event) {
            event.preventDefault();
            openRegisterModal();
        });
    }

	if (openFleamarketButton) {
		openFleamarketButton.addEventListener('click', function (event) {
			event.preventDefault();
			openFleamarketModal();
		});
	}

    if (closeLoginButton) {
        closeLoginButton.addEventListener('click', function (event) {
            event.preventDefault();
            closeAllModals();
        });
    }

    if (closeRegisterButton) {
        closeRegisterButton.addEventListener('click', function (event) {
            event.preventDefault();
            closeAllModals();
        });
    }

	if (closeVerifyButton) {
		closeVerifyButton.addEventListener('click', function (event) {
			event.preventDefault();
			closeAllModals();
		});
	}

	if (closeFleamarketButton) {
		closeFleamarketButton.addEventListener('click', function (event) {
			event.preventDefault();
			closeAllModals();
		});
	}

    if (switchToLoginButton) {
        switchToLoginButton.addEventListener('click', function (event) {
            event.preventDefault();
            openLoginModal();
        });
    }

	if (switchToRegisterButton) {
		switchToRegisterButton.addEventListener('click', function (event) {
			event.preventDefault();
			openRegisterModal();
		});
	}

    if (loginModalBackdrop) {
        loginModalBackdrop.addEventListener('click', function (event) {
            if (event.target === loginModalBackdrop) {
                closeAllModals();
            }
        });
    }

    if (registerModalBackdrop) {
        registerModalBackdrop.addEventListener('click', function (event) {
            if (event.target === registerModalBackdrop) {
                closeAllModals();
            }
        });
    }

	if (verifyModalBackdrop) {
		verifyModalBackdrop.addEventListener('click', function (event) {
			if (event.target === verifyModalBackdrop) {
				closeAllModals();
			}
		});
	}

	if (fleamarketModalBackdrop) {
		fleamarketModalBackdrop.addEventListener('click', function (event) {
			if (event.target === fleamarketModalBackdrop) {
				closeAllModals();
			}
		});
	}

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeAllModals();
        }
    });

	var accountDropdownToggle = document.getElementById('account-dropdown-toggle');
    var accountDropdownMenu = document.getElementById('account-dropdown-menu');

    if (accountDropdownToggle && accountDropdownMenu) {
        accountDropdownToggle.addEventListener('click', function (event) {
            event.preventDefault();
            event.stopPropagation();

            accountDropdownMenu.hidden = !accountDropdownMenu.hidden;
        });

        document.addEventListener('click', function (event) {
            if (!accountDropdownMenu.contains(event.target) && event.target !== accountDropdownToggle) {
                accountDropdownMenu.hidden = true;
            }
        });
    }

	function bindFleamarketMarketApplyFilter() {
		var applyButton = document.getElementById('fleamarket-filter-apply-button');
		var searchInput = document.getElementById('fleamarket-filter-search-input');

		if (!applyButton) {
			return;
		}

		if (applyButton.dataset.bound === '1') {
			return;
		}

		applyButton.dataset.bound = '1';

		applyButton.addEventListener('click', function () {

			var currentUrl = new URL(window.location.href);
			currentUrl.searchParams.set('fleamarket_open', '1');
			currentUrl.searchParams.set('fleamarket_tab', 'market');

			currentUrl.searchParams.delete('market_search');

			currentUrl.searchParams.delete('market_bonus');

			var checkedBonusInputs = document.querySelectorAll('#fleamarket-filter-bonus-list input[type="checkbox"]:checked');
			var selectedBonusValues = [];

			checkedBonusInputs.forEach(function (input) {
				selectedBonusValues.push(input.value);
			});

			if (selectedBonusValues.length) {
				currentUrl.searchParams.set('market_bonus', selectedBonusValues.join(','));
			}
			reloadFleamarketContent(currentUrl.pathname + currentUrl.search);
		});
	}

	function bindFleamarketFilterEvents() {
		var currentShowMoreButton = document.getElementById('fleamarket-filter-show-more-button');
		var bonusList = document.getElementById('fleamarket-filter-bonus-list');

		if (!currentShowMoreButton || !bonusList) {
			return;
		}

		if (currentShowMoreButton.dataset.bound === '1') {
			return;
		}

		currentShowMoreButton.dataset.bound = '1';
		currentShowMoreButton.dataset.expanded = '0';

		currentShowMoreButton.addEventListener('click', function () {
			var hiddenBonusItems = bonusList.querySelectorAll('.fleamarket-filter-bonus-item-hidden');
			var allBonusItems = bonusList.querySelectorAll('.fleamarket-filter-bonus-item');

			if (currentShowMoreButton.dataset.expanded === '0') {
				hiddenBonusItems.forEach(function (item) {
					item.classList.remove('fleamarket-filter-bonus-item-hidden');
				});

				currentShowMoreButton.dataset.expanded = '1';
				currentShowMoreButton.textContent = 'Show less';
				return;
			}

			allBonusItems.forEach(function (item, index) {
				if (index >= 5) {
					item.classList.add('fleamarket-filter-bonus-item-hidden');
				}
			});

			bonusList.scrollTop = 0;
			currentShowMoreButton.dataset.expanded = '0';
			currentShowMoreButton.textContent = 'Show more';
		});
	}

	function normalizeFleamarketText(text) {
		return (text || '')
			.toLowerCase()
			.replace(/[ăâ]/g, 'a')
			.replace(/[î]/g, 'i')
			.replace(/[șş]/g, 's')
			.replace(/[țţ]/g, 't');
	}

	function bindFleamarketSearchFilter() {
		var searchInput = document.getElementById('fleamarket-filter-search-input');

		if (!searchInput) {
			return;
		}

		if (searchInput.dataset.bound === '1') {
			return;
		}

		searchInput.dataset.bound = '1';

		searchInput.addEventListener('input', function () {
			var query = normalizeFleamarketText(searchInput.value).trim();
			var marketRows = document.querySelectorAll('.fleamarket-row-market[data-market-item-name]');

			marketRows.forEach(function (row) {
				var itemNameElement = row.querySelector('.fleamarket-market-item-name');
				var itemName = normalizeFleamarketText(itemNameElement ? itemNameElement.textContent : '');

				if (!query || itemName.indexOf(query) !== -1) {
					row.style.display = '';
				} else {
					row.style.display = 'none';
				}
			});
		});
	}

	function bindFleamarketMarketResetFilter() {
		var resetButton = document.getElementById('fleamarket-filter-reset-button');

		if (!resetButton) {
			return;
		}

		if (resetButton.dataset.bound === '1') {
			return;
		}

		resetButton.dataset.bound = '1';

		resetButton.addEventListener('click', function () {
			var currentUrl = new URL(window.location.href);
			currentUrl.searchParams.set('fleamarket_open', '1');
			currentUrl.searchParams.set('fleamarket_tab', 'market');
			currentUrl.searchParams.delete('market_search');
			currentUrl.searchParams.delete('market_bonus');
			currentUrl.searchParams.delete('market_bonus[]');

			reloadFleamarketContent(currentUrl.pathname + currentUrl.search);
		});
	}

	function bindFleamarketCountdowns() {
		var fleamarketCountdownButtons = document.querySelectorAll('[data-withdraw-countdown="1"]');

		if (!fleamarketCountdownButtons.length) {
			return;
		}

		fleamarketCountdownButtons.forEach(function (button) {
			if (button.dataset.countdownBound === '1') {
				return;
			}

			button.dataset.countdownBound = '1';

			var secondsLeft = parseInt(button.getAttribute('data-seconds-left'), 10);

			if (isNaN(secondsLeft) || secondsLeft < 0) {
				secondsLeft = 0;
			}

			var parentForm = button.closest('form');

			function formatCountdown(totalSeconds) {
				var minutes = Math.floor(totalSeconds / 60);
				var seconds = totalSeconds % 60;

				var minuteText = minutes < 10 ? '0' + minutes : '' + minutes;
				var secondText = seconds < 10 ? '0' + seconds : '' + seconds;

				return minuteText + ':' + secondText;
			}

			function tick() {
				button.textContent = formatCountdown(secondsLeft);

				if (secondsLeft <= 0) {
					if (parentForm && parentForm.dataset.autoSubmitted !== '1') {
						parentForm.dataset.autoSubmitted = '1';
						parentForm.submit();
					}
					return;
				}

				secondsLeft -= 1;
			}

			tick();
			setInterval(tick, 1000);
		});
	}
	bindFleamarketTabEvents();
	bindFleamarketCountdowns();
	bindFleamarketFilterEvents();
	bindFleamarketSearchFilter();
	bindFleamarketMarketApplyFilter();
	bindFleamarketMarketResetFilter();
});