$(document).ready(function(){
    Core.fullLoad();
});

Core = new function () {

    this.domain = '//' + document.location.hostname + '/';

    this.fetchedMedia = {};

    this.getInputData = function (inputs_container, success_func, fail_func) {
        if (inputs_container != undefined && inputs_container != '') {
            var form_values = {},
                elements = this.getInputsList(inputs_container);

            var valid = true;
            for (var i = 0; i <= elements.length; i++) {

                var $this = $(elements[i]),
                    val = undefined;

                // Имя есть - обрабатываем.
                if ($this.attr('name') != undefined && $this.attr('data-dontsend') !== 'true') {
                    var isValid = Validation.getInstance().validate($this);
                    if (isValid != 'ok') {
                        valid = false;
                    }

                    // Если имя имеет [] то шлём серверу массив.
                    if ($this.attr('name').indexOf('[]') !== -1) {
                        // Если массив для хранения выбранных елементов не создан создадим его.
                        if (form_values[$this.attr('name')] == undefined) {
                            form_values[$this.attr('name')] = [];
                        }
                    }

                    switch($this.attr('type')) {
                        // Радио и чекбоксы надо проверить на "чекнутость" перед добавлением
                        case 'radio':
                        case 'checkbox':
                            if ($this.is(':checked')) {
                                val = $this.val();
                            }
                        break;
                        default:
                            val = $this.val();
                        break;
                    }

                    // Если это textarea и к ней прикреплен редактор - получим его код
                    if ($this.get(0).tagName == 'TEXTAREA' && typeof(CKEDITOR) != 'undefined' && CKEDITOR.instances[$this.attr('name')]) {
                        val = CKEDITOR.instances[$this.attr('name')].getData();
                    }

                    if (typeof(form_values[$this.attr('name')]) == 'object' && val != undefined) {
                        form_values[$this.attr('name')].push(val);
                    } else if (val != undefined) {
                        form_values[$this.attr('name')] = val;
                    }
                }
            }

            if (valid) {
                if (typeof success_func === 'function') {
                    success_func(form_values);
                }
                return form_values;
            } else {
                if (typeof fail_func === 'function') {
                    fail_func(isValid);
                }
                return false;
            }
        }
    };

    // Очистить от данных все инпуты в указанном контейнере
    this.clearInputData = function(inputs_container) {
        this.getInputsList(inputs_container).map(function(){
            var $this = $(this);
            if ($this.attr('type') !== 'hidden') {
                $this.val('');
            }
        });
    };

    /**
     * Вытащить все инпуты из указанного контейнера
     */
    this.getInputsList = function(inputs_container) {
        return $(inputs_container).find('input, select, textarea');
    };

    /**
     * Создаем выбиралку некоторых данных с поиском по аяксу.
     * Запрос, направляемый по URL, должен возвратить JSON-массив с объектами, у которых должны быть
     * свойства id и name. Код отправляет на сервер поисковую строку в POST-параметре $_POST['data']['q']
     * @param {type} el
     * @param {type} url
     * @returns {undefined}
     */
    this.ajaxSelect = function(el,url,name_getter) {
        $(el).ajaxChosen({
            dataType: 'json',
            type: 'POST',
            url: url,
        },{
            processItems: function(data) {
                var results = [];
                $.each(data, function (i, val) {
                    var name = val.name;
                    if (typeof(name_getter) === 'function') {
                        name = name_getter(val);
                    }
                    results.push({
                        id: val.id,
                        text: name
                    });
                });
                return results;
            },
            /*generateUrl: function(){
                if (param !== undefined){
                    new_url = url + $(param).val();
                } else new_url = url;
                return new_url;
            },*/
            loadingImg: '/themes/js/vendor/chosen/loading.gif',
            minLength: 3
        });
    };

    this.token = function () {
        return CORE.token;
    };

    // Специальный метод, с помощью которого можно реализовать упрежденное действие.
    // Зачастую, его удобно использовать тогда, когда человек вводит какой-либо текст в поле,
    // и когда он завершит ввод, необходимо будет провести проверку на стороне сервера
    // (допустим, свободен ли указаннный ник в системе)
    this.wait = function (text, on_timer, on_keypress, event, element, timeout) {
        if (typeof(timeout) === 'undefined') timeout = 1000;
        //if (typeof(timeout))
        if (this.lastSearch === text)
            return;

        this.lastSearch = text;

        if (typeof(event) === 'undefined')
            var keyCode = 1000;
        else
            var keyCode = event.keyCode;

        // Если не была нажата какая-нибудь командная клавиша (типа alt, стрелок и т.д.)
        if (!in_array(keyCode,[33,34,35,36,37,38,39,40,16,17,18,20,27,116],true)) {
            if (this.searchTimeout != null)
                clearTimeout(this.searchTimeout);

            if (typeof(on_keypress) == 'function') {
                on_keypress.call(element);
            }

            // Если был нажат Enter - запустим поиск сразу
            if (keyCode == 13 || keyCode == 1000) {
                on_timer.call(element);
            } else {
                this.searchTimeout = setTimeout(function () {
                    on_timer.call(element);
                }, timeout);
            }
        }
    };

    this.widths = {
        itsTablet     : function() { return window.innerWidth <= CORE.widths.tablet },
        itsPhone      : function() { return window.innerWidth <= CORE.widths.phone },
        itsSmallPhone : function() { return window.innerWidth <= CORE.widths.smallPhone }
    };

    /**
     * Возвращает true, когда страница будет полностью загружена
     * @returns {Boolean}
     */
    this.onFullLoad = function(func) {
        if (this.onFullLoadInited) {
            func();
        } else {
            this.onFullLoadFuncs.push(func);
        }
    };
    this.onFullLoadFuncs = [];
    this.onFullLoadInited = false;

    this.fullLoad = function() {
        this.onFullLoadFuncs.map(function(el){
            el();
        });
        this.onFullLoadInited = true;
    };

    // Переключить флажок фильтрации и обновить страницу с учетом новых параметров
    this.filterbox = function() {
        var get_params = GetToArray();
        $('.filterbox input[type="checkbox"]').each(function(){
            get_params[$(this).attr('name')] = $(this).is(':checked');
        });
        Navigation.loadPage(location.pathname + ArrayToGet(get_params));
    };

    /**
     * Функция проверяет, находится ли текущий пользователь в данный момент на странице своего профиля,
     * или же он находится на какой-то другой странице
     */
    this.onOwnProfile = function() {
        if(CORE.user_id != 0) {
            if (CORE.user.is_organization === true) {
                return CORE.user_id !== '0' && (location.pathname + '/').indexOf('/org' + CORE.user.organization_id + '/') === 0;
            } else {
                return CORE.user_id !== '0' && (location.pathname + '/').indexOf('/id' + CORE.user_id + '/') === 0;
            }
        }
    };

    this.debug = false;

   /**
     * Метод подключает js к документу и выполняет callback функцию
     * options - object, параметры для fetch
     * callback - function
     * params - object
     */
    this.fetchScript = function(options, callback, params){

        //Если скрипт уже загружался, просто выполним callback
        if(typeof this.fetchedMedia[options.sName] != "undefined"){ callback(params);  return true; }

        if(typeof options.stamp == "undefined") options.stamp = '';

        var s = document.createElement('script'); s.async = true; s.defer = true;
        s.src = "/themes/packed/one/"+options.sName+".js"+options.stamp;
        document.body.appendChild(s);

        s.onload = function(){
            try{ callback(params);} catch (e){console.log('Error', e); return false;}
            Core.fetchedMedia[options.sName] = true;
        };

    };
};

Core.onFullLoad(function() {

    ISO_8601_FORMAT = 'YYYY-MM-DD HH:mm'; // Формат даты, который используется во всех временных метках системы

    $.extend({
        clearTable: function (table) {
            $(table).find('tbody tr').remove();
        }
    });


    // Про посылке ajax-запроса и получении включать и выключать соответствующее окошко анимации
    $("body").ajaxSend(function (event, xhr, options) {
        $('#loading').stop().css({display: 'block'}).animate({'opacity': 1}, 300, 'linear');
    }).ajaxStop(function () {
        $('#loading').stop().animate({'opacity': 0}, 300, 'linear', function () {
            $(this).css('display', 'none')
        });
    });

    $('body').on('click', '.really', function () {
        if ($(this).hasClass('senddata') || $(this).hasClass('senddata-token'))
            return;

        if (!confirm('Действительно выполнить действие?'))
            return false;
    });

    $('body').on('click', '.setactive', function () {
        var $this = $(this);
        var table = $this.parent().parent().parent();
        var tr = $this.parent().parent();

        $('tr', table).removeClass('active');
        tr.addClass('active');
    })

    $('body').on('click', '.closecontent', function () {
        $('#loadcontent-container').css('display', 'none').html('');
    });

    /**
     * Отключаем скролл мышью на элементах с классом no-scroll
     */
    $('body').on('mousewheel','.js-scroll', function (event, delta) {
        if (delta === -1) {
            $(this).stop().scrollTo($(this).scrollTop() + 155, 100);
        }
        if (delta === 1) {
            $(this).stop().scrollTo($(this).scrollTop() - 155, 100);
        }
        return false;
    });

    $('body').on('mouseenter','.no-scroll', function () {
        document.onmousewheel = document.onwheel = function () {
            return false;
        };
    }).on('mouseleave','.no-scroll',function () {
        document.onmousewheel = document.onwheel = null;
    });

    // Логика работы механизма панельки для возвращения в начало страницы
    /*
     * показываем треугольник #triangleShow, чтобы пользователь видел возможность прокрутки страницы
     */
    {
        $(window).scroll(function () {
            var that = this;
            
            if ($(this).scrollTop() >= 600) {
                $('#toTop').addClass('showed');
                
                $('#triangleShow').addClass('showed');
                
                $( "#toTop" ).hover(function() {
                    $('#triangleShow').removeClass('showed');
                },function(){
                    if ($(that).scrollTop() >= 600) 
                        $('#triangleShow').addClass('showed');                    
                })
            } else {
                $('#triangleShow').removeClass('showed');
                $('#toTop').removeClass('showed');
            }
        });
        $('#toTop').click(function () {
            $('body,html').animate({scrollTop: 0}, 500, 'easeOutCubic');
        });
    }

});