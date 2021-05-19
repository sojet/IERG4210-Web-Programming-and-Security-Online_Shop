var totalprice = 0.0;

function shoppinglist_update() {
	document.getElementById("shopping_cart").innerHTML = '<tr><th>Product</th><th>Price / 1 ($)</th><th colspan="3" >Quantity</th><th>Price ($)</th><th>Remove</th></tr>';
	
	var storage = JSON.parse(localStorage.getItem('cart_storage'));
	if (typeof(Storage) !== "undefined") {
		for(var i in storage) {
			addtocart(i);
		}
        display_total_price();
	} 
}

function getXmlHttpRequest(){
    var xhr = null;
    // for Firefox, IE7+, Safari,...
    if (window.XMLHttpRequest){
        xhr = new XMLHttpRequest();
    }
    else if (window.ActiveXObject){
        xhr = new ActiveXObject("Microsoft.XMLHTTP");
    }
    return xhr;
}
function addtocart(pid){
    var xhttp = getXmlHttpRequest();
    xhttp.onreadystatechange = function(){
        if (xhttp.readyState == 4 & xhttp.status == 200){
            parseJSON(xhttp);
        }
    };
    xhttp.open("POST", "../lib/cartstorage.php?pid=" + encodeHTML(pid),true);
	xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send();
}

function parseJSON(xhttp) {
	var txt = xhttp.responseText;
	var JSONObj = JSON.parse(txt.slice(1,txt.length - 1));
	var storage = JSON.parse(localStorage.getItem('cart_storage'));
	var is = false;
	for (var i in storage) {
		if (JSONObj.pid == i) {
			is = true;
			break;
		}
	}

	if (is == false) 
            insertIntoLS(JSONObj.pid);
    display_shopping_list(JSONObj.pid, JSONObj.name, JSONObj.price);
    console.log(JSONObj.pid, JSONObj.name, JSONObj.price );
}

//insert product to shopping localstore
function insertIntoLS(pid) {
	var quantity = 1;

	var storage = localStorage.getItem('cart_storage');
	if(storage == undefined)
		storage = {};
	else
		storage = JSON.parse(localStorage.getItem('cart_storage'));

	storage[pid] = 1;
	localStorage.setItem('cart_storage', JSON.stringify(storage));
}

function display_shopping_list(pid, name, price) {
	var table_update =  document.getElementById("shopping_cart").innerHTML;
	pid = encodeHTML(pid);
	name = encodeHTML(name);
	price = encodeHTML(price);
	var storage = JSON.parse(localStorage.getItem('cart_storage'));
	var num = storage[pid];
    var subtotal_price = num*price;

    table_update += '<tr>';
    table_update += '<td id="name' + pid + '" >' + name + '</td>';
    table_update += '<td id="price' + pid + '" >' + price + '</td>';
    table_update += '<td id="add' + pid + ' " ><button type="button" onclick = "addQuantity('+ pid +')">+</button></td>';
    table_update += '<td id="quantity' + pid + '" >' + num + '</td>';
    table_update += '<td id="reduce' + pid + ' " ><button type="button" onclick = "reduceQuantity('+ pid +')">-</button></td>';
    table_update += '<td id="subtotal' + pid +'">' + subtotal_price + '</td> ';
    table_update += '<td id="remove_' + pid + ' " ><button type="button" onclick = "removeProduct('+ pid +')">Remove</button></td>';
    table_update += '</tr>';

	totalprice += num * price;
	document.getElementById("shopping_cart").innerHTML = table_update;
    display_total_price();
}

//Add number in the shopping list
function addQuantity(pid){
	pid = encodeHTML(pid);
	var storage = JSON.parse(localStorage.getItem('cart_storage'));
	var add = parseInt(storage[pid.toString()], 10) + 1;
	storage[pid.toString()] += 1;
	localStorage.setItem('cart_storage', JSON.stringify(storage));

    document.getElementById("quantity"+pid).innerHTML = add;
    totalprice += parseFloat(document.getElementById("price" + pid).innerHTML,10);
    var add_subtotal = parseFloat(document.getElementById("price" + pid).innerHTML,10);
    add_subtotal=add_subtotal*add;
    document.getElementById("subtotal"+pid).innerHTML = add_subtotal.toFixed(2);

    display_total_price();
}

//Reduce number in the shopping list
function reduceQuantity(pid){
	pid = encodeHTML(pid);
	var storage = JSON.parse(localStorage.getItem('cart_storage'));
	var reduce = parseInt(storage[pid.toString()], 10);
	if (reduce > 1) {
		reduce -= 1;
		storage[pid.toString()] -= 1;
		localStorage.setItem('cart_storage', JSON.stringify(storage));
	
        document.getElementById("quantity"+pid).innerHTML = reduce;
        var reduce_subtotal = parseFloat(document.getElementById("price" + pid).innerHTML,10);
        reduce_subtotal=reduce_subtotal*reduce;
        document.getElementById("subtotal"+pid).innerHTML = reduce_subtotal.toFixed(2);
        totalprice -= parseFloat(document.getElementById("price" + pid).innerHTML,10);
	}
    display_total_price();
}

//Remove product in the cart and localstorage
function removeProduct(pid) {
	pid = encodeHTML(pid);
	var storage = JSON.parse(localStorage.getItem('cart_storage'));
	delete storage[pid];
	localStorage.setItem('cart_storage', JSON.stringify(storage));
	totalprice = 0.0;
    shoppinglist_update();
}

function display_total_price(){
    document.getElementById("total_price").innerHTML = "Shopping List: $" + totalprice.toFixed(2);
}

//check the product whether in the cart
function checkProduct(pid){
	pid = encodeHTML(pid);
	var storage = JSON.parse(localStorage.getItem('cart_storage'));
	var is = false;
	for (var i in storage) {
		if (pid == i) {
			is = true;
			break;
		}
	}

	if (is == false) {
        addtocart(pid);
		location.reload();
	}
}

function encodeHTML(s) {
    return s.toString().replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/"/g, '&quot;');
}

function fill_in_form(){
	var my_cart_info = localStorage.getItem('cart_storage');

	    var shopping = document.getElementById("shopping_form").innerHTML;
		var my_cart_info = JSON.parse(localStorage.getItem('cart_storage'));

		var i=1;
		for (var k in my_cart_info){
			var item_name = document.createElement("INPUT");
			item_name.type = "hidden";
			item_name.name = "item_name_"+ i;
			item_name.value = document.getElementById("name"+k).innerHTML;
			console.log(item_name.value);

			var item_quantity = document.createElement("INPUT");
			item_quantity.type = "hidden";
			item_quantity.name = "quantity_"+ i;
			item_quantity.value = my_cart_info[k].toString();
			console.log(item_quantity.value);

			var item_amount = document.createElement("INPUT");
			item_amount.type = "hidden";
			item_amount.name = "amount_"+ i;
			item_amount.value = document.getElementById("price"+k).innerHTML;
			console.log(item_amount.value);
			
			shopping_form.appendChild(item_name);
			shopping_form.appendChild(item_quantity);
			shopping_form.appendChild(item_amount);

			i++;

		}
		
}

function cartSubmit(){
	fill_in_form();
	var my_cart_info = localStorage.getItem('cart_storage');
	console.log(my_cart_info);
	var form = document.getElementById("idofshopping_cart");
	var xhr = (window.XMLHttpRequest)
			?	new XMLHttpRequest()
			:	new ActiveXObject("Microsoft.XMLHTTP"),
		async = true;
	xhr.open('POST', '../lib/checkout-process.php?action=genDigest',async);
	xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

	xhr.onreadystatechange = function(){
		if (xhr.readyState ==4 && xhr.status == 200){
			var output = xhr.responseText;
			console.log(output);
			if(output == '{}'){
				return false;
			}
			var json = JSON.parse(output);
			var resp = JSON.parse(json);
			console.log(resp["digest"]);
			console.log(resp["invoice"]);
			var custom = document.getElementsByName("custom");
			for ( var i = 0; i < custom.length; i++){
				custom[i].value = resp["digest"];
			}
			var invoice = document.getElementsByName("invoice");
			for ( var i = 0; i<invoice.length; i++){
				invoice[i].value = resp["invoice"];
			}
			localStorage.removeItem('cart_storage');
			form.submit();
		}
		return false;
	}
	xhr.send("cart="+JSON.stringify(my_cart_info));
}

