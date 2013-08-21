/**
 * Let's wrap it up in a function in order to prevent collision with other stuff
 */
(function ($, window, document, undefined) {
    var container, select, refreshBtn, hashChanged, timer, countdown, lastRefresh;
    var tracker = {
        name: false,
        hash: false
    };
    var xhr = false;

    $(document).ready(function(){
        container = $('.lw-log-container');
        select = $('.lw-switch');
        refreshBtn = $('.lw-refresh').html('refresh');
        hashChanged = $('.lw-hash-changed');
        lastRefresh = {wrapper: $('.lw-last-refresh')};
        lastRefresh.time = lastRefresh.wrapper.find('span');

        select.on('change', function(){
            tracker.name = $(this).val();
            select.val(tracker.name);
            download();
        });

        refreshBtn.on('click', function(event){
            event.preventDefault();
            download();
        });

        resetTimer();

        $(document).ajaxStop(function(){
            xhr = false;
        });
    });

    /**
     * Download the new log
     */
    function download(){
        if (!tracker.name){
            return;
        }
        container.empty();
        container.html('Loading...');
        hashChanged.hide();

        if (xhr){
            xhr.abort();
        }
        xhr = $.ajax({
            type: 'GET',
            dataType: 'json',
            url: logWatcher.webroot + 'log_watchers/' + tracker.name + '?json=1',
            success: function(data){
                container.html('<pre>' + data.content + '</pre>');
                tracker.hash = data.hash;
                lastRefresh.time.html(data.time);
                lastRefresh.wrapper.show();
                $(document).scrollTop(container.height() + container.position().top - $(window).height() + 50);
                resetTimer();
            },
            error: function(jqXHR, textStatus, errorThrown){
                container.html(textStatus);
            }
        });
    }

    function resetTimer(){
        countdown = 5;
        if (!timer){
            timer = setInterval(timerTick, 1000);
        }
    }

    function timerTick(){
        if (countdown-- > 0 || xhr || !tracker.name || !tracker.hash){
            return;
        }
        xhr = $.ajax({
            type: 'GET',
            dataType: 'text',
            url: logWatcher.webroot + 'log_watchers/' + tracker.name + '?json=1&hashOnly=1',
            success: function(hash){
                if (hash !== tracker.hash){
                    lastRefresh.wrapper.hide();
                    hashChanged.show();
                    clearInterval(timer);
                    timer = null;
                } else {
                    resetTimer();
                }
            },
            error: function(jqXHR, textStatus, errorThrown){
                resetTimer();
            }
        });
    }

})(jQuery, window, document);