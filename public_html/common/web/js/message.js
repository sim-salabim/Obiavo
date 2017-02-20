Message = new function () {

    this.alert_container = '#alert-container';
    this.validation_errors_container = null;
    this.validation_errors_class = '';
    this.history = {};
    this.content = '#content';

    // Загружает и отображает контент в специальной дивке
    this.show = function (href, data) {
        if (!data) {
            data = {};
        }

        var $this = $(this),
            $target = $('#modal-window'),
            option = $.extend(
                  { remote: !/#/.test(href) && href, data: data }
                , $target.data()
                , $this.data()
            );

        // Сделаем так, чтобы, при клике на фон, окно не закрывалось
        // Иногда случайно можно кликнуть на фон и закрыть модальное окно с заполненными данными в формах
        option.backdrop = 'static';

        $target
            .modal(option)
            .one('hide', function () {
                $this.focus();
            });
    };

    this.close = function () {
        $('#modal-window [data-dismiss="modal"]').click();
    };
    
    this.successMessage = function (data){
        var mess = "<div class='alert alert-info alert-autocloseable-info'> \n\
                           "+data+"\n\
                    </div>";
        
        $(this.alert_container).html(mess);
        
        // скролим страницу для просмотра сообщения
        this.scrollToContainer($(this.alert_container));
        
        //скрываем сообщение через 6 сек
        $('.alert-autocloseable-info').delay(6000).fadeOut( "slow", function() {				
                $('.alert-autocloseable-info').prop("disabled", false);
        });
    };

    this.handlers = {}; // Массив зарегистрированных обработчиков команд
    this.handleMessage = function (msg) {
        var command = msg.type; // Наименование команды для испонения
        var data = msg.data; // Данные, отправленные сервером для команды

        if (this.handlers[command]) {
            this.handlers[command](data);
        }
    };

    /**
     * Отправить данные на определенный URL и обработать ответ
     * @param string url
     * @param object mass
     * @param object input_container (необязательно) контейнер, в котором необходимо будет очистить все инпуты от введенных данных
     */
    this.sendData = function (url, mass, input_container, input) {

        var $this = this;
        if (mass == undefined) mass = {};
        mass.json = 'true';

        // Отошлем массив и посмотрим ответ - нормально все или нет.
        $.ajax({
            type: "POST",
            url: url,
            data: mass,
            dataType: 'json',
            success: function (data) {                
                data.map(function (elem) {
                    try {
                        $this.handleMessage.call($this, elem);
                    } catch (e) {
                        console.log('error in handling message', e);
                    }
                });

                if (typeof(input) !== 'undefined') {
                    var dataFunction = input.attr('data-function');
                    // Включим обратно нашу кнопочку и дадим возможность кликать на неё
                    input.removeClass('disabled').attr('disabled', null);
                }

                if (data[0].type !== 'show_validation_errors' // Это не ответ о непройденной валидации отправленных данных
                    && data[0].type !== 'error' // Это не сообщение об ошибке
                    && (typeof(data[0].data) !== 'string' || data[0].data.indexOf('Navigation.updateCoreInfo(true)') === -1) // Это не ошибка ключа безопасности
                    && input_container !== false) {
                    Core.clearInputData(input_container);
                }

                // Выполняем произвольную функцию
                if (dataFunction !== undefined){
                    eval(dataFunction);
                }
            },
            error: function (data, status, e) {
                // Включим обратно нашу кнопочку и дадим возможность кликать на неё
                if (typeof(input) !== 'undefined') {
                    input.removeClass('disabled').attr('disabled', null);
                }
                console.log(status);
                //Notify.message(__('An error occurred during sending data to the server. Please try again later.'), 'error')
            }
        });
    };
    
    this.loadContent = function (url) {
        var container = $('#loadcontent-container');        
        $.ajax({
            type: "GET",
            url: url,
            data: { loadcontent: 'true', onlycontent : 'true' },
            dataType: 'html',
            success: function (data) {
                container.html(data);
                container.css('display', 'block');
                Message.scrollToContainer(container);
            },
            error: function (data, status, e) {
                if (e != 'abort') {
                    var errm = '<h3>Ошибка загрузки объекта</h3><p>Причина: ' + e + '</p><p>Попробуйте <span class="a-like" onclick="Navigation.reloadFullPage(); return false;">обновить страницу</span></p>';
                    container.html(errm);
                }
            }
        });
    };
    
    this.refreshPage = function () {        
        this.loadPage(document.location.href,true);
    };
    
    this.loadPage = function (http_link, old_history_page) {
        var that = this;

        // Если уже был отправлен какой-либо запрос, отменим его
        if (typeof(this.ajaxObj) === 'object') this.ajaxObj.abort();

        http_link = http_link.replace(/http(s)?:\/\/(.*?)\//, '/');

        this.ajaxObj = jQuery.ajax({
            type: "GET",
            url: http_link,
            data: {
                onlycontent : 'true' // Попросим наш сервер отдать только контентную часть, без основного шаблона
            },
            dataType: 'html',
            success: function (data) {
                that.setPage(http_link, data, old_history_page);
            },
            error: function (data, status, e) {
            }
        });
    }
    
     this.setPage = function (link, html_data, old_history_page) {

        this.setContent(html_data);
     }
     
     // Установить новый контент на странице
    this.setContent = function (content) {        
        this.content.html(content);
    };

    /**
     * Зарегистрировать обработчик команды, отправленной сервером
     * клиенту после обработки данных
     * @param string name
     * @param function action
     */
    this.registerHandler = function (name, action) {
        this.handlers[name] = action;
    };
    
    /**
     * Проскролить к нужному контейнеру
     * Иногда нужно для загружаемого контента          
     */
    this.scrollToContainer = function(container){
        $('html,body').animate({
          scrollTop: (container.offset().top)-20
        }, 1000);
    };
    
    this.generatePassword = function(data){        
        $('#password-input').val(data);
    }
};

Core.onFullLoad(function () {
    Message.container = jQuery(Message.container);
    Message.container2 = jQuery(Message.container2);
    Message.text = jQuery(Message.text);

    /* Создаем методы-хуки для отслеживания нажимаемых кнопок в интерфейсе */
    $('body').on('click', '.showmessage, .senddata', function () {
        var $this = $(this);
        var link = $this.attr('href');
        if (link === undefined || link == '#') link = $this.attr('data-link');
        if (link === undefined) link = document.location.href;

        var inputs_container = $this.attr('data-input');
        if (!$this.hasClass('disabled')) {
            if ($this.hasClass('senddata')) {

                if ($this.hasClass('really')) {
                    var text = 'Действительно выполнить действие?';
                    if ($this.attr('data-really-text')) {
                        text = $this.attr('data-really-text');
                    }

                    if (!confirm(text)) {
                        return false;
                    }
                }

                // Запретим кликать повторно на нашу кнопочку, пока идет отправка данных
                $this.attr('disabled', 'disabled').addClass('disabled');
                setTimeout(function(){
                    $this.removeClass('disabled').attr('disabled',null);
                },5000);
                
                Message.validation_errors_class = 'validation-errors';
                Message.validation_errors_container = $(inputs_container + ' .'+Message.validation_errors_class);
                Message.validation_errors_container.removeClass('has-error');

                if (inputs_container) {
                    Core.getInputData(inputs_container, function (data) {
                        var clearinputs = false;
                        if ($this.attr('data-params') === 'clearinputs') {
                            clearinputs = inputs_container;
                        }
                        Message.sendData(link, data, clearinputs, $this);
                    }, function() {
                        Message.handleMessage({
                            type:'show_validation_errors',
                            data: [
                                __('During the check you entered data errors were found. Please correct the marked fields and try again.')
                            ]
                        });
                    });
                } else {
                    var mess = {};
                    Message.sendData(link, mess, false, $this);
                }
            } else {
                // Если у элемента есть класс "mfp", значит надо открыть окно плагина MagnificPopup
                if ($this.hasClass('mfp')) {
                    $.magnificPopup.open({
                        items: { src: link },
                        type: 'ajax',
                        closeOnBgClick: false
                    }, 0);
                // Иначе - открываем стандартное окно бутстрапа
                } else {
                    Message.show(link);
                }
            }
        }

        return false;
    });
    
    $('body').on('click', '.loadcontent', function () {
        Message.loadContent($(this).attr('data-link'));
    });

    $('body').on('click', '.showimage', function () {
        var $this = $(this);
        var link = $this.attr('href');
        if (link == undefined || link == '#') link = $this.attr('data-link');
        if (link != undefined)
            Message.show('/image/?src=' + link);

        return false;
    });

    $('body').on('click', '.closemessage', function () {
        Message.close();
        return false;
    });

    $('body').on('click', '.refreshmessage', function () {
        Message.refresh();
        return false;
    });

//    $('body').on('click', '.validation-errors', function () {
//        $(this).empty().removeClass('active');
//        return false;
//    });
});


Message.registerHandler('nothing', function () {
    Message.close();
});

Message.registerHandler('success', function (data) {
    Message.successMessage(data);
});

Message.registerHandler('refreshpage', function () {
    Message.close();
    Message.refreshPage();
});

Message.registerHandler('loadcontent', function (data) {
    Message.loadContent(data);
});

Message.registerHandler('reloadpage', function (data) {
    Navigation.reloadFullPage(data);
});

Message.registerHandler('redirect', function (data) {
    Message.close();
    Navigation.loadPage(data);
});

Message.registerHandler('showmessage', function (data) {
    Message.show(data);
});

Message.registerHandler('generatepassword', function (data) {
    Message.generatePassword(data);    
});

Message.registerHandler('show_validation_errors_input', function (data) {
    
    if (!isObject(data)){
        return;
    }        
    
    Message.validation_errors_container.find('span.help-block').remove();
    
    $.each(data, function(input_name, messages) {

        var input = Message.validation_errors_container.find('input[id="'+input_name+'"],select[id="'+input_name+'"]');
//        var input = Message.validation_errors_container.find('#'+input_name);
        
        input.closest('.'+Message.validation_errors_class).addClass('has-error');
        
        messages.map(function(mess){            
            
            input.after('<span class="help-block">'+mess+'</span>');
        });
    });        
    
});

function supports_history_api() {
    return !!(window.history && history.pushState);
};

function isObject(val) {
    return val instanceof Object; 
}

Core.onFullLoad(function(){

    Message.content = jQuery(Message.content);
})