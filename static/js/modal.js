		var modal0 = document.getElementById('popup_box_0');
		var modal1 = document.getElementById('popup_box_1');
		var modal2 = document.getElementById('popup_box_2');
		var modal3 = document.getElementById('popup_box_3');
		var modal4 = document.getElementById('popup_box_4');
		var modal5 = document.getElementById('popup_box_5');
		var modal6 = document.getElementById('popup_box_6');
		var btn = document.getElementsByClassName('popup_link');
		var span = document.getElementsByClassName("close");
		
		btn[0].onclick = function() {
			modal0.style.display = "block";
		}
		btn[1].onclick = function() {
			modal1.style.display = "block";
		}
		btn[2].onclick = function() {
			modal2.style.display = "block";
		}
		btn[3].onclick = function() {
			modal3.style.display = "block";
		}
		btn[4].onclick = function() {
			modal4.style.display = "block";
		}
		btn[5].onclick = function() {
			modal5.style.display = "block";
		}
		btn[6].onclick = function() {
			modal6.style.display = "block";
		}
		for(i=0;i<7;i++)
		{
			span[i].onclick = function() {
				modal0.style.display = "none";
				modal1.style.display = "none";
				modal2.style.display = "none";
				modal3.style.display = "none";
				modal4.style.display = "none";
				modal5.style.display = "none";
				modal6.style.display = "none";
			}
		}
		window.onclick = function(event) {
			if (event.target == modal0) {
				modal0.style.display = "none";
			}
			if (event.target == modal1) {
				modal1.style.display = "none";
			}
			if (event.target == modal2) {
				modal2.style.display = "none";
			}
			if (event.target == modal3) {
				modal3.style.display = "none";
			}
			if (event.target == modal4) {
				modal4.style.display = "none";
			}
			if (event.target == modal5) {
				modal5.style.display = "none";
			}
			if (event.target == modal6) {
				modal6.style.display = "none";
			}
		}