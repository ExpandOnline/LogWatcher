/**
 * Let's wrap it up in a function in order to prevent collision with other stuff
 */
(function ($, window, document, undefined) {
    var container;
    var xhr = false;

    $(document).ready(function(){
        container = $('.lw-log-container');
        var links = $('.lw-log-switch > a');
        links.on('click', function(event){
            event.preventDefault();
            container.empty();
            container.html('Loading...');
            download($(this).attr('data-log-name'));
            links.removeClass('active');
            $(this).addClass('active');
        });
        $(document).ajaxStop(function(){
            xhr = false;
        });
    });

    /**
     * Download the new log
     *
     * @param name
     */
    function download(name){
        if (xhr){
            xhr.abort();
        }
        xhr = $.ajax({
            type: 'GET',
            dataType: 'json',
            url: logWatcher.webroot + 'log_watchers/' + name + '/json:1',
            success: function(data){
                container.html('<pre>' + data.content + '</pre>');
                $(document).scrollTop(container.height() + container.position().top - $(window).height() + 50);
            },
            error: function(jqXHR, textStatus, errorThrown){
                container.html(textStatus);
            }
        });
    }

})(jQuery, window, document);