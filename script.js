$(document).ready(function() {

	$("body").click(function(e) {
		if (!$(e.target).hasClass("option")) $(".options").hide();
		else {
			$(".options:visible").each(function() {
				if (!$(this).parents(".selector").has($(e.target)).length) $(this).hide();
			});
		}
	});

	$(".selector").select();

	$(".tabPane").tab();
	
	$("section#schedule .scheduleItem").click(function() {
		$("section#schedule .scheduleItem").removeClass("selected");
		$(this).addClass("selected");
	});
	
});




(function($) {
	if (!("ontouchstart" in window)) {
		return;
	}

	var clickbuster = {
		isLocked: false,
		delayedUnlock: null,
		onClick: function(event) {
			if (this.isLocked) {
				event.stopPropagation();
				event.preventDefault();
			}
		},
		lock: function() {
			this.isLocked = true;
			var clickbuster = this;
			this.delayedUnlock = setTimeout(function() {
				clickbuster.unlock();
			}, 2000);
		},
		unlock: function() {
			this.isLocked = false;
			if (this.delayedUnlock) {
				window.clearTimeout(this.delayedUnlock);
			}
		}
	};
	document.addEventListener('click', function(e) {
		clickbuster.onClick(e);
	}, true);





	$.event.special.click = {
		delegateType: "click",
		bindType: "click",
		setup: function(data, namespaces, eventHandle) {
			var element = this;
			var touchHandler = {
				handleEvent: function(e) {
					switch(e.type) {
						case 'touchstart': this.onTouchStart(e); break;
						case 'touchmove': this.onTouchMove(e); break;
						case 'touchend': this.onTouchEnd(e); break;
					}
				},
				onTouchStart: function(e) {
					e.stopPropagation();
					this.moved = false;
					element.addEventListener('touchmove', this, false);
					element.addEventListener('touchend', this, false);
				},
				onTouchMove: function(e) {
					this.moved = true;
				},
				onTouchEnd: function(e) {
					element.removeEventListener('touchmove', this, false);
					element.removeEventListener('touchend', this, false);

					if (!this.moved) {
						clickbuster.unlock();

						var theEvent = document.createEvent('MouseEvents');
						theEvent.initEvent('click', true, true);
						e.target.dispatchEvent(theEvent);

						clickbuster.lock();

						e.stopPropagation();
					}
				}
			};

			element.addEventListener('touchstart', touchHandler, false);

			$(element).data('touchToClick-handler', touchHandler);

			return false;
		},
		teardown: function(namespaces) {
			var element = this;
			var touchHandler = $(element).data('touchToClick-handler');
			element.removeEventListener('touchstart', touchHandler, false);

			return false;
		}
	};
})(jQuery);


$.fn.select = function() {
	this.each(function() {
		var $selector = $(this),
		$menu = $('<div class="options"></div>');
				
		$selector.append($menu);
			
		$selector.find(".option").each(function() {
			var $option = $(this);
			$menu.append($option);
			$option.click(function() {
				if ($(this).parent().hasClass("options")) {
					$selector.children(".option").remove();
					$(this).parent().hide().children().removeClass("selected");
					$(this).addClass("selected").clone(true).removeClass("selected").insertBefore($menu);
				}
				else {
					$(this).siblings(".options").toggle();
				}
			});
			if ($option.hasClass("selected")) {
				$option.clone(true).removeClass("selected").insertBefore($menu);
			}
		});
	});
}

$.fn.tab = function() {
	this.each(function() {
		var $pane = $(this),
			$tabs = $pane.find(".tab"),
			$contents = $pane.find(".content"),
			$selectedTab = $tabs.filter(".selected:last");
		
		$contents.hide().eq($tabs.index($selectedTab)).show();
		
		$tabs.click(function() {
			$tabs.removeClass("selected");
			$(this).addClass("selected");
			$contents.hide().eq($tabs.index($(this))).show();
		});
	});
}