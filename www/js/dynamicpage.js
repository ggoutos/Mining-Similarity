

function changePage(_link) {
	
		history.pushState(null, null, _link);
        $("#page").load(_link);
        return false;
	
}