/*$('form').on('submit', function(){
	event.preventDefault();
	var url = $(this).attr('action');
    var fd  = new FormData();
    var arr = $(this).find("input, textarea, select");

    for (var i = 0, len = arr.length; i < len; ++i) {
        fd.append($(arr[i]).attr('name'), $(arr[i]).val());
    }
	$.ajax({
        type: 'POST',
        url: url,
        cache       : false,
        contentType : false,
        processData : false,
        method:"POST",
        dataType    : 'json',
        data: fd,
        success: function(data) {
            console.log(data);
            $(location).attr('href', MAIN);
        }
    });

});*/
$("span.chair-reserved").on("click", function(){
	event.preventDefault();
	alert("Это место не доступно для брони");
});
$("span.chair-free").on("click", function(){
	event.preventDefault();
	//var data = $(this).text();
	$(this).css("background:yellow;");
	$(this).removeClass("chair-free");
	$(this).addClass("chair-reserved");
	$(this).addClass("chair-reserved-data");
	$(".reserve").show();
});
$("button.reserve").on("click", function(){
	event.preventDefault();
	var elem = $(".chair-reserved-data");
	var information = {};
	var idSeance = $(this).attr("id");
	for(var i = 0; i < elem.length;i++){
		information[i] = elem[i].innerHTML;

	}
	information["id_seance"] = idSeance;
	var url = MAIN+"films/reserved-place/";
	console.log(information);
	$.ajax({
		url         : url,
		data        : information,
		type        : 'POST',
		method		: "POST",
		dataType    : 'json',
		success     : function(answer) {
			alert(answer);
			for(var i = 0; i < elem.length;i++){
				$('<input>').attr({
					type: 'text',
					name: i+'place',
					value: elem[i].innerHTML
				}).appendTo('form.buy');

			}
			$(".buy").show();
			$(".reserve").hide();
		}
	});

});