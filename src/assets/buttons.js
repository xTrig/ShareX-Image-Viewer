var nextBtn, prevBtn;
var page;

window.onload = function() {
    nextBtn = document.getElementById('nextBtn');
    prevBtn = document.getElementById('prevBtn');

    nextBtn.addEventListener('click', changePage(1));
    prevBtn.addEventListener('click', changePage(-1));
    if($_GET['page']) {
        page = parseInt($_GET['page']);
    } else {
        page = 1;
    }
}

function changePage(i) {
    return function() {
        if(page + i < 1) { return; }

        page += i;
        window.location.href = './welcome.php?page=' + page;
    }
}