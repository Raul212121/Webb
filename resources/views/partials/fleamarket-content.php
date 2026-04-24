<div class="fleamarket-window-inner">
            <div class="fleamarket-window-topbar">
                <div class="fleamarket-topbar-left">
                    <button type="button" class="fleamarket-tab-button<?= $activeFleamarketTab === 'depot' ? ' is-active' : ''; ?>" data-fleamarket-tab="depot">
						Depozit
					</button>
					<button type="button" class="fleamarket-tab-button<?= $activeFleamarketTab === 'offers' ? ' is-active' : ''; ?>" data-fleamarket-tab="offers">
						Ofertele mele
					</button>
					<button type="button" class="fleamarket-tab-button<?= $activeFleamarketTab === 'market' ? ' is-active' : ''; ?>" data-fleamarket-tab="market">
						Market
					</button>
					<div class="fleamarket-offer-limit-info">
						Oferte <?= (int) $activeListingCount; ?>/<?= (int) $maxListingSlots; ?>
					</div>
					<div class="fleamarket-offer-limit-reputation">
						Reputatie <?= number_format((float) $fleaReputation, 2, '.', ''); ?>
					</div>
                </div>

                <div class="fleamarket-topbar-center">
                    <h2 class="fleamarket-window-title" id="fleamarket-modal-title">FleaMarket</h2>
                </div>

                <div class="fleamarket-topbar-right">
                    <div class="fleamarket-balance-box">
						<span class="fleamarket-balance-label">MD:</span>
						<span class="fleamarket-balance-value">
							<?= isset($currentUser['cash']) ? (int) $currentUser['cash'] : 0; ?>
						</span>
					</div>

                </div>
            </div>

            <div class="fleamarket-window-body">
                <div class="fleamarket-tab-panel<?= $activeFleamarketTab === 'depot' ? ' is-active' : ''; ?>" id="fleamarket-tab-depot"<?= $activeFleamarketTab === 'depot' ? '' : ' hidden'; ?>>
					<div class="fleamarket-depot-layout">
						<section class="fleamarket-depot-content">
							<div class="fleamarket-depot-grid">
								<?php if (!empty($marketDepotItems)): ?>
									<?php foreach ($marketDepotItems as $depotItem): ?>
										<?php
											$iconPath = $itemIconResolver->getIconPathByVnum($depotItem['item_vnum']);
											$itemSize = $itemProtoResolver->getItemSizeByVnum($depotItem['item_vnum']);
											$itemName = $itemNameResolver->getNameByVnum($depotItem['item_vnum']);
										?>
										<a
											href="/?selected_depot_item=<?= (int) $depotItem['id']; ?>&fleamarket_open=1&fleamarket_tab=depot&depot_page=<?= (int) $depotCurrentPage; ?>"
											class="fleamarket-depot-slot<?= (!empty($selectedDepotItem) && (int) $selectedDepotItem['id'] === (int) $depotItem['id']) ? ' is-selected' : ''; ?>"
										>
											<div class="fleamarket-item-icon-stack size-<?= (int) $itemSize; ?>">
												<?php for ($i = 0; $i < $itemSize; $i++): ?>
													<span class="fleamarket-item-icon-cell"></span>
												<?php endfor; ?>

												<?php if (!empty($depotItem['item_count']) && (int) $depotItem['item_count'] > 1): ?>
													<span class="fleamarket-item-count-badge">
														<?= (int) $depotItem['item_count']; ?>
													</span>
												<?php endif; ?>

												<?php if (!empty($iconPath)): ?>
													<img src="<?= htmlspecialchars($iconPath, ENT_QUOTES, 'UTF-8'); ?>" alt="Item <?= (int) $depotItem['item_vnum']; ?>" class="fleamarket-item-icon-image">
												<?php endif; ?>
											</div>

											<div class="fleamarket-depot-slot-name">
												<?= htmlspecialchars(!empty($itemName) ? $itemName : ('VNUM ' . (int) $depotItem['item_vnum']), ENT_QUOTES, 'UTF-8'); ?>
											</div>
										</a>
									<?php endforeach; ?>
								<?php else: ?>
									<div class="fleamarket-empty-state">
										Nu exista iteme in depozitul FleaMarket.
									</div>
								<?php endif; ?>
							</div>

							<?php if ($depotTotalPages > 1): ?>
								<div class="fleamarket-pagination">
									<?php if ($depotCurrentPage > 1): ?>
										<a class="fleamarket-pagination-button fleamarket-pagination-button-compact" href="/?fleamarket_open=1&fleamarket_tab=depot&depot_page=<?= (int) ($depotCurrentPage - 1); ?>">&lt;</a>
									<?php endif; ?>

									<span class="fleamarket-pagination-info">
										<?= (int) $depotCurrentPage; ?>/<?= (int) $depotTotalPages; ?>
									</span>

									<?php if ($depotCurrentPage < $depotTotalPages): ?>
										<a class="fleamarket-pagination-button fleamarket-pagination-button-compact" href="/?fleamarket_open=1&fleamarket_tab=depot&depot_page=<?= (int) ($depotCurrentPage + 1); ?>">&gt;</a>
									<?php endif; ?>
								</div>
							<?php endif; ?>
						</section>

						<?php
						$selectedDepotItemIconPath = null;
						$selectedDepotItemName = 'Selecteaza un item';
						$selectedDepotItemType = null;
						$selectedDepotItemDescription = null;
						$selectedDepotItemHasBonuses = false;
						$selectedDepotItemExtraBonusLines = array();
						$selectedDepotItemFixedBonusLines = array();
						$selectedDepotItemSize = 1;
						$selectedDepotItemSocketRows = array();

						if (!empty($selectedDepotItem)) {
							$selectedDepotItemIconPath = $itemIconResolver->getIconPathByVnum($selectedDepotItem['item_vnum']);
							$selectedDepotItemName = $itemNameResolver->getNameByVnum($selectedDepotItem['item_vnum']);
							$selectedDepotItemDescription = $itemDescResolver->getDescriptionByVnum($selectedDepotItem['item_vnum']);
							$selectedDepotItemExtraBonusLines = $itemAttributeResolver->getExtraBonusLines($selectedDepotItem);
							$selectedDepotItemProtoRow = $itemProtoResolver->getItemByVnum($selectedDepotItem['item_vnum']);

							if (!empty($selectedDepotItemProtoRow)) {
								$selectedDepotItemType = isset($selectedDepotItemProtoRow['item_type']) ? $selectedDepotItemProtoRow['item_type'] : null;
								$selectedDepotItemHasBonuses = in_array($selectedDepotItemType, array('ITEM_WEAPON', 'ITEM_ARMOR', 'ITEM_METIN'), true);
								$selectedDepotItemFixedBonusLines = $itemFixedBonusResolver->getFixedBonusLines($selectedDepotItemProtoRow);
								$selectedDepotItemSize = isset($selectedDepotItemProtoRow['size']) ? (int) $selectedDepotItemProtoRow['size'] : 1;
							}

							if (empty($selectedDepotItemName)) {
								$selectedDepotItemName = 'VNUM ' . (int) $selectedDepotItem['item_vnum'];
							}

							for ($socketIndex = 0; $socketIndex <= 2; $socketIndex++) {
							$socketKey = 'socket' . $socketIndex;
							$socketVnum = isset($selectedDepotItem[$socketKey]) ? (int) $selectedDepotItem[$socketKey] : 0;

							if ($socketVnum <= 2) {
								continue;
							}

							$socketIconPath = $itemIconResolver->getIconPathByVnum($socketVnum);
							$socketName = $itemNameResolver->getNameByVnum($socketVnum);
							$socketProtoRow = $itemProtoResolver->getItemByVnum($socketVnum);
							$socketFixedBonusLines = array();

							if (!empty($socketProtoRow)) {
								$socketFixedBonusLines = $itemFixedBonusResolver->getFixedBonusLines($socketProtoRow);
							}

							$selectedDepotItemSocketRows[] = array(
								'index' => $socketIndex,
								'vnum' => $socketVnum,
								'icon_path' => $socketIconPath,
								'name' => !empty($socketName) ? $socketName : ('VNUM ' . $socketVnum),
								'bonus_lines' => $socketFixedBonusLines,
							);
						}
						}
						?>

						<aside class="fleamarket-depot-sidebar">
							<div class="fleamarket-depot-sidebar-inner">
								<h3 class="fleamarket-sidebar-title">Detalii item</h3>

								<div class="fleamarket-item-preview">
									<div class="fleamarket-item-preview-icon">
										<div class="fleamarket-item-icon-stack size-<?= (int) $selectedDepotItemSize; ?>">
											<?php for ($i = 0; $i < $selectedDepotItemSize; $i++): ?>
												<span class="fleamarket-item-icon-cell"></span>
											<?php endfor; ?>

											<?php if (!empty($selectedDepotItemIconPath)): ?>
												<img src="<?= htmlspecialchars($selectedDepotItemIconPath, ENT_QUOTES, 'UTF-8'); ?>" alt="<?= htmlspecialchars($selectedDepotItemName, ENT_QUOTES, 'UTF-8'); ?>" class="fleamarket-item-icon-image">
											<?php endif; ?>
										</div>
									</div>

									<div class="fleamarket-item-preview-name">
										<?= htmlspecialchars($selectedDepotItemName, ENT_QUOTES, 'UTF-8'); ?>
									</div>

									<?php if (!empty($selectedDepotItemHasBonuses)): ?>
										<div class="fleamarket-item-preview-section">
											<?php if (!empty($selectedDepotItemFixedBonusLines)): ?>
												<?php foreach ($selectedDepotItemFixedBonusLines as $fixedBonusLine): ?>
													<div class="fleamarket-item-preview-line fleamarket-item-preview-line-fixed">
														<?= htmlspecialchars($fixedBonusLine, ENT_QUOTES, 'UTF-8'); ?>
													</div>
												<?php endforeach; ?>
											<?php endif; ?>
										</div>

										<div class="fleamarket-item-preview-section">
											<?php if (!empty($selectedDepotItemExtraBonusLines)): ?>
												<?php foreach ($selectedDepotItemExtraBonusLines as $extraBonusLine): ?>
													<div class="fleamarket-item-preview-line fleamarket-item-preview-line-extra">
														<?= htmlspecialchars($extraBonusLine, ENT_QUOTES, 'UTF-8'); ?>
													</div>
												<?php endforeach; ?>
											<?php endif; ?>
										</div>

										<div class="fleamarket-item-preview-section">
											<?php if (!empty($selectedDepotItemSocketRows)): ?>
												<?php foreach ($selectedDepotItemSocketRows as $socketRow): ?>
													<div class="fleamarket-socket-row">
														<div class="fleamarket-socket-icon">
															<?php if (!empty($socketRow['icon_path'])): ?>
																<img src="<?= htmlspecialchars($socketRow['icon_path'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?= htmlspecialchars($socketRow['name'], ENT_QUOTES, 'UTF-8'); ?>" class="fleamarket-socket-image">
															<?php endif; ?>
														</div>

														<div class="fleamarket-socket-text">
															<?php
															if (!empty($socketRow['bonus_lines'])) {
																echo htmlspecialchars($socketRow['bonus_lines'][0], ENT_QUOTES, 'UTF-8');
															} else {
																echo htmlspecialchars($socketRow['name'], ENT_QUOTES, 'UTF-8');
															}
															?>
														</div>
													</div>
												<?php endforeach; ?>
											<?php endif; ?>
										</div>
									<?php else: ?>
										<div class="fleamarket-item-preview-section">
											<div class="fleamarket-item-preview-description">
												<?= htmlspecialchars(!empty($selectedDepotItemDescription) ? $selectedDepotItemDescription : 'Acest item nu are descriere disponibila.', ENT_QUOTES, 'UTF-8'); ?>
											</div>
										</div>
									<?php endif; ?>
									<div class="fleamarket-action-stack">
										<form class="fleamarket-withdraw-box" method="post" action="">
											<input type="hidden" name="fleamarket_withdraw_form" value="1">
											<input type="hidden" name="depot_item_id" value="<?= !empty($selectedDepotItem['id']) ? (int) $selectedDepotItem['id'] : 0; ?>">

											<?php if (!empty($fleamarketWithdrawError)): ?>
												<div class="login-form-error"><?= htmlspecialchars($fleamarketWithdrawError, ENT_QUOTES, 'UTF-8'); ?></div>
											<?php endif; ?>

											<?php if (!empty($fleamarketWithdrawSuccess)): ?>
												<div class="login-form-success"><?= htmlspecialchars($fleamarketWithdrawSuccess, ENT_QUOTES, 'UTF-8'); ?></div>
											<?php endif; ?>

											<button type="submit" class="fleamarket-withdraw-button">
												Retrage in joc
											</button>
										</form>

										<form class="fleamarket-sell-box" method="post" action="">
											<input type="hidden" name="fleamarket_sell_form" value="1">
											<input type="hidden" name="depot_item_id" value="<?= !empty($selectedDepotItem['id']) ? (int) $selectedDepotItem['id'] : 0; ?>">

											<?php if (!empty($fleamarketSellError)): ?>
												<div class="login-form-error"><?= htmlspecialchars($fleamarketSellError, ENT_QUOTES, 'UTF-8'); ?></div>
											<?php endif; ?>

											<?php if (!empty($fleamarketSellSuccess)): ?>
												<div class="login-form-success"><?= htmlspecialchars($fleamarketSellSuccess, ENT_QUOTES, 'UTF-8'); ?></div>
											<?php endif; ?>

											<div class="fleamarket-sell-controls">
												<input
													type="number"
													min="1"
													step="1"
													value="1"
													class="fleamarket-sell-price-input"
													id="fleamarket-sell-price"
													name="price_md"
													placeholder="Pret MD"
												>
												<button type="submit" class="fleamarket-sell-button" id="fleamarket-sell-button">
													Vinde
												</button>
											</div>

											<div class="fleamarket-sell-info">
												Tranzactia peste 10MD este taxata cu 5%
											</div>
										</form>
									</div>
								</div>
							</div>
						</aside>
					</div>
				</div>

                <div class="fleamarket-tab-panel<?= $activeFleamarketTab === 'offers' ? ' is-active' : ''; ?>" id="fleamarket-tab-offers"<?= $activeFleamarketTab === 'offers' ? '' : ' hidden'; ?>>
					<div class="fleamarket-offers-layout">
						<div class="fleamarket-list-header">
							<div>ID</div>
							<div>Icon</div>
							<div>Data publicarii</div>
							<div>Pret</div>
							<div>Actiune</div>
						</div>

						<div class="fleamarket-list-body">
							<?php if (!empty($myMarketListings)): ?>
								<?php foreach ($myMarketListings as $listingItem): ?>
									<?php
										$listingIconPath = $itemIconResolver->getIconPathByVnum($listingItem['item_vnum']);
										$listingItemSize = $itemProtoResolver->getItemSizeByVnum($listingItem['item_vnum']);
									?>
									<div class="fleamarket-row fleamarket-row-offers">
										<div><?= (int) $listingItem['id']; ?></div>

										<div class="fleamarket-item-icon-box">
											<div class="fleamarket-item-icon-stack size-<?= (int) $listingItemSize; ?>">
												<?php for ($i = 0; $i < $listingItemSize; $i++): ?>
													<span class="fleamarket-item-icon-cell"></span>
												<?php endfor; ?>

												<?php if (!empty($listingIconPath)): ?>
													<img src="<?= htmlspecialchars($listingIconPath, ENT_QUOTES, 'UTF-8'); ?>" alt="Item <?= (int) $listingItem['item_vnum']; ?>" class="fleamarket-item-icon-image">
												<?php endif; ?>

												<?php if (!empty($listingItem['item_count']) && (int) $listingItem['item_count'] > 1): ?>
													<span class="fleamarket-item-count-badge">
														<?= (int) $listingItem['item_count']; ?>
													</span>
												<?php endif; ?>
											</div>
										</div>

										<div><?= htmlspecialchars($listingItem['created_at'], ENT_QUOTES, 'UTF-8'); ?></div>
										<div class="fleamarket-price"><?= (int) $listingItem['price_md']; ?> MD</div>
										<div>
											<div>
												<?php
													$isWithdrawPending = !empty($listingItem['withdraw_status']) && $listingItem['withdraw_status'] === 'pending';
													$secondsLeft = isset($listingItem['withdraw_seconds_left']) ? (int) $listingItem['withdraw_seconds_left'] : 0;
												?>

												<?php if ($isWithdrawPending): ?>
													<form method="post" action="" class="fleamarket-auto-finalize-form" data-auto-finalize-form="1">
														<input type="hidden" name="fleamarket_listing_finalize_return_form" value="1">
														<input type="hidden" name="listing_id" value="<?= (int) $listingItem['id']; ?>">

														<button
															type="submit"
															class="fleamarket-action-button fleamarket-action-button-pending"
															data-withdraw-countdown="1"
															data-listing-id="<?= (int) $listingItem['id']; ?>"
															data-seconds-left="<?= (int) $secondsLeft; ?>"
														>
															<?= gmdate('i:s', max(0, (int) $secondsLeft)); ?>
														</button>
													</form>
												<?php else: ?>
													<form method="post" action="">
														<input type="hidden" name="fleamarket_listing_withdraw_form" value="1">
														<input type="hidden" name="listing_id" value="<?= (int) $listingItem['id']; ?>">
														<button type="submit" class="fleamarket-action-button">Retrage</button>
													</form>
												<?php endif; ?>
											</div>
										</div>
									</div>
								<?php endforeach; ?>
							<?php else: ?>
								<div class="fleamarket-empty-state">
									Nu ai oferte active in acest moment.
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>

                <div class="fleamarket-tab-panel<?= $activeFleamarketTab === 'market' ? ' is-active' : ''; ?>" id="fleamarket-tab-market"<?= $activeFleamarketTab === 'market' ? '' : ' hidden'; ?>>
					<div class="fleamarket-market-layout">
						<aside class="fleamarket-market-sidebar">
							<div class="fleamarket-market-sidebar-inner">
								<h3 class="fleamarket-sidebar-title">Filtru</h3>

								<div class="fleamarket-filter-search">
									<input
										type="text"
										class="fleamarket-filter-search-input"
										id="fleamarket-filter-search-input"
										placeholder="Cauta dupa nume"
										value="<?= htmlspecialchars($activeMarketSearch, ENT_QUOTES, 'UTF-8'); ?>"
									>
								</div>

								<div class="fleamarket-filter-bonus-section">
									<div class="fleamarket-filter-bonus-title">Bonusuri</div>

									<div class="fleamarket-filter-bonus-list" id="fleamarket-filter-bonus-list">
										<?php
										$fleamarketFilterBonuses = array(
											'semi_human' => 'Tare impotriva semi-oamenilor',
											'animal' => 'Tare impotriva animalelor',
											'orc' => 'Tare impotriva orcilor',
											'esoteric' => 'Tare impotriva esotericilor',
											'undead' => 'Tare impotriva vampirilor',
											'devil' => 'Tare impotriva diavolului',
											'monster' => 'Tare impotriva monstrilor',
											'warrior' => 'Tare impotriva razboinicilor',
											'assassin' => 'Tare impotriva ninja',
											'sura' => 'Tare impotriva sura',
											'shaman' => 'Tare impotriva samanilor',
											'critical' => 'Lovitura critica',
											'penetrate' => 'Lovitura patrunzatoare',
											'attack_speed' => 'Viteza de atac',
											'move_speed' => 'Viteza de miscare',
											'cast_speed' => 'Viteza farmecului',
											'max_hp' => 'Max. PV',
											'max_sp' => 'Max. PM',
											'strength' => 'Putere',
											'dexterity' => 'Dexteritate',
											'vitality' => 'Vitalitate',
											'intelligence' => 'Inteligenta',
											'steal_hp' => 'Absorbtie PV',
											'steal_sp' => 'Absorbtie PM',
											'arrow_resist' => 'Rezistenta la sageti',
											'fire_resist' => 'Rezistenta la foc',
											'electric_resist' => 'Rezistenta la fulger',
											'magic_resist' => 'Rezistenta la magie',
											'wind_resist' => 'Rezistenta la vant',
											'average' => 'Paguba medie',
											'average_resist' => 'Rezistenta medie la paguba',
											'skill_damage' => 'Paguba competentei',
											'skill_resist' => 'Rezistenta la paguba competentei'
										);
										?>

										<?php $bonusIndex = 0; ?>
										<?php foreach ($fleamarketFilterBonuses as $bonusKey => $bonusLabel): ?>
											<label class="fleamarket-filter-bonus-item<?= $bonusIndex >= 5 ? ' fleamarket-filter-bonus-item-hidden' : ''; ?>">
												<input
													type="checkbox"
													value="<?= htmlspecialchars($bonusKey, ENT_QUOTES, 'UTF-8'); ?>"
													<?= in_array($bonusKey, $activeMarketBonusFilters, true) ? 'checked' : ''; ?>
												>
												<span><?= htmlspecialchars($bonusLabel, ENT_QUOTES, 'UTF-8'); ?></span>
											</label>
											<?php $bonusIndex++; ?>
										<?php endforeach; ?>
									</div>

									<button type="button" class="fleamarket-filter-show-more-button" id="fleamarket-filter-show-more-button">
										Show more
									</button>
									<div class="fleamarket-filter-actions">
										<button type="button" class="fleamarket-filter-action-button" id="fleamarket-filter-apply-button">
											Cauta
										</button>
										<button type="button" class="fleamarket-filter-action-button fleamarket-filter-action-button-reset" id="fleamarket-filter-reset-button">
											Reset filter
										</button>
									</div>
								</div>
							</div>
						</aside>

						<section class="fleamarket-market-content">
							<?php if (!empty($fleamarketBuyError)): ?>
								<div class="login-form-error fleamarket-inline-message">
									<?= htmlspecialchars($fleamarketBuyError, ENT_QUOTES, 'UTF-8'); ?>
								</div>
							<?php endif; ?>

							<?php if (!empty($fleamarketBuySuccess)): ?>
								<div class="login-form-success fleamarket-inline-message">
									<?= htmlspecialchars($fleamarketBuySuccess, ENT_QUOTES, 'UTF-8'); ?>
								</div>
							<?php endif; ?>
							<div class="fleamarket-list-header fleamarket-list-header-market">
								<div>ID</div>
								<div>Vanzator</div>
								<div>Icon</div>
								<div>Nume</div>
								<div>Pret</div>
								<div>Actiune</div>
							</div>

							<div class="fleamarket-list-body">
								<?php if (!empty($publicMarketListings)): ?>
									<?php foreach ($publicMarketListings as $marketItem): ?>
										<?php
											$marketIconPath = $itemIconResolver->getIconPathByVnum($marketItem['item_vnum']);
											$marketItemSize = $itemProtoResolver->getItemSizeByVnum($marketItem['item_vnum']);
											$marketItemName = $itemNameResolver->getNameByVnum($marketItem['item_vnum']);

											$marketItemProtoRow = $itemProtoResolver->getItemByVnum($marketItem['item_vnum']);
											$marketItemFixedBonusLines = array();
											$marketItemExtraBonusLines = $itemAttributeResolver->getExtraBonusLines($marketItem);
											$marketItemSocketRows = array();
											$marketItemDescription = $itemDescResolver->getDescriptionByVnum($marketItem['item_vnum']);

											if (!empty($marketItemProtoRow)) {
												$marketItemFixedBonusLines = $itemFixedBonusResolver->getFixedBonusLines($marketItemProtoRow);
											}

											for ($socketIndex = 0; $socketIndex <= 2; $socketIndex++) {
												$socketKey = 'socket' . $socketIndex;
												$socketVnum = isset($marketItem[$socketKey]) ? (int) $marketItem[$socketKey] : 0;

												if ($socketVnum <= 2) {
													continue;
												}

												$socketName = $itemNameResolver->getNameByVnum($socketVnum);
												$socketProtoRow = $itemProtoResolver->getItemByVnum($socketVnum);
												$socketFixedBonusLines = array();

												if (!empty($socketProtoRow)) {
													$socketFixedBonusLines = $itemFixedBonusResolver->getFixedBonusLines($socketProtoRow);
												}

												$marketItemSocketRows[] = array(
													'name' => !empty($socketName) ? $socketName : ('VNUM ' . $socketVnum),
													'bonus_lines' => $socketFixedBonusLines,
												);
											}
										?>
										<div
											class="fleamarket-row fleamarket-row-market"
											data-market-item-name="<?= htmlspecialchars(!empty($marketItemName) ? $marketItemName : ('VNUM ' . (int) $marketItem['item_vnum']), ENT_QUOTES, 'UTF-8'); ?>"
										>
											<div><?= (int) $marketItem['id']; ?></div>

											<div>
												<div class="fleamarket-seller-name">
													<?= htmlspecialchars($marketItem['owner_name'], ENT_QUOTES, 'UTF-8'); ?>
												</div>
												<div class="fleamarket-seller-reputation">
													Reputatie:
													<?=
													number_format(
														isset($marketSellerReputations[(int) $marketItem['account_id']]) ? (float) $marketSellerReputations[(int) $marketItem['account_id']] : 0,
														2,
														'.',
														''
													);
													?>
												</div>
											</div>

											<div class="fleamarket-item-icon-box fleamarket-market-icon-box">
												<div class="fleamarket-item-icon-stack size-<?= (int) $marketItemSize; ?>">
													<?php for ($i = 0; $i < $marketItemSize; $i++): ?>
														<span class="fleamarket-item-icon-cell"></span>
													<?php endfor; ?>

													<?php if (!empty($marketIconPath)): ?>
														<img src="<?= htmlspecialchars($marketIconPath, ENT_QUOTES, 'UTF-8'); ?>" alt="Item <?= (int) $marketItem['item_vnum']; ?>" class="fleamarket-item-icon-image">
													<?php endif; ?>

													<?php if (!empty($marketItem['item_count']) && (int) $marketItem['item_count'] > 1): ?>
														<span class="fleamarket-item-count-badge">
															<?= (int) $marketItem['item_count']; ?>
														</span>
													<?php endif; ?>
												</div>

												<div class="fleamarket-market-tooltip">
													<div class="fleamarket-market-tooltip-name">
														<?= htmlspecialchars(!empty($marketItemName) ? $marketItemName : ('VNUM ' . (int) $marketItem['item_vnum']), ENT_QUOTES, 'UTF-8'); ?>
													</div>
													<?php if (!empty($marketItemDescription)): ?>
														<div class="fleamarket-market-tooltip-description">
															<?= htmlspecialchars($marketItemDescription, ENT_QUOTES, 'UTF-8'); ?>
														</div>
													<?php endif; ?>
													<?php foreach ($marketItemFixedBonusLines as $fixedBonusLine): ?>
														<div class="fleamarket-market-tooltip-line fleamarket-market-tooltip-line-fixed">
															<?= htmlspecialchars($fixedBonusLine, ENT_QUOTES, 'UTF-8'); ?>
														</div>
													<?php endforeach; ?>

													<?php foreach ($marketItemExtraBonusLines as $extraBonusLine): ?>
														<div class="fleamarket-market-tooltip-line fleamarket-market-tooltip-line-extra">
															<?= htmlspecialchars($extraBonusLine, ENT_QUOTES, 'UTF-8'); ?>
														</div>
													<?php endforeach; ?>

													<?php foreach ($marketItemSocketRows as $socketRow): ?>
														<div class="fleamarket-market-tooltip-line fleamarket-market-tooltip-line-socket">
															<?php
															if (!empty($socketRow['bonus_lines'])) {
																echo htmlspecialchars($socketRow['bonus_lines'][0], ENT_QUOTES, 'UTF-8');
															} else {
																echo htmlspecialchars($socketRow['name'], ENT_QUOTES, 'UTF-8');
															}
															?>
														</div>
													<?php endforeach; ?>
												</div>
											</div>
											<div class="fleamarket-market-item-name">
												<?= htmlspecialchars(!empty($marketItemName) ? $marketItemName : ('VNUM ' . (int) $marketItem['item_vnum']), ENT_QUOTES, 'UTF-8'); ?>
											</div>
											<div class="fleamarket-price"><?= (int) $marketItem['price_md']; ?> MD</div>
											<div>
												<form method="post" action="">
													<input type="hidden" name="fleamarket_buy_form" value="1">
													<input type="hidden" name="listing_id" value="<?= (int) $marketItem['id']; ?>">
													<button type="submit" class="fleamarket-action-button">Cumpara</button>
												</form>
											</div>
										</div>
									<?php endforeach; ?>
								<?php else: ?>
									<div class="fleamarket-empty-state">
										Nu exista iteme disponibile pentru filtrele selectate.
									</div>
								<?php endif; ?>
							</div>
						</section>
					</div>
				</div>
            </div>
        </div>
