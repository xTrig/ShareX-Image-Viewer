$(document).ready(function() {
    var menu = [
        {
            name: 'view',
            title: 'View this image',
            img: 'assets/save.png',
            fun: function(data, event) {
                $(location).attr('href', './view_image.php?img=' + data.trigger[0].id);
            }
        },
        {
            name: 'download',
            title: 'Download this image',
            img: 'assets/save.png',
            fun: function(data, event) {
                var dlLink = document.createElement('a');
                dlLink.href = "./" + data.trigger[0].id;
                dlLink.download = data.trigger[0].id;
                document.body.appendChild(dlLink);
                dlLink.click();
                document.body.removeChild(dlLink);
            }
        },
        {
        name: 'delete',
        title: 'Delete this image',
        img: 'assets/delete.png',
        fun: function (data, event) {
            $(location).attr('href', './delete_image.php?img=' + data.trigger[0].id + "&page=" + $_GET['page']);
            
        }
    }];

$('.ctx').contextMenu(menu, {triggerOn:'contextmenu'});
});