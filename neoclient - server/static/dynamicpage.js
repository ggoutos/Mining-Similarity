

function changePage(_link) {
	
		history.pushState(null, null, _link);
        $("body").load(_link);
        return false;
	
}