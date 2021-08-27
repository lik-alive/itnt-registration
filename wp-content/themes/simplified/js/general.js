// Enable $-commands
var $ = jQuery.noConflict();

/*****DataTable functions*****/
/*{
	//Throw datatable ajax errors (inside "ready" for the widgets are not in the general script load stream)
	$(document).ready(function () {
		if (undefined !== $.fn.dataTable)
			$.fn.dataTable.ext.errMode = 'throw';
	});

	//Hide empty groups (no name)
	if (undefined !== $.fn.dataTable)
		$.fn.dataTable.RowGroup.defaults.emptyDataGroup = '';

	//Mouse click on table rows
	function InitMouseClick(dataTable, idColNo, tolink) {
		dataTable.on('click', 'td', function () {
			let rowNo = dataTable.row($(this).closest('tr')).index();
			if (typeof (rowNo) != "undefined"
				&& $(this).find("img, .btn, select").length == 0) {
				let id = dataTable.cell(rowNo, idColNo).data();
				window.location.href = SITE_URL + tolink + id;
			}
		});
	}

	//Enable row selection
	$('body').on('click', '.dataTable.select tr', function (e) {
		//Prevent selection for links, buttons, etc
		if ($(e.target).is('a, button')) return;

		//Prevent selection for special rows (e.g. chapters)
		if ($(this).hasClass('noeffect')) return;

		let dt = $(this).closest('.dataTable');
		if ($(this).hasClass('selected')) return;
		else {
			dt.find('tr.selected').removeClass('selected');
			$(this).addClass('selected');
		}
		dt.trigger('selectchanged', this);
	});
}*/

/*****String functions*****/
{
    //Contract fullname
    function contractName(str, reverse) {
        reverse = reverse || false;
        var result = '';

        if (str === null) return 'unauthorized';

        if (str.indexOf(' ') < 0) return str;

        str.split(' ').forEach(function(el) {
            if (reverse) {
                if (result.length < 3) result += el[0] + '.';
                else result = el + ' ' + result;
            } else {
                if (result === '') result = el + ' ';
                else result += el[0] + '.';
            }
        });
        return result;
    }

    // Get first name
    function firstName(str) {
        var result = '';

        if (str === null) return 'unauthorized';

        if (str.indexOf(' ') < 0) return str;

        return str.split(' ')[0];
    }

    //Check if string contains russian chars
    function containsRussianChars(str) {
        return /[а-яА-я]+$/.test(str);
    }

    //Change keyboard layout
    function changeKeyboardLayout(str) {
        var str_rus = [
            "й", "ц", "у", "к", "е", "н", "г", "ш", "щ", "з", "х", "ъ",
            "ф", "ы", "в", "а", "п", "р", "о", "л", "д", "ж", "э",
            "я", "ч", "с", "м", "и", "т", "ь", "б", "ю", "ё",
            "Й", "Ц", "У", "К", "Е", "Н", "Г", "Ш", "Щ", "З", "Х", "Ъ",
            "Ф", "Ы", "В", "А", "П", "Р", "О", "Л", "Д", "Ж", "Э",
            "Я", "Ч", "С", "М", "И", "Т", "Ь", "Б", "Ю", "Ё"
        ];
        var str_eng = [
            "q", "w", "e", "r", "t", "y", "u", "i", "o", "p", "[", "]",
            "a", "s", "d", "f", "g", "h", "j", "k", "l", ";", "'",
            "z", "x", "c", "v", "b", "n", "m", ",", ".", "`",
            "Q", "W", "E", "R", "T", "Y", "U", "I", "O", "P", "{", "}",
            "A", "S", "D", "F", "G", "H", "J", "K", "L", ":", "\"",
            "Z", "X", "C", "V", "B", "N", "M", "<", ">", "~"
        ];

        if (containsRussianChars(str)) revert = str.replace(/[а-яА-ЯёЁ]/g, function(match) { return str_eng[str_rus.indexOf(match)]; });
        else revert = str.replace(/[a-zA-Z\[\];',.`{}:"<>~]/g, function(match) { return str_rus[str_eng.indexOf(match)]; });
        return revert;
    }

    //Escape regexp string
    function escapeRegExp(str) {
        if (str === null || str === undefined) return str;
        return str.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'); // $& means the whole matched string
    }

    //Convert unicode to char
    function unicodeToChar(str) {
        return str.replace(/\\u[\dA-F]{4}/gi,
            function(match) {
                return String.fromCharCode(parseInt(match.replace(/\\u/g, ''), 16));
            });
    }

    // Remove string escapes from JSON
    function removeJSONEscapes(str) {
        if (str === null || str === undefined) return str;

        return str.replace(/\\"/g, '"');
    }

    //Escape HTML characters in a string
    function escapeHtml(str) {
        if (str === null || str === undefined) return str;
        return str
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    //Convert null-value to empty string
    function nullToEmpty(val) {
        return (val === null) ? '' : val;
    }

    //Auto-trim for input text
    $('body').on('change', 'input[type=text], textarea:not(.notrim)', function() {
        $(this).val($(this).val().trim());
    });

    //Textarea disable line breaks
    $('.nolinebreaks').on('change keyup', function() {
        str = $(this).val();
        if (str.endsWith('\n')) str = str.slice(0, str.length - 1);
        $(this).val(str.replace(/\n/g, ' '));
    });

    //Autosize textarea
    (function($) {
        $.fn.autoResize = function(options) {
            // Just some abstracted details,
            // to make plugin users happy:
            var settings = $.extend({
                animate: true,
                animateDuration: 150,
                animateCallback: function() {},
                limit: 1000
            }, options);

            this.filter('textarea').each(function() {
                var textarea = $(this),
                    origHeight = textarea.height();

                textarea.height(0).scrollTop(10000);
                var scrollTop = textarea.scrollTop() + 26;

                if (scrollTop >= settings.limit) {
                    $(this).css('overflow-y', '');
                    return;
                }

                // Extra height for animate (include padding)
                var extra = 12;

                textarea.height(origHeight).scrollTop(-10000);

                if (settings.animate && textarea.css('display') === 'block') {
                    textarea.stop().animate({ height: scrollTop + extra }, settings.animateDuration, settings.animateCallback);
                } else {
                    textarea.height(scrollTop);
                }
            });

            return this;
        };
    })(jQuery);

    // Autoresize textareas
    $('.autoresize').each(function() {
        $(this).autoResize({ animate: false });
    });

    // Copy string to clipboard
    function copyToClipboard(str) {
        let el = document.createElement('textarea');
        el.value = str;
        el.setAttribute('readonly', '');
        el.style.position = 'absolute';
        el.style.left = '-9999px';
        document.body.appendChild(el);
        el.select();
        document.execCommand('copy');
        document.body.removeChild(el);
    };

    // Enable tab in textareas
    $('body').on('keydown', 'textarea.allowtab', function(e) {
        let keyCode = e.keyCode || e.which;

        if (keyCode == 9) {
            e.preventDefault();
            let start = this.selectionStart;
            let end = this.selectionEnd;

            // set textarea value to: text before caret + tab + text after caret
            $(this).val($(this).val().substring(0, start) +
                "\t" +
                $(this).val().substring(end));

            // put caret at right position again
            this.selectionStart = this.selectionEnd = start + 1;
        }
    });
}

/*****Date functions*****/
{
    //Get months names in russian
    function GetMonthName(no) {
        let monthNames = [
            'января', 'февраля', 'марта', 'апреля',
            'мая', 'июня', 'июля', 'августа',
            'сентября', 'октября', 'ноября', 'декабря'
        ];

        return monthNames[no];
    }

    //Get difference between two dates in days
    function dateDiffDays(date1, date2) {
        return parseInt((date2 - date1) / (1000 * 60 * 60 * 24));
    }

    //Parse date from str without timezone offset
    function parseDate(str) {
        let date = new Date(str);
        let userTimezoneOffset = date.getTimezoneOffset() * 60000;
        date = new Date(date.getTime() + userTimezoneOffset);
        return date;
    }

    //Prevent future dates
    $('body').on('change', 'input[type=date]:not(.allowfuture)', function() {
        if (parseDate(this.value) > new Date()) {
            AddStatusMsg([2, 'Выбрана дата из будущего']);
            this.value = '';
        }
    });
}

/*****Status message*****/
{
    //Close message on button click
    $('body').on('click', '.alert .close', function() {
        var alertmsg = $(this).closest('.alert');
        alertmsg.addClass('sliding');
        alertmsg.slideToggle();
        setTimeout(function() {
            alertmsg.remove();
        }, 400);
    });

    //Get current status-container
    function GetActiveStatusContainer() {
        let container = $('#page-status');
        let maxZ = 0;
        $('.modal.show').each(function() {
            if ($(this).css('z-index') > maxZ) {
                maxZ = $(this).css('z-index');
                container = $(this).find('.status-container');
            }
        });

        return container;
    }

    // Create alert message
    function createAlertMsg(data, closeBtn, closeCircle) {
        var statusType = 'info';
        if (data[0] === 1) {
            statusType = 'success';
        } else if (data[0] === 2) {
            statusType = 'danger';
        }

        let btn = closeBtn ? "<button type='button' class='close'><span>&times;</span></button>" : '';

        var alertmsg = $(
            "<div class='alert mb-1 sliding alert-" + statusType + " border-" + statusType + "' style='display:none'>" + data[1] + " " + btn + "</div>"
        );

        return alertmsg;
    }

    //Show message on top of the screen (array(status, msg))
    function AddStatusMsg(data, pars) {
        pars = pars || {};
        // Set default parameters
        if (undefined === pars.isautoclose) pars.isautoclose = true;
        if (undefined === pars.isanimated) pars.isanimated = true;
        if (undefined === pars.isscroll) pars.isscroll = true;

        var container = GetActiveStatusContainer();

        //Show no more than 3 msgs
        if (container.children().length > 2) {
            container.find('.alert:not(.sliding)').eq(0).find('.close').trigger('click');
        }

        let alertmsg = createAlertMsg(data, true, pars.isautoclose);
        container.append(alertmsg);

        //Animate show
        if (pars.isanimated) {
            alertmsg.slideToggle();
        } else {
            alertmsg.show();
        }

        //Scroll to top
        if (pars.isscroll) {
            let modal = container.closest('.modal');
            if (0 !== modal.length) modal.animate({ scrollTop: 0 }, 500);
            else $('html, body').animate({ scrollTop: 0 }, 500);
        }

        //Autoclose message
        if (pars.isautoclose) {
            setTimeout(function() {
                alertmsg.find('.close').trigger('click');
            }, 10000);
        }
    }

    //Show fixed message on top of the screen (array(status, msg))
    function AddStatusFixedMsg(data) {
        var container = $('#page-status-fixed');

        //Show no more than 1 msgs
        if (container.children().length > 0) {
            setTimeout(function() { AddStatusFixedMsg(data); }, 500);
            return;
        }

        let alertmsg = createAlertMsg(data, false, false);
        container.append(alertmsg);
        alertmsg.show();

        // Start animation
        container.addClass('show');

        //Autoclose message
        setTimeout(function() {
            alertmsg.remove();
            container.removeClass('show');
        }, 3500);
    }
}

/*****Smart Form*****/
{
    //Form validation
    $('.needs-validation').submit(function(e) {
        this.classList.remove('not-valid');

        //Un-require all hidden input fields
        $(this).find(':input[required]:not(:visible)').each(function() {
            $(this).prop('required', false);
            $(this).data('required', true);
        });

        //Re-require all shown input fields
        $(this).find(':input:visible').each(function() {
            if ($(this).data('required')) $(this).prop('required', true);
        });

        //Check validity
        if (false === this.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
            this.classList.add('not-valid');
        }

        this.classList.add('was-validated');
    });
}

/*****Modals*****/
{
    //Clear all inputs inside the modal
    function ClearModal(modal) {
        modal.find('.status-container').empty();
        modal.find('form').removeClass('was-validated not-valid');
        modal.find('input[type=date]').val('').trigger('change');

        modal.find('.custom-switch input').each(function() {
            $(this).prop('checked', $(this).attr('checked'));
        });

        modal.find('l-pickdate .active').removeClass('active');

        modal.find('l-select-group').each(function() {
            $(this).val($(this).attr('value')).trigger('change');
        });

        modal.find('select').each(function() {
            $(this).find('option').each(function() {
                $(this).prop('selected', $(this).attr('selected'));
            });
        });
        modal.find('.is-invalid').removeClass('is-invalid');
        modal.find('.not-required').removeClass('not-required');
    }

    //Scroll into view on collapse
    //NOTE Работает на чёртовой магии. И хер с ним... Не лезь.
    $('body').on('shown.bs.collapse', '.collapse', function(e) {
        // Prevent raising event in parents
        e.stopPropagation();
        //this.scrollIntoView({block: 'end', behavior: 'smooth'});
        let target = $(this);
        let targetTop = target.offset().top;
        let targetHeight = target.height();
        let windowTop = jQuery(window).scrollTop();
        //let windowHeight = Math.min(window.innerHeight, document.documentElement.clientHeight);
        let windowHeight = window.innerHeight;
        let navbarHeight = $('#navbar').outerHeight();
        let headerHeight = 0;
        if (target.prev().is('.collapser')) headerHeight = target.prev().outerHeight();

        if (targetTop < windowTop || targetHeight + headerHeight + navbarHeight > windowHeight) {
            $('html,body').animate({ scrollTop: targetTop - headerHeight - navbarHeight - 5 });
        } else if (targetTop + targetHeight > windowTop + windowHeight) {
            $('html,body').animate({ scrollTop: targetTop + targetHeight - windowHeight + 10 });
        }
    });

    //Nested modals
    $('body').on('show.bs.modal', '.modal', function() {
        var zIndex = 4000;
        $('.modal:visible').each(function() {
            zIndex = Math.max(zIndex, $(this).css('z-index'));
        });
        zIndex += 10;
        $(this).css('z-index', zIndex);

        setTimeout(function() {
            $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
        }, 0);
    });

    //Nested modals - Prevent close modal-mode if one of back modals was closed
    $('body').on('hidden.bs.modal', function(event) {
        if ($('.modal.show').length > 0) {
            $('body').addClass('modal-open');
            $('body').css('padding-right', '17px');
            $('.fixed-top').css('padding-right', '30.3333px');
        }
    });
}

//*****Others*****
{

    //Periodically check logged_in status and open login page on session closed
    /*setInterval(function () {
    	$.post(ADMIN_URL, { action: 'is_user_logged_in_json' }, function (response) {
    		if (!JSON.parse(response)) {
    			$('#loginModal').modal({ backdrop: 'static', keyboard: false });
    			//window.location.href = SITE_URL + '/login/?redirect_to=' + window.location.href;
    		} else {
    			if ($('#loginModal').is(':visible')) $('#loginModal').modal('hide');
    		}
    	});
    }, 30 * 1000);*/

    //Hide widget panel if nothing inside
    if ($('.widgets-panel .info-panel').length === 0) $('.widgets-panel').hide();

    /**
     * Safely change collapse stage
     * @param {*} div 
     * @param {*} type 
     */
    function safeCollapse(div, type) {
        if (div.hasClass('collapsing')) setTimeout(function() { safeCollapse(div, type) }, 100);
        else div.collapse(type);
    }
}