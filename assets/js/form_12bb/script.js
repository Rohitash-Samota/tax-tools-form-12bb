(function () {
	function ready(fn) {
		if (document.readyState !== "loading") fn();
		else document.addEventListener("DOMContentLoaded", fn);
	}

	ready(function () {
		if (!window.jQuery) return;
		var $ = window.jQuery;

		var $form = $('form[action*="tax-tools"]');
		var saveUrl = $form.attr("action");
		var loadUrl = "/tax-tools/form-12bb/load";

		var STEP_MAP = {
			tab1: "employee_details",
			tab2: "housing_rent_allowance",
			tab3: "leave_travel_concession",
			tab4: "interest_on_loan",
			tab5: "deductions",
		};

		function getCsrf() {
			var $h = $form.find('input[type="hidden"][data-csrf]');
			var name = $h.attr("name");
			var hash = $h.val();
			if (!name || !hash) return {};
			var obj = {};
			obj[name] = hash; // send as dynamic field name
			return obj;
		}

		function setCsrf(csrf) {
			if (!csrf || !csrf.name || !csrf.hash) return;
			var $h = $form.find('input[type="hidden"][data-csrf]');
			if ($h.length) {
				$h.attr("name", csrf.name).val(csrf.hash);
			} else {
				$("<input>", {
					type: "hidden",
					"data-csrf": true,
					name: csrf.name,
					value: csrf.hash,
				}).appendTo($form);
			}
			$.ajaxSetup({ headers: { "X-CSRF-TOKEN": csrf.hash } });
		}

		function fieldSelector(stepId, key) {
			var nested = stepId + "[" + key + "]";
			return (
				'[name="' +
				nested +
				'"],' +
				'[name="' +
				nested +
				'[]"],' +
				'[name="' +
				key +
				'"],' +
				'[name="' +
				key +
				'[]"]'
			);
		}

		function clearStepErrors(stepId) {
			var $scope = $("#" + stepId);
			$scope.find(".is-invalid").removeClass("is-invalid");
			$scope.find(".invalid-feedback.validation-msg").remove();
		}

		function showStepErrors(stepId, errors) {
			var $scope = $("#" + stepId);
			clearStepErrors(stepId);

			var firstInvalid = null;

			Object.keys(errors || {}).forEach(function (key) {
				var sel = fieldSelector(stepId, key);
				var $fields = $scope.find(sel);

				if (!$fields.length) {
					if (!$scope.find(".step-level-validation").length) {
						$scope.prepend(
							'<div class="alert alert-danger step-level-validation"></div>'
						);
					}
					$scope
						.find(".step-level-validation")
						.append($("<div/>").text(errors[key]));
					return;
				}

				$fields.each(function (idx, el) {
					var $el = $(el);
					var $container = $el.closest(".form-group, .mb-3, .form-field").length
						? $el.closest(".form-group, .mb-3, .form-field")
						: $el;

					$el.addClass("is-invalid");
					if (!$container.find(".invalid-feedback.validation-msg").length) {
						$container.append(
							'<div class="invalid-feedback validation-msg" style="display:block;"></div>'
						);
					}
					$container.find(".invalid-feedback.validation-msg").text(errors[key]);

					if (!firstInvalid) firstInvalid = $el;
				});
			});

			if (firstInvalid && firstInvalid.length) {
				firstInvalid.trigger("focus");
				$("html, body").animate(
					{ scrollTop: Math.max(0, firstInvalid.offset().top - 120) },
					300
				);
			}
		}

		function showStepMessage(stepId, type, text) {
			var $scope = $("#" + stepId);
			$scope.find(".step-flash").remove();
			$scope.prepend(
				'<div class="alert alert-' +
					type +
					' step-flash" role="alert">' +
					$("<div/>").text(text).html() +
					"</div>"
			);
		}
		function activeTab() {
			return $(".nav-tabs > li.active > a").attr("href").replace("#", "");
		}
		function stepKey(tabId) {
			return STEP_MAP[tabId] || tabId;
		}
		function goToTab(tabId) {
			$('.nav-tabs a[href="#' + tabId + '"]').trigger("click");
		}

		function updateCsrfFromResponse() {
			return $.ajax({
				url: "/csrf-token",
				method: "GET",
				dataType: "json",
			}).done(function (res) {
				if (res && res.name && res.hash) {
					setCsrf(res);
				}
			});
		}

		function serializeCurrentTab(tabId) {
			var $pane = $("#" + tabId);
			var data = {};
			var formId = $form.find('[name="form_id"]').val();
			if (formId) data.form_id = formId;

			data.step = stepKey(tabId);

			$pane.find("input, select, textarea").each(function () {
				var $el = $(this);
				var name = $el.attr("name");
				if (!name) return;
				if ($el.is(":checkbox")) {
					if (!data[name]) data[name] = [];
					if ($el.prop("checked")) data[name].push($el.val());
				} else if ($el.is(":radio")) {
					if ($el.prop("checked")) data[name] = $el.val();
				} else {
					data[name] = $el.val();
				}
			});

			data._dynamic_indexes = {
				eightyCIndex: $(".items .item.item-1").length,
				otherIndex: $(".items .item.item-2").length,
			};

			return data;
		}

		function fillTabFromData(tabId, payload) {
			if (!payload || typeof payload !== "object") return;
			var $pane = $("#" + tabId);

			if (payload._dynamic_indexes) {
				var need80C = +payload._dynamic_indexes.eightyCIndex || 1;
				var needOTH = +payload._dynamic_indexes.otherIndex || 1;

				while ($pane.find(".item.item-1").length < need80C) {
					$pane.find('.addItem[data-target="80C"]').trigger("click");
				}
				while ($pane.find(".item.item-2").length < needOTH) {
					$pane.find('.addItem[data-target="OTHER"]').trigger("click");
				}
			}

			Object.keys(payload).forEach(function (name) {
				if (
					name === "_dynamic_indexes" ||
					name === "step" ||
					name === "form_id"
				)
					return;

				var $fields = $pane.find('[name="' + name + '"]');
				if (!$fields.length) return;

				var val = payload[name];

				$fields.each(function () {
					var $el = $(this);
					if ($el.is(":checkbox")) {
						if (Array.isArray(val)) {
							$el.prop("checked", val.indexOf($el.val()) !== -1);
						} else {
							$el.prop("checked", !!val);
						}
					} else if ($el.is(":radio")) {
						$el.prop("checked", $el.val() == val);
					} else {
						$el.val(val);
					}
				});
			});
		}

		function saveStep(tabId) {
			var data = serializeCurrentTab(tabId);
			var payload = $.extend({}, getCsrf(), data);

			var doPost = function () {
				return $.ajax({
					url: saveUrl,
					method: "POST",
					data: payload,
					dataType: "json",
				});
			};

			return doPost()
				.done(function (res) {
					if (res && res.form_id) {
						var $formId = $form.find('[name="form_id"]');
						if ($formId.length) $formId.val(res.form_id);
						else
							$("<input>", {
								type: "hidden",
								name: "form_id",
								value: res.form_id,
							}).appendTo($form);
					}

					if (res && res.csrf && res.csrf.name && res.csrf.hash) {
						setCsrf(res.csrf);
					} else {
						updateCsrfFromResponse();
					}

					if (res && res.status === "failed") {
						showStepErrors(tabId, res.errors || {});
						if (res.message) showStepMessage(tabId, "danger", res.message);
					} else {
						clearStepErrors(tabId);
						if (res && res.message ) {
							if(tabId === 'tab5' && res.status === 'success' && res){
							}
							showStepMessage(tabId, "success", res.message);
						}
					}
				})
				.then(function (res) {
					if (res && res.status === "failed") {
						return { ok: false, res: res };
					}
					return { ok: true, res: res };
				})
				.fail(function (xhr) {
					if (xhr && xhr.status === 403) {
						return updateCsrfFromResponse().then(function () {
							payload = $.extend({}, getCsrf(), data);
							return doPost().then(function (res) {
								if (res && res.csrf && res.csrf.name && res.csrf.hash) {
									setCsrf(res.csrf);
								}
								if (res && res.status === "failed") {
									showStepErrors(tabId, res.errors || {});
									if (res.message)
										showStepMessage(tabId, "danger", res.message);
									return { ok: false, res: res };
								}
								clearStepErrors(tabId);
								return { ok: true, res: res };
							});
						});
					}
					updateCsrfFromResponse();
					showStepMessage(
						tabId,
						"danger",
						"Something went wrong. Please try again."
					);
					return { ok: false };
				});
		}

		function loadStep(tabId) {
			var params = {
				step: stepKey(tabId),
			};
			var formId = $form.find('[name="form_id"]').val();
			if (!formId) {
				return;
			}
			if (formId) params.form_id = formId;

			return $.ajax({
				url: loadUrl,
				method: "GET",
				data: params,
				dataType: "json",
			})
				.then(function (res) {
					if (res && res.data) fillTabFromData(tabId, res.data);
				})
				.catch(function () {
				});
		}

		$('[data-toggle="tooltip"]').tooltip();
		$(".print-button").on("click", function (e) {
			e.preventDefault();
			window.print();
		});

		var eightyCIndex = 1,
			otherIndex = 1;
		$(document).on("click", ".addItem", function () {
			var target = $(this).data("target");
			if (target === "80C") {
				eightyCIndex++;
				var block = [
					'<div class="row item item-1">',
					'  <div class="col-md-12 col-sm-12 col-xs-12"> <span class="inlineDel delete hideable">-</span> </div>',
					'  <div class="col-md-4 col-sm-12 col-xs-12 form-group">',
					"    <label> &nbsp;</label>",
					'    <select name="dedn_eighty_c[' +
						eightyCIndex +
						']" class="form-control" id="dedn_eighty_c_' +
						eightyCIndex +
						'">',
					'      <option value="" selected>Select</option>',
					'      <option value="Life Insurance Premium">Life Insurance Premium</option>',
					'      <option value="Investment in Tax Saving Fixed Deposit">Investment in Tax Saving Fixed Deposit</option>',
					'      <option value="Investment in Tax Saving Mutual Fund">Investment in Tax Saving Mutual Fund</option>',
					'      <option value="Investment in PPF (Public Provident Fund)">Investment in PPF (Public Provident Fund)</option>',
					'      <option value="Childrens Tuition Fees">Childrens Tuition Fees</option>',
					'      <option value="Principal repayment of Home Loan">Principal repayment of Home Loan</option>',
					'      <option value="Others">Others</option>',
					"    </select>",
					"  </div>",
					'  <div class="col-md-4 col-sm-6 col-xs-12 form-group">',
					"    <label>Amount</label>",
					'    <input type="number" name="dedn_eighty_c_amount[' +
						eightyCIndex +
						']" min="0" max="100000000" step="1" class="form-control" placeholder="Amount" id="dedn_eighty_c_amount_' +
						eightyCIndex +
						'">',
					"  </div>",
					'  <div class="col-md-4 col-sm-6 col-xs-12 form-group">',
					"    <label>Evidence</label>",
					'    <input type="text" name="dedn_eighty_c_evidence[' +
						eightyCIndex +
						']" class="form-control alpha_dash_space" placeholder="Evidence" id="dedn_eighty_c_evidence_' +
						eightyCIndex +
						'">',
					"  </div>",
					"</div>",
				].join("");
				$(this).closest(".items").find(".row:last").before(block);
			} else if (target === "OTHER") {
				otherIndex++;
				var block2 = [
					'<div class="row item item-2">',
					'  <div class="col-md-12 col-sm-12 col-xs-12"> <span class="inlineDel delete hideable">-</span> </div>',
					'  <div class="col-md-4 col-sm-12 col-xs-12 form-group">',
					"    <label> &nbsp;</label>",
					'    <select name="dedn_other[' +
						otherIndex +
						']" class="form-control" id="dedn_other_' +
						otherIndex +
						'">',
					'      <option value="" selected>Select</option>',
					'      <option value="Sec 80CCC - Deduction for contribution to Certain Pension Funds">Sec 80CCC - Deduction for contribution to Certain Pension Funds</option>',
					'      <option value="Sec 80CCD - Contribution to NPS">Sec 80CCD - Contribution to NPS</option>',
					'      <option value="Sec 80D - Deduction for Health Insurance Premium">Sec 80D - Deduction for Health Insurance Premium</option>',
					'      <option value="Sec 80DD - Deduction for Dependent Disabled">Sec 80DD - Deduction for Dependent Disabled</option>',
					'      <option value="Sec 80E - Deduction for Interest on Eductaion Loan">Sec 80E - Deduction for Interest on Eductaion Loan</option>',
					'      <option value="Sec 80G - Deduction for Donations">Sec 80G - Deduction for Donations</option>',
					"    </select>",
					"  </div>",
					'  <div class="col-md-4 col-sm-6 col-xs-12 form-group">',
					"    <label>Amount</label>",
					'    <input type="number" name="dedn_other_amount[' +
						otherIndex +
						']" min="0" max="100000000" step="1" class="form-control" placeholder="Amount" id="dedn_other_amount_' +
						otherIndex +
						'">',
					"  </div>",
					'  <div class="col-md-4 col-sm-6 col-xs-12 form-group">',
					"    <label>Evidence</label>",
					'    <input type="text" name="dedn_other_evidence[' +
						otherIndex +
						']" class="form-control alpha_dash_space" placeholder="Evidence" id="dedn_other_evidence_' +
						otherIndex +
						'">',
					"  </div>",
					"</div>",
				].join("");
				$(this).closest(".items").find(".row:last").before(block2);
			}
		});

		$(document).on("click", ".inlineDel.delete", function () {
			var $item = $(this).closest(".item");
			if ($item.siblings(".item").length >= 1) $item.remove();
		});

		$(".btnNext").on("click", function (e) {
			e.preventDefault();
			var current = activeTab();
			var $nextLi = $(".nav-tabs > .active").next("li");
			if (!$nextLi.length && current != 'tab5') return;

			saveStep(current).always(function (result) {
				var ok = result && result.ok;
				if (!ok) {
					return;
				}
				var nextId = $nextLi.find("a").attr("href").replace("#", "");
				goToTab(nextId);
				loadStep(nextId);
			});
		});

		$(".btnPrevious").on("click", function (e) {
			e.preventDefault();
			var current = activeTab();
			var $prevLi = $(".nav-tabs > .active").prev("li");
			if (!$prevLi.length) return;

			var prevId = $prevLi.find("a").attr("href").replace("#", "");
			goToTab(prevId);
			loadStep(prevId);
		});

		$(".nav-tabs a").on("shown.bs.tab", function (e) {
			var tabId = $(e.target).attr("href").replace("#", "");
			loadStep(tabId);
		});
	});
})();
